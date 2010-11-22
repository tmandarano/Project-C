import logging
import urllib
import datetime

from google.appengine.ext import db
from google.appengine.ext import webapp
from google.appengine.runtime import apiproxy_errors
from google.appengine.ext import blobstore
from google.appengine.ext.webapp import blobstore_handlers

import lg
import models


MIMETYPE_JSON = 'application/json'


unquote = urllib.unquote


# TODO provide ws.geonames.org cache?
# http://ws.geonames.org/findNearestAddress?lat=37.451&lng=-122.18
# http://ws.geonames.org/findNearestAddressJSON?lat=37.451&lng=-122.18

class PhotoProximity(webapp.RequestHandler):

    def get(self, geopt, distance):
        """ Find photos within distance of GeoPt
            Args:
                required for URL to match:
                    geopt - lat,lon
                    distance - in meters
                optional:
                    max_age - results will be restricted to at most this many 
                        minutes in the past (in set float((0, inf)))
                    max_results - at most return this many results (in set
                        int([1, 20]))

            Gives a JSON array with max_results photos.
        """
        try:
            geopt = db.GeoPt(*map(float, unquote(geopt).split(',')))
        except ValueError:
            self.response.set_status(
                400, 'GeoPt is lat,lon. Lat and Lon must be numbers')
            return

        try:
            distance = float(unquote(distance))
        except ValueError:
            self.response.set_status(
                400, 'distance must be a number in meters')
            return

        max_results = 20
        try:
            max_results = int(unquote(self.request.get('max_results',
                                                        default_value='20')))
        except ValueError:
            self.response.set_status(400, 'max_results must be integer')
            return
        max_age = 30
        try:
            max_age = float(unquote(self.request.get('max_age',
                                                     default_value='30')))
        except ValueError:
            self.response.set_status(400, 'max_age must be number')
            return

        query = models.Photo.all()

        if max_age:
            max_age = max(max_age, 1e-6)
            query.filter('created_at >',
                         datetime.datetime.now() - \
                         datetime.timedelta(minutes=max_age))

        photos = models.Photo.proximity_fetch(
            query,
            geopt,
            max_results=min(20, max(max_results, 1)),
            max_distance=distance)

        json_photos = map(lambda x: x.to_json(), photos)

        self.response.out.write('[%s]' % ', '.join(json_photos))


class PhotoCreatePath(blobstore_handlers.BlobstoreUploadHandler):

    def get(self):
        """ Get Blobstore upload URL
            Upload to this URL to actually be able to store a photo.
        """
        self.response.out.write(str(blobstore.create_upload_url('/photos')))

class PhotoCreate(blobstore_handlers.BlobstoreUploadHandler):

    def post(self):
        """ Store photo
            Since this is a blobstore handler and does not respond RESTfully
            the only HTTP status codes allowed are 301, 302, and 303. Any
            errors are represented by 302 and an ASCII description of the
            error. Proper requests will redirect with a 303.
        """
        uploads = self.get_uploads()
        if len(uploads) is not 1:
            self.response.set_status(302, 'Need one file')
            return
        image = uploads[0]

        caption = unquote(self.request.get('caption'))
        geopt = unquote(self.request.get('geopt'))

        try:
            geopt = db.GeoPt(*map(float, (unquote(geopt).split(','))))
        except:
            self.response.set_status(302, 'GeoPt must be lat,lon')
            image.delete()
            return

        photo = models.Photo(img_orig=image.key(), caption=caption, location=geopt)
        try:
            photo.put()
        except Exception, e:
            logging.error('Postprocessing failed: %s' % e)
            self.response.set_status(302, 'Postprocessing failed')
            image.delete()
            return
        self.redirect('/photos/%s' % photo.key())


class PhotoResource(webapp.RequestHandler):

    def get(self, key):
        self.response.headers["Content-Type"] = MIMETYPE_JSON
        try:
            photo = models.Photo.get(unquote(key))
        except db.BadKeyError:
            self.error(404)
        self.response.out.write(photo.to_json())

    def put(self, key):
        self.response.headers["Content-Type"] = MIMETYPE_JSON

        try:
            photo = models.Photo.get(unquote(key))
        except db.BadKeyError:
            self.error(404)

        try:
            caption = self.request.get('caption', default_value=None)
            if caption is not None:
                photo.caption = unquote(caption)
            photo.put()
        except apiproxy_errors.CapabilityDisabledError:
            # fail
            pass
        self.redirect('/photos/%s' % key)


class PhotoImageResource(webapp.RequestHandler):

    def get(self, key, os, size):
        self.response.headers["Content-Type"] = "image/jpeg"
        #self.response.headers.add_header("Expires", "")

        try:
            id = long(id)
        except ValueError:
            self.response.set_status(400, 'Illegal ID')
            return

        try:
            photo = models.Photo.get(unquote(key))
        except:
            self.error(404)
            return

        self.response.out.write(photo.get_os_img(unquote(os),
                                                 unquote(size)))


class Admin(webapp.RequestHandler):

    def get(self, *args):
        model, command = args

        self.response.out.write(model)
        self.response.out.write(command)
        self.response.out.write('asdf')
