<?php /* Entry point for Project C */
require_once('lib/limonade.php');

function configure() {
    $env = ($_SERVER['HTTP_HOST'] == 'dev.livegather.com') ? ENV_PRODUCTION : ENV_DEVELOPMENT;
    $is_production = $env == ENV_PRODUCTION;
    $dsn = $is_production ?
        'mysql:dbname=livegather;host=db.livegather.com' :
        'mysql:dbname=projectc;host=localhost';
    $dbuser = $is_production ? 'livegather' : 'projectc';
    $dbpass = $is_production ? 'liv3g@th3r' : 'projectc';

    try {
        $db = new PDO($dsn, $dbuser, $dbpass);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    } catch (PDOException $e) {
        header('Status Code: 500 Internal Server Error');
        exit();
    }

    option('env', $env);
    option('dsn', $dsn);
    option('db_conn', $db);
    option('controllers_dir', 'src/controllers');
    option('debug', TRUE);
    option('system_username', 'projc');
    option('system_password', 'pr0j(');
    option('session', 'LiveGather');
    option('UPLOAD_DIR', $_SERVER['DOCUMENT_ROOT'].'/special/uploads/');
    option('IMAGES_DIR', $_SERVER['DOCUMENT_ROOT'].'/images/');
}

dispatch        ('/',                     'home');
dispatch        ('/getapp',               'getapp');
dispatch        ('/download',             'getapp');

dispatch        ('/about/contact',        'about_contact');
dispatch        ('/about/faq',            'about_faq');

dispatch        ('/explore/map',          'explore_map');
dispatch        ('/explore/photos',       'explore_photos');
//dispatch        ('/explore/people',       'explore_people');

dispatch        ('/share/upload',         'share_upload');
dispatch        ('/share/mobile',         'share_mobile');
dispatch        ('/share/webcam',         'share_webcam');

dispatch        ('/profile/:id',          'profile');
dispatch        ('/settings',             'settings');

run();

?>
