import urllib

from google.appengine.ext import webapp
from google.appengine.ext import db
from google.appengine.api import urlfetch

from django.utils import simplejson
from lib import gaesessions

import models


get_session = gaesessions.get_current_session


def _session_terminate(session):
    if session.is_active():
        session.terminate()


class Resource(webapp.RequestHandler):

    def delete(self):
        """ Delete session """
        current_session = get_session()
        _session_terminate(current_session)


class RPXTokenHandler(webapp.RequestHandler):
    """ Receive RPX POST with user's login information """

    def post(self):
        """ Receiver for RPX """
        token = self.request.get('token')
        url = 'https://rpxnow.com/api/v2/auth_info'
        args = {
            'format': 'json',
            'apiKey': '6af1713bce4897a0067343c5da898e1dccb6862d',
            'token': token,
        }
        r = urlfetch.fetch(
            url=url,
            payload=urllib.urlencode(args),
            method=urlfetch.POST,
            headers={'Content-Type': 'application/x-www-form-urlencoded'})

        auth = simplejson.loads(r.content)

        session = get_session()
        _session_terminate(session)

        if auth['stat'] == 'ok':
            info = auth['profile']
            oid = info['identifier']
            email = info.get('email', '')
            try:
                display_name = info['displayName']
            except KeyError:
                display_name = email.partition('@')[0]

            try:
                user = models.User.get_or_insert(
                    oid,
                    email=email,
                    display_name=display_name)
            except db.TransactionFailedError:
                # TODO uhoh.
                raise

            session.start()
            session.regenerate_id()
            session['me'] = user
            self.redirect('/users/%s' % user.key())
        else:
            # failed
            self.redirect('/users')

