import urllib

from google.appengine.ext import webapp
from google.appengine.ext import db

import models
import controllers

import session


class Photos(webapp.RequestHandler):

    def get(self, key):
        """ JSON serializations of photos the user referenced by :key took """
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
        """ JSON serialization of the user referenced by :key """
        self.response.headers["Content-Type"] = controllers.MIMETYPE_JSON
        try:
            user = models.User.get(controllers.unquote(key))
        except db.BadKeyError:
            self.error(404)
            return

        self.response.out.write(user.to_json())


class User(webapp.RequestHandler):

    def get(self):
        """ JSON serialization of the current logged in user """
        self.response.headers["Content-Type"] = controllers.MIMETYPE_JSON
        current_session = session.get_session()
        import logging
        logging.warn(str(current_session))
        if not current_session or not current_session.is_active():
            self.response.set_status(404, 'No user logged in')
            return
        user = current_session.get('me', None)
        if user is not None:
            self.response.out.write(user.to_json())
        else:
            self.error(404)
