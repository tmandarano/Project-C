from google.appengine.ext import db
from google.appengine.ext import webapp
from google.appengine.runtime.apiproxy_errors import CapabilityDisabledError

import models


MIMETYPE_JSON = 'application/json'


class PhotoResource(webapp.RequestHandler):

    def get(self, *args):
        self.response.headers["Content-Type"] = MIMETYPE_JSON
        response = ''

        photo = models.Photo()
        photo.put()

        for photo in models.Photo.all():
            response += str(photo)
        response += "\n"
        response += str(self.request._environ['PATH_INFO'])
        response += "\n"
        response += str(args)
        response += '"'
        self.response.out.write(response)

    def post(self, *args):
        self.response.headers["Content-Type"] = MIMETYPE_JSON
        self.response.out.write('"%s\n\n%s"' % (self.__dict__, args))

    def put(self, *args):
        self.response.headers["Content-Type"] = MIMETYPE_JSON
        try:
            # try to save
            pass
        except CapabilityDisabledError:
            # fail
            pass
        self.response.out.write('"%s\n\n%s"' % (self.__dict__, args))


class PhotoImageResource(webapp.RequestHandler):

    def get(self, *args):
        self.response.headers["Content-Type"] = "image/jpeg"
        photo_id, path = args
        try:
            photo = models.Photo.get_by_id(photo_id)
        except:
            self.error(400)
            return
        os, size = path.split('/')
        self.response.out.write(photo.get_os_img(os, size))
