<?php /* Entry point for Project C */

require_once('lib/limonade.php');

function configure() {
    $env = $_SERVER['HTTP_HOST'] == 'dev.livegather.com' ? ENV_PRODUCTION : ENV_DEVELOPMENT;
    $dsn = $env == ENV_PRODUCTION ? 'mysql:dbname=livegather;host=db.livegather.com' : 'mysql:dbname=projectc;host=localhost';
    $dbpass = $env == ENV_PRODUCTION ? 'livegather' : 'projectc';
    $dbuser = $env == ENV_PRODUCTION ? 'liv3g@th3r' : 'projectc';
    $db = new PDO($dsn, $dbuser, $dbpass);
    $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
    option('env', $env);
    option('dsn', $dsn);
    option('db_conn', $db);
    option('controllers_dir', 'localhost/controllers');
    option('debug', TRUE);
    option('session', 'LiveGather');
    option('UPLOAD_DIR', $_SERVER['DOCUMENT_ROOT'].'/special/uploads/');
    option('IMAGES_DIR', $_SERVER['DOCUMENT_ROOT'].'/images/');
}


dispatch        ('/',          'home');
dispatch_get    ('/users/',    'users_get');
dispatch_get    ('/users/:id', 'users_get_by_id');
dispatch_post   ('/users/',    'users_create');
dispatch_post   ('/sessions/', 'sessions_create');
dispatch_get    ('/photos/',   'photos_get');
dispatch_get    ('/photos/:id','photos_get_by_id');
dispatch_post   ('/photos/',   'photos_create');

run();

?>
