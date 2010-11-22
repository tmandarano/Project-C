import logging
import urllib2

from google.appengine.ext import db
from google.appengine.ext import webapp
from google.appengine.runtime import apiproxy_errors
from google.appengine.ext import blobstore
from google.appengine.ext.webapp import blobstore_handlers

import lg
import models


MIMETYPE_JSON = 'application/json'


class PhotoProximity(webapp.RequestHandler):

    def get(self, geopt, radius):
        pass


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

        caption = self.request.get('caption')
        geopt = self.request.get('geopt')

        try:
            geopt = db.GeoPt(*map(float, (geopt.split(','))))
        except:
            self.response.set_status(302, 'GeoPt must be lat,lon')
            image.delete()
            return

        photo = models.Photo(img_orig=image.key(), caption=caption, geopt=geopt)
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
            photo = models.Photo.get(key)
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

    def get(self, *args):
        self.response.headers["Content-Type"] = "image/jpeg"
        #self.response.headers.add_header("Expires", "")

        id, os, size = args

        try:
            id = long(id)
        except ValueError:
            self.response.set_status(400, 'Illegal ID')
            return

        try:
            photo = models.Photo.get_by_id(id)
        except:
            self.error(404)
            return

        self.response.out.write(photo.get_os_img(os, size))


class Admin(webapp.RequestHandler):

    def get(self, *args):
        model, command = args

        self.response.out.write(model)
        self.response.out.write(command)
        self.response.out.write('asdf')
