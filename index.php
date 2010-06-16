<?php /* Entry point for Project C */
require_once('lib/limonade.php');
require_once('src/utils/config.php');

function configure() {
    Config::configure();
}

dispatch        ('/',                     'eye');
//dispatch        ('/',                     'home');
//dispatch        ('/getapp',               'getapp');
//dispatch        ('/download',             'getapp');
//
//dispatch        ('/about/contact',        'about_contact');
//dispatch        ('/about/faq',            'about_faq');
//
//dispatch        ('/explore/map',          'explore_map');
//dispatch        ('/explore/photos',       'explore_photos');
//
//dispatch        ('/share/upload',         'share_upload');
//dispatch        ('/share/mobile',         'share_mobile');
//dispatch        ('/share/webcam',         'share_webcam');
//
//dispatch        ('/profile/:id',          'profile');
//dispatch        ('/settings',             'settings');

//dispatch        ('/photos/view/:id',      'photos_view_by_id');

run();

?>
