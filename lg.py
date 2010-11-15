import cgi
import logging

from google.appengine.ext import webapp
from google.appengine.ext.webapp.util import run_wsgi_app

import controllers

# XXX DEBUG
logging.getLogger().setLevel(logging.DEBUG)


routes = [
    ('/photos(/\d+)?', controllers.PhotoResource),
    ('/photos/([^/]+)/(.*)', controllers.PhotoImageResource),
]


application = webapp.WSGIApplication(routes, debug=True)


def main():
    run_wsgi_app(application)


if __name__ == "__main__":
    main()
