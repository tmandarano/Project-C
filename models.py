import logging
import datetime
import StringIO
import pickle

from google.appengine.ext import db
from google.appengine.ext import blobstore
from google.appengine.api import images
from google.appengine.api import urlfetch

from lib import EXIF
from django.utils import simplejson
from lib.geo.geomodel import GeoModel


class SpecializedJSONEncoder(simplejson.JSONEncoder):
    """ Serializes additional objects beyond the default simplejson encoder """

    def default(self, obj):
        obj_type = type(obj)
        if obj_type is datetime.datetime:
            return obj.isoformat()
        elif obj_type is db.GeoPt:
            return ','.join(map(str, (obj.lat, obj.lon)))
        elif obj_type is db.Key:
            return obj.id_or_name()
        elif isinstance(obj, JSONableModel):
            return obj.serialize()
        return simplejson.JSONEncoder.default(self, obj)


class JSONableModel(db.Model):
    """ Generic model that is JSON serializable """

    serializable = ()

    def serialize(self):
        properties = self.properties()
        keys = properties.keys()

        allowed_keys = filter(lambda x: x in self.serializable, keys)

        obj = {}
        for x in allowed_keys:
            obj[x] = properties[x].get_value_for_datastore(self)

        return obj

    def to_json(self):
        return simplejson.dumps(self.serialize(), cls=SpecializedJSONEncoder)


class User(JSONableModel):
    """ Service User """
    email = db.EmailProperty()
    display_name = db.TextProperty()

    serializable = ('email', 'display_name', )

    def serialize(self):
        obj = super(User, self).serialize()
        obj['key'] = str(self.key())
        return obj


def _read_EXIF(fobj):
    return EXIF.process_file(fobj)


def ref_to_sign(r):
    """ Convert EXIF.ASCII to a sign """
    return 1 if r.values in ('N', 'E', 'T') else -1


def ratio_to_frac(r):
    """ Converts EXIF.Ratio to a float """
    return float(r.num) / float(r.den) if r.den is not 0 else float('nan')


def decimalify(*xs):
    """ Turns an array of degrees minutes seconds into a decimal
        Arg:
            xs - a bunch of numbers representing DMS
        Returns:
            decimal representation
    """
    s = 0
    for x in reversed(xs):
        s = x + s / 60.0
    return s


def _iOS_coord_to_decimal(coord, ref=None):
    """ Convert iOS photo EXIF to decimal
        Args:
            coord - EXIF GPS coordinate in DMS
            ref - EXIF GPS coordinate ref
        Returns:
            decimal representation
    """
    coord = map(ratio_to_frac, coord.values)
    sign = 1 if not ref else ref_to_sign(ref)
    return sign * decimalify(*coord)


