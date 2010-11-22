import cgi
import logging

from google.appengine.ext import webapp
from google.appengine.ext.webapp.util import run_wsgi_app

import controllers


routes = [
# Photo resource
    # GET /photos/proximity/:geopt/:radius
    ('/photos/proximity/([^/]+)/([^/]+).*', controllers.PhotoProximity),
    # GET /photos/upload
    ('/photos/upload.*', controllers.PhotoCreatePath),
    # GET /photos/:id/:os/:size
    ('/photos/([^/]+)/([^/]+)/([^/]+).*', controllers.PhotoImageResource),
    # GET /photos/:id
    # PUT /photos/:id
    ('/photos/([^/]+).*', controllers.PhotoResource),
    # POST /photos
    ('/photos.*', controllers.PhotoCreate),

# XXX Admin
    ('/a/(.*)/(.*)', controllers.Admin),
]


application = webapp.WSGIApplication(routes, debug=True)


def main():
    run_wsgi_app(application)


if __name__ == "__main__":
    main()
