<?php /* Entry point for Project C */
require_once('lib/limonade.php');
require_once('src/utils/config.php');

function configure() {
    Config::configure();
}

/* REST routes */

$PRE = '/api';

dispatch_get   ($PRE.'/users/',                      'users_get');
dispatch_get   ($PRE.'/users/status/:status',        'users_get');
dispatch_get   ($PRE.'/users/:id',                   'users_get_by_id');
dispatch_get   ($PRE.'/users/:id/photos/',           'photos_get_by_user_id');
dispatch_get   ($PRE.'/users/:id/photos/days/:days', 'photos_get_by_user_id_recent');
dispatch_get   ($PRE.'/users/:id/tags/',             'tags_get_by_user_id');
dispatch_get   ($PRE.'/users/:id/tags/days/:days',   'tags_get_by_user_id_recent');
dispatch_post  ($PRE.'/users/',                      'users_create');
dispatch_get   ($PRE.'/users/photo/:id',             'users_get_photo_by_id');
dispatch_post  ($PRE.'/users/:id/identifiers',       'users_add_identifier');

dispatch_post  ($PRE.'/sessions/janrain',            'sessions_janrain_create');
dispatch_delete($PRE.'/sessions/',                   'sessions_delete');

dispatch_get   ($PRE.'/photos/',                     'photos_get');
dispatch_get   ($PRE.'/photos/status/:status',       'photos_get');
dispatch_get   ($PRE.'/photos/:id',                  'photos_get_by_id');
dispatch_get   ($PRE.'/photos/:id/status/:status',   'photos_get_by_id');
dispatch_get   ($PRE.'/photos/recent/:limit',        'photos_recent');

dispatch_get   ($PRE.'/photos/area/:limit/:coords',  'photos_recent_by_area');
dispatch_post  ($PRE.'/photos/',                     'photos_create');
dispatch_put   ($PRE.'/photos/',                     'photos_update');
dispatch_get   ($PRE.'/photos/:id/user/',            'users_get_by_photo_id');
dispatch_get   ($PRE.'/photos/:id/tags',             'tags_get_by_photo_id');
dispatch_post  ($PRE.'/photos/:id/tags/:tag',        'photos_add_tag');
dispatch_delete($PRE.'/photos/:id/tags/:tag',        'photos_delete_tag');
dispatch_post  ($PRE.'/photos/upload',               'photos_upload');
dispatch       ($PRE.'/photos/:id/:size',            'photo_by_size');

dispatch_get   ($PRE.'/tags/',                       'tags_get');
dispatch_get   ($PRE.'/tags/:id',                    'tags_get_by_id');
dispatch_get   ($PRE.'/tags/:id/photos',             'photos_get_by_tag_id');
dispatch_get   ($PRE.'/tags/recent/:limit',          'tags_recent');
dispatch_get   ($PRE.'/tags/trending/:limit',        'tags_trending');

run();

?>
