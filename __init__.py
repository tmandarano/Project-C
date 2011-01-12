""" Application routing """
import cgi
import os

from google.appengine.ext import webapp
from google.appengine.ext.webapp.util import run_wsgi_app

import controllers
import controllers.photo
import controllers.user
import controllers.trending
import controllers.session
import controllers.web


debug = True


subdomains = {
    'api': webapp.WSGIApplication([
    # Photo resource
        # GET /photos/proximity/:geopt/:radius
        ('/photos/proximity/([^/]+)/([^/]+).*', controllers.photo.Proximity),
        # GET /photos/recent/:limit
        ('/photos/recent/([^/]+).*', controllers.photo.Recent),
        # GET /photos/upload
        ('/photos/upload.*', controllers.photo.CreatePath),
        # GET /photos/:key/:os/:size
        ('/photos/([^/]+)/([^/]+)/([^/]+).*', controllers.photo.ImageResource),
        # POST /photos/:key/(up|down)
        ('/photos/([^/]+)/(up|down).*', controllers.photo.Thumb),
        # DELETE /photos/:key/tags/:tag_key
        # GET /photos/:key/tags
        # POST /photos/:key/tags
        ('/photos/([^/]+)/tags/([^/]+).*', controllers.photo.Tag),
        ('/photos/([^/]+)/tags.*', controllers.photo.Tags),
        # DELETE /photos/:key/comments/:comment_key
        # GET /photos/:key/comments
        # POST /photos/:key/comments
        ('/photos/([^/]+)/comments/([^/]+).*', controllers.photo.Comment),
        ('/photos/([^/]+)/comments.*', controllers.photo.Comments),
        # GET /photos/:key/user
        ('/photos/([^/]+)/user.*', controllers.photo.User),
        # GET /photos/:key
        # PUT /photos/:key
        ('/photos/([^/]+).*', controllers.photo.Resource),
        # POST /photos
        ('/photos.*', controllers.photo.Create),

    # User resource
        # GET /users/:key/photos
        ('/users/([^/]+)/photos.*', controllers.user.Photos),
        # GET /users/:key
        ('/users/([^/]+).*', controllers.user.Resource),
        # GET /users
        ('/users.*', controllers.user.User),

        # DELETE /session
        ('/sessions', controllers.session.Resource),
        # POST /rpx (called by Janrain)
        ('/rpx.*', controllers.session.RPXTokenHandler),

    # Trending resource
        # GET /trending/tags
        ('/trending/tags', controllers.trending.Tags),

        ('.*', controllers.web.API),
    ], debug=debug),
    'www': webapp.WSGIApplication([
        ('.*', controllers.web.Index),
    ], debug=debug),
}


def main():
    ## TESTING HACK:
    #os.environ['HTTP_HOST'] = 'api.testing.' + os.environ['HTTP_HOST']

    #domains = os.environ['HTTP_HOST'].split('.')
    #if type(domains) is str or len(domains) < 3:
    #    subdomain = 'www'
    #else:
    #    subdomain = domains[0]
    #run_wsgi_app(subdomains[subdomain])
    run_wsgi_app(subdomains['api'])


if __name__ == "__main__":
    main()
