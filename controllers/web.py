import logging

from google.appengine.ext import webapp


class Index(webapp.RequestHandler):

    def get(self):
        self.response.out.write('Hello')


class API(webapp.RequestHandler):

    def get(self):
        self.response.out.write("""\
<!DOCTYPE html>
<style type="text/css">
  html { height: 100%; }
  body { background: #45a136 url('img/logo/large_no_tagline.png') no-repeat center 40%; }
</style>
""")
