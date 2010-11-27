import logging

from google.appengine.ext import webapp


class Index(webapp.RequestHandler):

    def get(self):
        self.response.out.write('Hello')


class API(webapp.RequestHandler):

    def get(self):
        self.response.out.write('LG API')
