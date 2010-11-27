#!/usr/bin/env python


import distutils.core
import unittest
import glob
import os
import sys
import inspect
import shutil


# DIRECTORY is the absolute path to the directory that contains setup.py
DIRECTORY = os.path.abspath(os.path.dirname(os.path.realpath(__file__)))


PACKAGE_PATH, PACKAGE_NAME = os.path.split(DIRECTORY)


class TestCommand(distutils.core.Command):
    """http://da44en.wordpress.com/2002/11/22/using-distutils/"""
    description = "Runs tests"
    user_options = []

    def initialize_options(self):
        sys.path.insert(0, PACKAGE_PATH)
        import dev_appserver
        dev_appserver.fix_sys_path()

    def finalize_options(self):
        pass

    def run(self):
        """Finds all the tests modules in test/ and runs them."""
        testdir = 'test'
        testspath = os.path.join(DIRECTORY, testdir)
        testfiles = []
        verbosity = 2

        globbed = glob.glob(os.path.join(testspath, '*.py'))
        del globbed[globbed.index(os.path.join(testspath, '__init__.py'))]

        for t in globbed:
            testfiles.append(
                '.'.join((PACKAGE_NAME, testdir,
                          os.path.splitext(os.path.basename(t))[0])))

        tests = unittest.TestSuite()
        for t in testfiles:
            __import__(t)
            tests.addTests(
                unittest.defaultTestLoader.loadTestsFromModule(sys.modules[t]))

        unittest.TextTestRunner(verbosity=verbosity).run(tests)
        del sys.path[0]


class CleanCommand(distutils.core.Command):
    description = "Cleans directories of .pyc files and documentation"
    user_options = []

    def initialize_options(self):
        self._clean_me = []
        for root, dirs, files in os.walk('.'):
            for f in files:
                if f.endswith('.pyc'):
                    self._clean_me.append(os.path.join(root, f))

    def finalize_options(self):
        print "Clean."

    def run(self):
        for clean_me in self._clean_me:
            try:
                os.unlink(clean_me)
            except:
                pass


if __name__ == "__main__":
    distutils.core.setup(name=PACKAGE_NAME,
         version='1',
         description="LG",
         long_description="",
         cmdclass = {
             'test': TestCommand,
             'clean': CleanCommand,
         }
    )
