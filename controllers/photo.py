import logging
import datetime

from google.appengine.ext import db
from google.appengine.ext import webapp
from google.appengine.runtime import apiproxy_errors
from google.appengine.runtime import DeadlineExceededError
from google.appengine.ext import blobstore
from google.appengine.ext.webapp import blobstore_handlers

from django.utils import simplejson

import models
import controllers

import session


class Proximity(webapp.RequestHandler):

    def get(self, geopt, distance):
        """ Find photos within distance of GeoPt
            Args:
                required for URL to match:
                    geopt - lat,lon
                    distance - in meters
                optional:
                    max_age - results will be restricted to at most this many 
                        minutes in the past (in set float((0, inf)))
                    max_results - at most return this many results (in set
                        int([1, 20]))
            Returns:
                JSON array with max_results photos.
        """
        self.response.headers["Content-Type"] = controllers.MIMETYPE_JSON
        try:
            geopt = db.GeoPt(*map(float, controllers.unquote(geopt).split(',')))
        except ValueError:
            self.response.set_status(
                400, 'GeoPt is lat,lon. Lat and Lon must be numbers')
            return

        try:
            distance = float(controllers.unquote(distance))
        except ValueError:
            self.response.set_status(
                400, 'distance must be a number in meters')
            return

        max_results = 20
        try:
            max_results = int(controllers.unquote(self.request.get('max_results',
                                                        default_value='20')))
        except ValueError:
            self.response.set_status(400, 'max_results must be integer')
            return

        max_age = 30
        try:
            max_age = float(controllers.unquote(self.request.get('max_age',
                                                     default_value='30')))
        except ValueError:
            self.response.set_status(400, 'max_age must be number')
            return

        query = models.Photo.all()

        if max_age:
            max_age = max(max_age, 1e-6)
            query.filter('taken_at >',
                         datetime.datetime.now() - \
                         datetime.timedelta(minutes=max_age))

        photos = models.Photo.proximity_fetch(
            query,
            geopt,
            max_results=min(20, max(max_results, 1)),
            max_distance=distance)

        json_photos = [x.to_json() for x in photos]

        self.response.out.write('[%s]' % ', '.join(json_photos))


class Recent(webapp.RequestHandler):

    def get(self, limit):
        """ List recently uploaded photos
            Args:
                limit - the maximum number of recently uploaded photos
            Returns:
                JSON array of recently uploaded photos
        """
        self.response.headers["Content-Type"] = controllers.MIMETYPE_JSON
        try:
            limit = int(limit)
        except ValueError:
            self.error(403)
            return
        json_photos = models.Photo.all().order('-created_at').fetch(limit)
        json_photos = [x.to_json() for x in json_photos]
        self.response.out.write('[%s]' % ', '.join(json_photos))


class Thumb(webapp.RequestHandler):
    """ Exposes the thumb up and thumb down operations on photos by a user """

    def post(self, key, up):
        """ Change the user's vote on a given photo """
        current_session = session.get_session()
        if not current_session or not current_session.is_active():
            self.error(403)
            return

        try:
            user = current_session['me']
        except KeyError:
            self.error(403)
            return

        try:
            photo = models.Photo.get(controllers.unquote(key))
        except db.BadKeyError:
            self.error(400)
            return

        thumb = photo.thumb_set.filter('user = ', user).get()
        if not thumb:
            thumb = models.Thumb(up=True, photo=photo, user=user)
        logging.info('thumb %s' % up)
        thumb.up = (up == 'up')
        thumb.put()

        self.response.headers["Content-Type"] = controllers.MIMETYPE_JSON
        self.redirect('/photos/%s' % key)


class Comments(webapp.RequestHandler):
    """ Exposes commenting on a photo """

    def get(self, key):
        """ Get comments on a given photo """
        try:
            photo = models.Photo.get(controllers.unquote(key))
        except db.BadKeyError:
            self.error(400)
            return

        json_comments = [x.to_json() for x in photo.comment_set]
        self.response.headers["Content-Type"] = controllers.MIMETYPE_JSON
        self.response.out.write('[%s]' % ', '.join(json_comments))

    def post(self, key):
        """ Add a comment by the current user on a given photo """
        current_session = session.get_session()
        if not current_session or not current_session.is_active():
            self.error(403)
            return

        try:
            user = current_session['me']
        except KeyError:
            self.error(403)
            return

        try:
            photo = models.Photo.get(controllers.unquote(key))
        except db.BadKeyError:
            self.error(400)
            return

        comment = self.request.get('comment')

        logging.error(self.request.body)
        if not comment:
            logging.error('no comment')
            self.error(400)
            return

        c = models.Comment(comment=comment, photo=photo, user=user)
        c.put()

        self.redirect('/photos/%s' % key)


class Comment(webapp.RequestHandler):
    """ Exposes comment management """

    def delete(self, key):
        """ Delete a comment by the current user on a given photo """
        current_session = session.get_session()
        if not current_session or not current_session.is_active():
            self.error(403)
            return

        try:
            user = current_session['me']
        except KeyError:
            self.error(403)
            return

        try:
            photo = models.Photo.get(controllers.unquote(key))
        except db.BadKeyError:
            self.error(400)
            return

        try:
            comment = models.Comment.get(controllers.unquote(comment_key))
        except db.BadKeyError:
            self.error(400)
            return

        if comment.user != user:
            self.error(403)
            return

        if comment in photo.comment_set:
            comment.delete()
        else:
            self.error(404)
            return

        self.redirect('/photos/%s' % key)


