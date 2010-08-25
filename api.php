<?php /* Entry point for Project C */
require_once('lib/limonade.php');
require_once('src/utils/config.php');

/**
 * @internal
 *
 * This function is required because limonade, on entry into 
 * a PHP script it uses as a gateway to routing requires that
 * some basic limonade-specific parameters are setup. So 
 * we call configure here to do that as well as setup our 
 * own global parameters. 
 */
function configure() {
    Config::configure();
}

/**
 * @internal
 *
 * These are REST routes. Limonade asks that you setup 
 * all routes with how they will be requested (see the 
 * dispatch type), what the URL will be and what the 
 * function to call will be.
 *
 * So /users/:id/photos tells us that the url will look 
 * something like http://livegather.com/api/users/34/photos.
 * This will route the web client to photos_get_by_user_id
 * where we will handle the call, using 34 as a parameter.
 *
 * Parameters all being denoted by the :someVar syntax
 */

$PRE = '/api';

dispatch_get   ($PRE.'/users/',                          'users_get');
dispatch_post  ($PRE.'/users/',                          'users_create');
dispatch_get   ($PRE.'/users/status/:status',            'users_get');
dispatch_get   ($PRE.'/users/:id',                       'users_get_by_id');
dispatch_get   ($PRE.'/users/:id/identifiers',           'users_get_by_identifier');
dispatch_post  ($PRE.'/users/:id/identifiers',           'users_add_identifier');
dispatch_get   ($PRE.'/users/:id/photos/',               'photos_get_by_user_id');
dispatch_get   ($PRE.'/users/:id/photos/:offset/:limit', 'photos_get_by_user_id_limited');
dispatch_get   ($PRE.'/users/:id/photos/days/:days',     'photos_get_by_user_id_recent');
dispatch_get   ($PRE.'/users/:id/tags/',                 'tags_get_by_user_id');
dispatch_get   ($PRE.'/users/:id/tags/days/:days',       'tags_get_by_user_id_recent');
dispatch_get   ($PRE.'/users/photo/:id',                 'users_get_photo_by_id');

dispatch_delete($PRE.'/sessions/',                   'sessions_delete');

dispatch_get   ($PRE.'/photos/',                     'photos_get');
dispatch_post  ($PRE.'/photos/',                     'photos_create');
dispatch_put   ($PRE.'/photos/',                     'photos_update');
dispatch_post  ($PRE.'/photos/upload',               'photos_upload');
dispatch_get   ($PRE.'/photos/status/:status',       'photos_get');
dispatch_get   ($PRE.'/photos/recent/:limit',        'photos_recent');
dispatch_get   ($PRE.'/photos/area/:limit/:coords',  'photos_recent_by_area');
dispatch_get   ($PRE.'/photos/circle/:limit/:radius/:coord','photos_recent_by_circle');
dispatch_get   ($PRE.'/photos/:id',                  'photos_get_by_id');
dispatch_get   ($PRE.'/photos/:id/status/:status',   'photos_get_by_id');
dispatch_get   ($PRE.'/photos/:id/user/',            'users_get_by_photo_id');
dispatch_get   ($PRE.'/photos/:id/tags',             'tags_get_by_photo_id');
dispatch_post  ($PRE.'/photos/:id/tags/:tag',        'photos_add_tag');
dispatch_delete($PRE.'/photos/:id/tags/:tag',        'photos_delete_tag');
dispatch       ($PRE.'/photos/:id/:platform/:size',  'photos_image_by_platform');

dispatch_get   ($PRE.'/tags/',                       'tags_get');
dispatch_get   ($PRE.'/tags/:id',                    'tags_get_by_id');
dispatch_get   ($PRE.'/tags/:id/photos',             'photos_get_by_tag_id');
dispatch_get   ($PRE.'/tags/recent/:limit',          'tags_recent');
dispatch_get   ($PRE.'/tags/trending/:limit',        'tags_trending');

run();

?>
