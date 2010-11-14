import cgi

from google.appengine.ext import webapp
from google.appengine.ext.webapp.util import run_wsgi_app
from google.appengine.ext import db


class HomePage(webapp.RequestHandler):
    def get(self):
        self.response.out.write('<html><body>Hello . world</body></html>')


class PhotoResource(webapp.RequestHandler):
    def get(self):
        self.response.out.write('Not implemented yet.')


routes = [
    ('/', HomePage),
    ('/photos', PhotoResource),
#    ('/a', A),
]


class Photo(db.Model):
    location = db.StringProperty()
    caption = db.StringProperty()
    date = db.DateTimeProperty(auto_now_add=True)


application = webapp.WSGIApplication(routes, debug=True)


def main():
    run_wsgi_app(application)


if __name__ == "__main__":
    main()
