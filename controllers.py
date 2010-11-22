import logging
import urllib

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
            geopt - lat,lon
            distance - in meters
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

        photos = models.Photo.proximity_fetch(
            models.Photo.all(),
            geopt,
            max_results=10,
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

    def put(self, *args):
        self.response.headers["Content-Type"] = MIMETYPE_JSON
        try:
            # try to save
            # TODO
            #for photo in models.Photo.all():
            #    photo.delete()
            #photo = models.Photo()
            #photo.put()
            pass
        except apiproxy_errors.CapabilityDisabledError:
            # fail
            pass
        self.response.out.write('"%s\n\n%s"' % (self.__dict__, args))


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
