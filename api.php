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
