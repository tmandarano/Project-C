""" Application routing """
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
    # GET /photos/:key/:os/:size
    ('/photos/([^/]+)/([^/]+)/([^/]+).*', controllers.photo.ImageResource),
    # PUT /photos/:key/(up|down)
    ('/photos/([^/]+)/(up|down).*', controllers.photo.Thumb),
    # GET /photos/:key
    # PUT /photos/:key
    ('/photos/([^/]+).*', controllers.photo.Resource),
    # POST /photos
    ('/photos.*', controllers.photo.Create),

    # GET /users/:key/photos
    ('/users/([^/]+)/photos.*', controllers.user.Photos),
    # GET /users/:key
    ('/users/([^/]+).*', controllers.user.Resource),
    # GET /users
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