class Photo(JSONableModel, GeoModel):
    """ Photo
        A Photo's information is available as a JSON object at:

        GET /photos/<key>

        Overview of information:

        key - the string key that uniquely identifies this photo in the
            service
        taken_at - time the photo EXIF reports it was taken
        caption - a caption specified by the user
        location - the GPS coordinates the photo EXIF reported
        location_geocells - http://geohash.org/
        geoname - reverse geocode result from ws.geonames.org
        user - a string key that uniquely identifies the photo's user

        In order to get a photo's image data:

        GET /photos/<key>/<OS>/<size>

        where OS is the mobile operating system and size is one of the
        following:

        t - tiny
        s - small
        m - medium
        f - full

        The default size is full. Valid operating systems are:

        iOS
        iOS_retina
    """
    user = db.ReferenceProperty(User, required=True)
    caption = db.StringProperty()
    taken_at = db.DateTimeProperty()
    created_at = db.DateTimeProperty(auto_now_add=True)
    updated_at = db.DateTimeProperty(auto_now=True)
    exif = db.BlobProperty()
    geoname = db.BlobProperty()

    img_orig = blobstore.BlobReferenceProperty(required=True)

    img_ios_t = db.BlobProperty()
    img_ios_s = db.BlobProperty()
    img_ios_m = db.BlobProperty()
    img_ios_f = db.BlobProperty()

    img_ios_r_t = db.BlobProperty()
    img_ios_r_s = db.BlobProperty()
    img_ios_r_m = db.BlobProperty()
    img_ios_r_f = db.BlobProperty()

    serializable = ('taken_at', 'caption', 'location', )

    def put(self, **kwargs):
        """ Postprocess img_orig """
        saved = True
        try:
            self.key()
        except db.NotSavedError:
            saved = False

        # Image, thumbnails, location are not mutable
        if not saved:
            self._postprocess()

        return super(Photo, self).put(**kwargs)

    def _img_orig_fobj(self):
        if type(self.img_orig) is blobstore.BlobInfo:
            return blobstore.BlobReader(self.img_orig, buffer_size=2**10)
        else:
            return StringIO.StringIO(self.img_orig)

    def _get_geo_name(self):
        """ Attempts to get the results of a reverse geocode """
        try:
            result = urlfetch.fetch(
                'http://ws.geonames.org/findNearestAddressJSON?lat=%f&lng=%f' %
                (self.location.lat, self.location.lon)).content
            # TODO handle cases when server is overloaded.
        except urlfetch.DownloadError:
            result = 'Unknown'
        except urlfetch.Error:
            result = 'Unknown'
        return simplejson.loads(result)

    def _set_location(self, geopt):
        """ Sets the location of the photo to geopt and updates the location
            hash and reverse geocode results.
        """
        self.location = geopt
        self.geoname = pickle.dumps(self._get_geo_name())

        # Sync geocell indexing
        self.update_location()

    def _postprocess(self):
        """ Gather image data and make thumbnails.
            1. Read and store EXIF
            2. Update image information based on EXIF
            2. Generate smaller sizes
        """
        logging.info("Processing original image")

        fobj = self._img_orig_fobj()
        exif, byterange = _read_EXIF(fobj)
        logging.info(byterange)
        self.exif = pickle.dumps(exif)
        logging.info("Got EXIF: \n%s" % exif)

        lat = 0
        try:
            lat = _iOS_coord_to_decimal(
                exif['GPS GPSLatitude'], exif['GPS GPSLatitudeRef'])
        except KeyError:
            pass
        lon = 0
        try:
            lon = _iOS_coord_to_decimal(
                exif['GPS GPSLongitude'], exif['GPS GPSLongitudeRef'])
        except KeyError:
            pass
        try:
            dir = _iOS_coord_to_decimal(
                exif['GPS GPSImgDirection'], exif['GPS GPSImgDirectionRef'])
        except KeyError:
            pass
        try:
            alt = _iOS_coord_to_decimal(
                exif['GPS GPSAltitude'], exif['GPS GPSAltitudeRef'])
        except KeyError:
            pass
        try:
            gpstime = _iOS_coord_to_decimal(
                exif['GPS GPSTimeStamp'])
        except KeyError:
            pass
        image_time = datetime.datetime.now()
        try:
            image_time = datetime.datetime.strptime(exif['Image DateTime'].values,
                '%Y:%m:%d %H:%M:%S')
        except KeyError:
            pass
        try:
            info = exif['Image GPSInfo'].values
        except KeyError:
            pass
        try:
            horiz = True if exif['Image Orientation'].values else False
        except KeyError:
            pass

        self.taken_at = image_time
        self._set_location(db.GeoPt(lat, lon))

        self._generate_thumbnails()

    def _generate_thumbnail(self, width, height):
        """ Generates a thumbnail of size width x height. Unfortunately the
            only allowed output encodings are JPEG and PNG. Since this
            operates on photos, JPEG has been selected.
        """
        logging.info("Generating thumbnail %dx%d" % (width, height))
        if type(self.img_orig) is blobstore.BlobInfo:
            img = images.Image(blob_key=str(self.img_orig.key()))
        else:
            img = images.Image(image_data=self.img_orig)
        img.resize(width, height)
        # TODO rotate the image if necessary
        # I'm feeling luckyify?

        result = None
        try:
            result = img.execute_transforms(output_encoding=images.JPEG, quality=85)
        except Exception, e:
            logging.error("Transformations failed: %s" % e)
        return result

    def _generate_thumbnails(self):
        self.img_ios_t = self._generate_thumbnail(50, 50)
        self.img_ios_s = self._generate_thumbnail(61, 61)
        self.img_ios_m = self._generate_thumbnail(125, 130)
        self.img_ios_f = self._generate_thumbnail(290, 360)

        self.img_ios_r_t = self.img_ios_t
        self.img_ios_r_s = self._generate_thumbnail(122, 122)
        self.img_ios_r_m = self._generate_thumbnail(250, 260)
        self.img_ios_r_f = self._generate_thumbnail(520, 580)

    def get_os_img(self, os='iOS', size='t'):
        """ Returns a bytestring valid image of the corresponding OS and size.
            Args:
                os - the OS type
                size - the size (one of t, s, m, l, o). The sizes are tiny,
                    small, medium, large, and original
        """
        if os == 'iOS':
            return self.get_iOS_img(size)
        elif os == 'iOS_retina':
            return self.get_iOS_retina_img(size)

    def get_iOS_img(self, size='t'):
        if size == 't':
            return self.img_ios_t
        elif size == 's':
            return self.img_ios_s
        elif size == 'm':
            return self.img_ios_m
        elif size == 'f':
            return self.img_ios_f
        else:
            return self.img_ios_f

    def get_iOS_retina_img(self, size='t'):
        if size == 't':
            return self.img_ios_r_t
        elif size == 's':
            return self.img_ios_r_s
        elif size == 'm':
            return self.img_ios_r_m
        elif size == 'f':
            return self.img_ios_r_f
        else:
            return self.img_ios_r_f

    def thumbs(self):
        """ Get summary of number of thumbs up """
        return self.thumb_set.filter('up', True).count() - \
               self.thumb_set.filter('up', False).count()

    def serialize(self):
        obj = super(Photo, self).serialize()
        obj['key'] = str(self.key())
        obj['user'] = str(self.user.key())
        obj['thumbs'] = self.thumbs()
        obj['comments'] = [x for x in self.comment_set]
        obj['tags'] = [x for x in self.tag_set]
        obj['geoname'] = pickle.loads(self.geoname)
        return obj


