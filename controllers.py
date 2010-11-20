from google.appengine.ext import db
from google.appengine.ext import webapp
from google.appengine.runtime import apiproxy_errors

import models
import vendors.python2_6.json as json


MIMETYPE_JSON = 'application/json'


class PhotoCreate(webapp.RequestHandler):

    def get(self, *args):
        # XXX ADMIN
        self.response.headers["Content-Type"] = MIMETYPE_JSON
        all_photos = ', '.join(map(lambda x: x.to_json(), models.Photo.all()))
        self.response.out.write('[%s]' % all_photos)

    def post(self, *args):
        self.response.headers["Content-Type"] = MIMETYPE_JSON
        self.response.out.write('"%s\n\n%s"' % (self.__dict__, args))


class PhotoResource(webapp.RequestHandler):

    def get(self, *args):
        id = long(args[0])

        self.response.headers["Content-Type"] = MIMETYPE_JSON
        self.response.out.write(models.Photo.get_by_id(id).to_json())

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
            self.error(400)
            return

        try:
            photo = models.Photo.get_by_id(id)
        except:
            self.error(400)
            return

        self.response.out.write(photo.get_os_img(os, size))


class Admin(webapp.RequestHandler):

    def get(self, *args):
        model, command = args

        self.response.out.write(model)
        self.response.out.write(command)
        self.response.out.write('asdf')
