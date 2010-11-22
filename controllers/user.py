import urllib

from google.appengine.ext import webapp
from google.appengine.ext import db

import models
import controllers

import session


class Photos(webapp.RequestHandler):

    def get(self, key):
        self.response.headers["Content-Type"] = controllers.MIMETYPE_JSON
        try:
            user = models.User.get(controllers.unquote(key))
        except db.BadKeyError:
            self.error(404)
            return

        photos = models.Photo.all().filter('user =', user)

        photo_json = ', '.join(map(lambda x: x.to_json(), photos))

        self.response.out.write('[%s]' % photo_json)


class Resource(webapp.RequestHandler):

    def get(self, key):
        self.response.headers["Content-Type"] = controllers.MIMETYPE_JSON
        try:
            user = models.User.get(controllers.unquote(key))
        except db.BadKeyError:
            self.error(404)
            return

        self.response.out.write(user.to_json())


class User(webapp.RequestHandler):

    def get(self):
        current_session = session.get_session()
        if not current_session or not current_session.is_active():
            self.error(404)
            return
        user = current_session.get('me', None)
        if user is not None:
            self.response.out.write(user.to_json())
        else:
            self.error(404)
