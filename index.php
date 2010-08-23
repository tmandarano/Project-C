<?php /* Entry point for Project C */
require_once('lib/limonade.php');
require_once('src/utils/config.php');
require_once('src/utils/template.php');
require_once('src/dao/user_dao.php');
require_once('src/utils/helpers.php');

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

function initialize() {
    check_username();
}

// Error handlers first
function not_found($errno, $errstr, $errfile=null, $errline=null) {
    set('errno', $errno);
    set('errstr', $errstr);
    set('errfile', $errfile);
    set('errline', $errline);

    $template = new Template();
    $template->assign(array('title'=>'404', 'class'=>'http404'));
    return html($template->fetch('http404.tpl'));
}

function check_username() {
    $possible_username = $_SERVER['REQUEST_URI'];
    $possible_username = substr($possible_username, 1);

    $user = UserDao::get_user_by_username($possible_username);

    if ($user) {
        // Change the request into a GET for the appropriate profile/user_id
        // so that the run() will dispatch it correctly.
        $_GET = array('uri' => '/profile/'.$user->get_id());
        env(null);
    }
}

/**
 * @internal
 *
 * These are web URL dispatch paths. Limonade asks that you setup 
 * all routes with how they will be requested (see the 
 * dispatch type), what the URL will be and what the 
 * function to call will be.
 */

// Now dispatch paths
dispatch        ('/',                     'eye');
dispatch        ('/beta',                 'beta');
//dispatch        ('/',                     'home');
dispatch_post   ('/signin_janrain',               'signin_janrain');
dispatch        ('/signout',              'signout');
dispatch        ('/getapp',               'getapp');
dispatch        ('/download',             'getapp');

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
   
run();

?>
