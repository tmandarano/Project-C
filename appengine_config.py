""" GAESessions configuration """

from lib.gaesessions import SessionMiddleware


def webapp_add_wsgi_middleware(app):
    """ Configure GAE Sessions with cookie key """
    app = SessionMiddleware(app, cookie_key=(
        "xyPafQ9lbjK7ykecEjpD"
        "JcHJopZLHSFQ2BBuBjjG"
        "CDK4HDHSv1zdxYBsSW5W"
        "RyDtY53hYRfQ9ssPpp4i"
        "8iwPeryk1vqc35jDu3Fp"
        "cYeCP4XI7TRFeXEWRDrT"
        "v4cLeQ6gBsBrf0RYkgrs"
        "CPdwGSxFeCxySle2oyeD"
        "3TyROjv0DzMRlro62Smz"
        "stjU8X0AtvyM1ePsDSQ5"))
    return app
