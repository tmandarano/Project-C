<?php /* Entry point for Project C */
require_once('special/lib/limonade.php');
require_once('src/utils/config.php');

function configure() {
    Config::configure();
}

/* REST routes */
dispatch_get    ('/api/users/',                           'users_get');
dispatch_get    ('/api/users/:id',                        'users_get_by_id');
dispatch_get    ('/api/users/:id/photos/',                'photos_get_by_user_id');
dispatch_get    ('/api/users/:id/photos/days/:days',      'photos_get_by_user_id_recent');
dispatch_post   ('/api/users/',                           'users_create');
dispatch_get    ('/api/users/photo/:id',                  'users_get_photo_by_id');
dispatch_post   ('/api/sessions/',                        'sessions_create');
dispatch_delete ('/api/sessions/',                        'sessions_delete');
dispatch        ('/api/signout',                          'sessions_delete');
dispatch_get    ('/api/photos/',                          'photos_get');
dispatch_get    ('/api/photos/:id',                       'photos_get_by_id');
dispatch_get    ('/api/photos/recent/:limit',             'photos_recent');
dispatch_get    ('/api/photos/:id/user/',                 'users_get_by_photo_id');
dispatch_post   ('/api/photos/',                          'photos_create');
dispatch        ('/api/photo/:id/:size',                  'photo_by_size');

run();

?>
