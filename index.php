<?php /* Entry point for Project C */
require_once('lib/limonade.php');
require_once('src/utils/config.php');
require_once('src/utils/template.php');

function configure() {
    Config::configure();
}

dispatch        ('/',                     'eye');
//dispatch        ('/',                     'home');
//dispatch        ('/getapp',               'getapp');
//dispatch        ('/download',             'getapp');

//dispatch        ('/about/contact',        'about_contact');
//dispatch        ('/about/faq',            'about_faq');

//dispatch        ('/explore/map',          'explore_map');
//dispatch        ('/explore/photos',       'explore_photos');

dispatch        ('/share/upload',         'share_upload');
//dispatch        ('/share/mobile',         'share_mobile');
//dispatch        ('/share/webcam',         'share_webcam');

dispatch        ('/profile/:id',          'profile');
dispatch        ('/settings',             'settings');
dispatch        ('/faq',                  'faq');
dispatch        ('/privacy',              'privacy');
dispatch        ('/tos',                  'tos');
dispatch        ('/contact',              'contact');
dispatch        ('/team',                 'team');

//dispatch        ('/photos/view/:id',      'photos_view_by_id');

function not_found($errno, $errstr, $errfile=null, $errline=null) {
    $template = new Template();
    $template->assign(array('title'=>'404', 'class'=>'http404'));
    return html($template->fetch('http404.tpl'));
}

run();

?>
