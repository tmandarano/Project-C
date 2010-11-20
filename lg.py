import cgi
import logging

from google.appengine.ext import webapp
from google.appengine.ext.webapp.util import run_wsgi_app

import controllers


# XXX DEBUG
logging.getLogger().setLevel(logging.DEBUG)


routes = [
# Photo resource
    # POST /photos
    ('/photos/?', controllers.PhotoCreate),
    # GET /photos/:id
    # PUT /photos/:id
    ('/photos/(\d+)/?', controllers.PhotoResource),
    # GET /photos/:id/:os/:size
    ('/photos/(\d+)/(\w*)/(\w*)/?', controllers.PhotoImageResource),

# XXX Admin
    ('/a/(.*)/(.*)', controllers.Admin),
]


application = webapp.WSGIApplication(routes, debug=True)


def main():
    run_wsgi_app(application)


if __name__ == "__main__":
    main()
