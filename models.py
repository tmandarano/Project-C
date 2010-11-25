import logging
import datetime
import StringIO
import pickle
import urllib2

from google.appengine.ext import db
from google.appengine.ext import blobstore
from google.appengine.api import images

from lib import EXIF
from django.utils import simplejson as json
from lib.geo.geomodel import GeoModel


def _json_default_handler(obj):
    obj_type = type(obj)
    if obj_type is datetime.datetime:
        return obj.isoformat()
    elif obj_type is db.GeoPt:
        return ','.join(map(str, (obj.lat, obj.lon)))
    elif obj_type is db.Key:
        return obj.id_or_name()
    else:
        raise TypeError('Object %s = %s is not JSON serializable' %
                (type(obj), repr(obj)))


class JSONableModel(db.Model):

    def to_json(self):
        return json.dumps(self.properties(), default=_json_default_handler)


class User(JSONableModel):
    email = db.EmailProperty()
    display_name = db.TextProperty()

    def to_json(self):
        properties = self.properties()
        keys = properties.keys()

        key_whitelist = ('email', 'display_name', )
        allowed_keys = filter(lambda x: x in key_whitelist, keys)

        obj = {}
        for x in allowed_keys:
            obj[x] = properties[x].get_value_for_datastore(self)
        obj['key'] = str(self.key())

        return json.dumps(obj, default=_json_default_handler)


def _read_EXIF(fobj):
    return EXIF.process_file(fobj)


def _scrub_EXIF(image_data, byterange):
    """ Remove EXIF data from the image """
    logging.info("Scrubbing EXIF from image")
    logging.debug("EXIF header found at range %s" % str(byterange))

    # TODO
    scrubbed = list(image_data)
    for i in range(*byterange):
        scrubbed[i] = chr(0x0)
    return db.Blob(''.join(scrubbed))


class Photo(JSONableModel, GeoModel):
    user = db.ReferenceProperty(User, required=True)
    caption = db.StringProperty()
    created_at = db.DateTimeProperty(auto_now_add=True)
    updated_at = db.DateTimeProperty(auto_now=True)
    exif = db.TextProperty()

    geoname = db.StringProperty(multiline=True)

    img_orig = blobstore.BlobReferenceProperty(required=True)

    img_ios_t = db.BlobProperty()
    img_ios_s = db.BlobProperty()
    img_ios_m = db.BlobProperty()
    img_ios_f = db.BlobProperty()

    img_ios_r_t = db.BlobProperty()
    img_ios_r_s = db.BlobProperty()
    img_ios_r_m = db.BlobProperty()
    img_ios_r_f = db.BlobProperty()

    def put(self, **kwargs):
        """ Postprocess img_orig before storing. """
        saved = True
        try:
            self.key()
        except db.NotSavedError:
            saved = False

        # Image, thumbnails, location are not mutable
        if not saved:
            self._postprocess()

            # Sync geocell indexing
            self.update_location()
            self.geoname = pickle.dumps(self._get_geo_name())


        return super(Photo, self).put(**kwargs)

    def _get_geo_name(self):
        try:
            result = urllib2.urlopen(
                'http://ws.geonames.org/findNearestAddressJSON?lat=%f&lng=%f' %
                (self.location.lat, self.location.lon)).read()
        except urllib2.URLError, e:
            result = 'Unknown'
        return result

    def _img_orig_fobj(self):
        if type(self.img_orig) is blobstore.BlobInfo:
            return blobstore.BlobReader(self.img_orig, buffer_size=2**10)
        else:
            return StringIO.StringIO(self.img_orig)

    def _postprocess(self):
        """ Gather image data and make thumbnails.
            1. Read and store EXIF
            2. Generate smaller sizes
        """
        logging.info("Processing original image")

        fobj = self._img_orig_fobj()
        exif, byterange = _read_EXIF(fobj)
        logging.info(byterange)
        self.exif = pickle.dumps(exif)
        logging.info("Got EXIF: \n%s" % exif)

        # TODO Can't edit Blobstore info so maybe we shouldn't offer original
        # size images.
        #logging.info("Scrubbing EXIF from image_data")
        #image_data = _scrub_EXIF(image_data, byterange)

        self._generate_thumbnails()

    def _generate_thumbnail(self, width, height):
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
        logging.info("Returning image of size %s" % size)
        if size == 't':
            return self.img_ios_t
        elif size == 's':
            return self.img_ios_s
        elif size == 'm':
            return self.img_ios_m
        elif size == 'f':
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

    def to_json(self):
        properties = self.properties()
        keys = properties.keys()

        key_whitelist = ('created_at', 'updated_at', 'caption', 'location',
            'location_geocells', 'geoname', )
        allowed_keys = filter(lambda x: x in key_whitelist, keys)

        obj = {}
        for x in allowed_keys:
            obj[x] = properties[x].get_value_for_datastore(self)
        obj['key'] = str(self.key())
        obj['user'] = str(self.user.key())

        return json.dumps(obj, default=_json_default_handler)