class Tag(JSONableModel):
    """ A photo can be tagged by a user with a phrase.
        TODO perhaps expand this to also allow tagging an area with a phrase?
    """
    tag = db.StringProperty(required=True, multiline=True)
    photo = db.ReferenceProperty(Photo, required=True)
    user = db.ReferenceProperty(User, required=True)
    created_at = db.DateTimeProperty(auto_now_add=True)

    serializable = ('tag', 'created_at', )

    def serialize(self):
        obj = super(Tag, self).serialize()
        obj['photo'] = str(self.photo.key())
        obj['user'] = str(self.user.key())
        return obj


class Thumb(JSONableModel):
    """ A thumb is a representation of a user's opinion on a photo. A user can
        only change a thumb up or down once they have given an opinion.
    """
    up = db.BooleanProperty(required=True)
    user = db.ReferenceProperty(User, required=True)
    photo = db.ReferenceProperty(Photo, required=True)

    serializable = ('up', )

    def serialize(self):
        obj = super(Thumb, self).serialize()
        obj['photo'] = str(self.photo.key())
        obj['user'] = str(self.user.key())
        return obj


class Comment(JSONableModel):
    comment = db.StringProperty(required=True, multiline=True)
    photo = db.ReferenceProperty(Photo, required=True)
    user = db.ReferenceProperty(User, required=True)
    created_at = db.DateTimeProperty(auto_now_add=True)

    serializable = ('comment', 'created_at', )

    def serialize(self):
        obj = super(Comment, self).serialize()
        obj['photo'] = str(self.photo.key())
        obj['user'] = str(self.user.key())
        return obj