class Tags(webapp.RequestHandler):
    """ Exposes tagging """

    def get(self, key):
        """ Get tags on a given photo """
        try:
            photo = models.Photo.get(controllers.unquote(key))
        except db.BadKeyError:
            self.error(400)
            return

        json_tags = [x.to_json() for x in photo.tag_set]
        self.response.headers["Content-Type"] = controllers.MIMETYPE_JSON
        self.response.out.write('[%s]' % ', '.join(json_tags))

    def post(self, key):
        """ Add a tag by the current user on a given photo """
        current_session = session.get_session()
        if not current_session or not current_session.is_active():
            self.error(403)
            return

        try:
            user = current_session['me']
        except KeyError:
            self.error(403)
            return

        try:
            photo = models.Photo.get(controllers.unquote(key))
        except db.BadKeyError:
            self.error(400)
            return

        tag = self.request.get('tag')

        logging.error(self.request.body)
        if not comment:
            logging.error('no tag')
            self.error(400)
            return

        # Prevent double tag
        tag = photo.tag_set.filter('tag = ', tag).filter('user = ', user).get()
        if not tag:
            tag = models.Tag(tag=tag, photo=photo, user=user)
            tag.put()

        self.redirect('/photos/%s' % key)


class Tag(webapp.RequestHandler):
    """ Exposes tag managment """

    def delete(self, key, tag_key):
        """ Delete a tag by the current user on a given photo """
        current_session = session.get_session()
        if not current_session or not current_session.is_active():
            self.error(403)
            return

        try:
            user = current_session['me']
        except KeyError:
            self.error(403)
            return

        try:
            photo = models.Photo.get(controllers.unquote(key))
        except db.BadKeyError:
            self.error(400)
            return

        try:
            tag = models.Tag.get(controllers.unquote(tag_key))
        except db.BadKeyError:
            self.error(400)
            return

        if tag.user != user:
            self.error(403)
            return

        if tag in photo.tag_set:
            tag.delete()
        else:
            self.error(404)
            return

        self.redirect('/photos/%s' % key)


class User(webapp.RequestHandler):

    def get(self, key):
        """ Redirects permanently to the photo's user URN """
        try:
            photo = models.Photo.get(controllers.unquote(key))
        except db.BadKeyError:
            self.error(400)
            return

        self.redirect('/users/%s' % photo.user.key(), permanent=True)


class CreatePath(webapp.RequestHandler):

    def get(self):
        """ Get Blobstore upload URL
            POST to the response URL to actually be able to store a photo.
            Returns:
                JSON string containing upload URL
        """
        current_session = session.get_session()
        if not current_session or not current_session.is_active():
            self.error(403)
            return
        self.response.headers["Content-Type"] = controllers.MIMETYPE_JSON
        self.response.out.write('"%s"' % blobstore.create_upload_url('/photos'))


class Create(blobstore_handlers.BlobstoreUploadHandler):

    def post(self):
        """ Store photo
            Since this is a blobstore handler and does not respond RESTfully
            the only HTTP status codes allowed are 301, 302, and 303. Any
            errors are represented by 303 and an ASCII description of the
            error. Proper requests will redirect with 302 Found.

            Errors:
                Need one file
                Unauthorized
                Postprocessing took too long
                Postprocessing failed
        """
        uploads = self.get_uploads()
        if len(uploads) is not 1:
            self.response.set_status(error_code, 'Need one file')
            return
        image = uploads[0]

        # THE ONLY RIGHT WAY TO EXIT THIS HANDLER IS WITH 302 OR CALLING THIS
        # METHOD AND RETURNING.
        def failed(message, error_code=303):
            """ Encapsulate failure logic. Yay closures. """
            image.delete()
            self.response.set_status(error_code, message)

        current_session = session.get_session()
        if not current_session or not current_session.is_active():
            failed('Unauthorized')
            return

        try:
            user = current_session['me']
        except KeyError:
            failed('Unauthorized')
            return

        caption = controllers.unquote(self.request.get('caption'))

        photo = models.Photo(
            user=user,
            img_orig=image.key(),
            caption=caption)
        try:
            photo.put()
        except DeadlineExceededError:
            logging.error('Postprocessing took too long.')
            failed('Postprocessing took too long.')
            return
        except Exception, e:
            logging.error(repr(e))
            failed('Postprocessing failed')
            return
        self.redirect('/photos/%s' % photo.key())


class Resource(webapp.RequestHandler):

    def get(self, key):
        """ JSON serialization of photo identified by :key """
        try:
            photo = models.Photo.get(controllers.unquote(key))
        except db.BadKeyError:
            self.error(404)
            return
        self.response.headers["Content-Type"] = controllers.MIMETYPE_JSON
        self.response.out.write(photo.to_json())

    def put(self, key):
        """ Updates caption of photo identified by :key """
        try:
            photo = models.Photo.get(controllers.unquote(key))
        except db.BadKeyError:
            self.error(404)
            return

        try:
            caption = self.request.get('caption', default_value=None)
            if caption is not None:
                photo.caption = controllers.unquote(caption)
            photo.put()
        except apiproxy_errors.CapabilityDisabledError:
            # fail
            pass


class ImageResource(webapp.RequestHandler):

    def get(self, key, os, size):
        """ JPG thumbnail of original image """
        #self.response.headers.add_header("Expires", "")

        try:
            photo = models.Photo.get(controllers.unquote(key))
        except db.BadKeyError:
            self.error(404)
            return

        self.response.headers["Content-Type"] = 'image/jpeg'
        self.response.out.write(photo.get_os_img(
            controllers.unquote(os),
            controllers.unquote(size)))
