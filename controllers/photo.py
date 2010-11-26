import logging
import datetime

from google.appengine.ext import db
from google.appengine.ext import webapp
from google.appengine.runtime import apiproxy_errors
from google.appengine.ext import blobstore
from google.appengine.ext.webapp import blobstore_handlers

import models
import controllers

import session


class Proximity(webapp.RequestHandler):

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
            geopt = db.GeoPt(*map(float, controllers.unquote(geopt).split(',')))
        except ValueError:
            self.response.set_status(
                400, 'GeoPt is lat,lon. Lat and Lon must be numbers')
            return

        try:
            distance = float(controllers.unquote(distance))
        except ValueError:
            self.response.set_status(
                400, 'distance must be a number in meters')
            return

        max_results = 20
        try:
            max_results = int(controllers.unquote(self.request.get('max_results',
                                                        default_value='20')))
        except ValueError:
            self.response.set_status(400, 'max_results must be integer')
            return
        max_age = 30
        try:
            max_age = float(controllers.unquote(self.request.get('max_age',
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


class CreatePath(blobstore_handlers.BlobstoreUploadHandler):

    def get(self):
        """ Get Blobstore upload URL
            Upload to this URL to actually be able to store a photo.
        """
        current_session = session.get_session()
        if not current_session or not current_session.is_active():
            self.error(401)
            return
        self.response.out.write(
            str(blobstore.create_upload_url('/photos')))


class Create(blobstore_handlers.BlobstoreUploadHandler):

    def post(self):
        """ Store photo
            Since this is a blobstore handler and does not respond RESTfully
            the only HTTP status codes allowed are 301, 302, and 303. Any
            errors are represented by 302 and an ASCII description of the
            error. Proper requests will redirect.
        """
        uploads = self.get_uploads()
        if len(uploads) is not 1:
            self.response.set_status(302, 'Need one file')
            return
        # REMEMBER TO DELETE THE IMAGE FROM BLOBSTORE WHEN RETURNING ERROR
        image = uploads[0]

        current_session = session.get_session()
        if not current_session or not current_session.is_active():
            self.error(401)
            return

        caption = controllers.unquote(self.request.get('caption'))

        try:
            user = current_session['me']
        except KeyError:
            self.error(401)
            image.delete()
            return

        photo = models.Photo(
            user=user,
            img_orig=image.key(),
            caption=caption)
        try:
            photo.put()
        except Exception, e:
            logging.error('Postprocessing failed: %s' % e)
            self.response.set_status(302, 'Postprocessing failed')
            image.delete()
            return
        self.redirect('/photos/%s' % photo.key())


class Resource(webapp.RequestHandler):

    def get(self, key):
        """ JSON serialization of photo identified by :key """
        self.response.headers["Content-Type"] = controllers.MIMETYPE_JSON
        try:
            photo = models.Photo.get(controllers.unquote(key))
        except db.BadKeyError:
            self.error(404)
            return
        self.response.out.write(photo.to_json())

    def put(self, key):
        """ Updates caption of photo identified by :key """
        self.response.headers["Content-Type"] = controllers.MIMETYPE_JSON

        try:
            photo = models.Photo.get(controllers.unquote(key))
        except db.BadKeyError:
            self.error(404)
            return

        try:
            caption = self.request.get('caption', default_value=None)
            if caption is not None:
                photo.caption = controllers.unquote(caption)
            photo.put()
        except apiproxy_errors.CapabilityDisabledError:
            # fail
            pass
        self.redirect('/photos/%s' % key)


class ImageResource(webapp.RequestHandler):

    def get(self, key, os, size):
        """ JPG thumbnail of original image """
        self.response.headers["Content-Type"] = "image/jpeg"
        #self.response.headers.add_header("Expires", "")

        try:
            photo = models.Photo.get(controllers.unquote(key))
        except db.BadKeyError:
            self.error(404)
            return

        self.response.out.write(photo.get_os_img(
            controllers.unquote(os),
            controllers.unquote(size)))
