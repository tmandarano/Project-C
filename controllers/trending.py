import logging

from google.appengine.ext import webapp

import models
import controllers


class Tags(webapp.RequestHandler):

    def get(self):
        """ Give the top ten trending tags """
        limit = 10
        trending_tags = {}
        ordered_tags = models.Tag.all().order('-created_at')

        for tag in ordered_tags:
            if len(trending_tags.keys()) >= limit:
                break
            try:
                trending_tags[tag.tag] += 1
            except KeyError:
                trending_tags[tag.tag] = 1

        trending_tags = [tag[0] for tag in sorted(trending_tags.items(),
                                                  key=lambda tag: tag[1])]

        self.response.headers["Content-Type"] = controllers.MIMETYPE_JSON
        self.response.out.write('[%s]' % ', '.join(trending_tags))
