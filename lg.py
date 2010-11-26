import cgi

from google.appengine.ext import webapp
from google.appengine.ext.webapp.util import run_wsgi_app

import controllers
import controllers.photo
import controllers.user
import controllers.session


routes = [
# Photo resource
    # GET /photos/proximity/:geopt/:radius
    ('/photos/proximity/([^/]+)/([^/]+).*', controllers.photo.Proximity),
    # GET /photos/upload
    ('/photos/upload.*', controllers.photo.CreatePath),
    # GET /photos/:id/:os/:size
    ('/photos/([^/]+)/([^/]+)/([^/]+).*', controllers.photo.ImageResource),
    # GET /photos/:id
    # PUT /photos/:id
    ('/photos/([^/]+).*', controllers.photo.Resource),
    # POST /photos
    ('/photos.*', controllers.photo.Create),

    # GET /users
    ('/users/([^/]+)/photos.*', controllers.user.Photos),
    ('/users/([^/]+).*', controllers.user.Resource),
    ('/users.*', controllers.user.User),

    # DELETE /session
    ('/session', controllers.session.Resource),

    # POST /rpx (called by Janrain)
    ('/rpx.*', controllers.session.RPXTokenHandler),
]


application = webapp.WSGIApplication(routes, debug=True)


def main():
    run_wsgi_app(application)


if __name__ == "__main__":
    main()
