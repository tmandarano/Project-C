import urllib

from google.appengine.ext import webapp
from google.appengine.ext import db

import models


unquote = urllib.unquote


class Photos(webapp.RequestHandler):

    def get(self, key):
        try:
            user = models.User.get(unquote(key))
        except db.BadKeyError:
            self.error(404)
            return

        photos = models.Photo.all().filter('user =', user)

        photo_json = ', '.join(map(lambda x: x.to_json(), photos))

        self.response.out.write('[%s]' % photo_json)


class Resource(webapp.RequestHandler):

    def get(self, key):
        try:
            user = models.User.get(unquote(key))
        except db.BadKeyError:
            self.error(404)
            return

        self.response.out.write(user.to_json())
