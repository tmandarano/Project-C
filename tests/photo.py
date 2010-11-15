from __future__ import with_statement
import os
from unittest import TestCase

from google.appengine.ext import db

import models


class TestPhoto(TestCase):

    def setUp(self):
        asset_path = os.path.join(os.path.dirname(__file__), 'assets')
        names = [
            'Canon_40D_photoshop_import.jpg',
            'Nikon_COOLPIX_P1.jpg',
            'iphone.jpg',
            'iphone_3gs.jpg',
        ]

        self.image_files = []
        self.blobs = []
        self.photos = []
        for name in names:
            im_f = os.path.join(asset_path, name)
            self.image_files.append(im_f)
            with open(im_f) as f:
                data = f.read()
                blob = db.Blob(data)
                self.blobs.append(blob)
                photo = models.Photo()
                photo.store_image_data(blob)
                self.photos.append(photo)

    def test_initialization(self):
        print self.image_files

    def test_exif(self):
        for img in self.blobs:
            print models._read_EXIF(img)

#    def test_sizes(self):
#        print self.photo.__dict__
#        print self.photo.get_iOS_img('l')
#
#    def test_get_user(self):
#        pass
#
#    def test_get_recent(self):
#        pass
#
#    def test_get_recent_in_circle(self):
#        pass
