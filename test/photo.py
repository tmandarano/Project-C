from __future__ import with_statement
import os
import logging
import StringIO
from unittest import TestCase

from google.appengine.ext import db

import models


class TestPhoto(TestCase):

    def setUp(self):
        asset_path = os.path.join(os.path.dirname(__file__), 'assets')
        names = [
            'realiphone.jpg',
            'realiphone2.jpg',
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
                #photo = models.Photo(img_orig=blob)
                #photo._postprocess()
                #self.photos.append(photo)
        # Blobstore sucks. Can't insert Blobs so can't test! WTH Google.

    def test_initialization(self):
        logging.info(self.image_files)

    def test_exif(self):
        for img in self.blobs:
            logging.info(models._read_EXIF(StringIO.StringIO(img))[0])

    def test_postprocessing(self):
        for img in self.blobs:
            exif = models._read_EXIF(StringIO.StringIO(img))[0]

            print

            print sorted(exif.keys())

            print 'lat', models._iOS_coord_to_decimal(
                exif['GPS GPSLatitude'], exif['GPS GPSLatitudeRef'])
            print 'lon', models._iOS_coord_to_decimal(
                exif['GPS GPSLongitude'], exif['GPS GPSLongitudeRef'])
            print 'dir', models._iOS_coord_to_decimal(
                exif['GPS GPSImgDirection'], exif['GPS GPSImgDirectionRef'])
            print 'alt', models._iOS_coord_to_decimal(
                exif['GPS GPSAltitude'], exif['GPS GPSAltitudeRef'])
            print 'time', models._iOS_coord_to_decimal(
                exif['GPS GPSTimeStamp'])
            print 'info', exif['Image GPSInfo'].values[0]
            print 'horiz', True if exif['Image Orientation'].values else False

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
