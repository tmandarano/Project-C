<?php
require_once('lib/limonade.php');
require_once('src/utils/logging.php');

/**
 * @internal
 * 
 * The purpose of this function is to 
 * So /users/:id/photos tells us that the url will look 
 * something like http://livegather.com/api/users/34/photos.
 * This will route the web client to photos_get_by_user_id
 * where we will handle the call, using 34 as a parameter.
 *
 * Parameters all being denoted by the :someVar syntax as defined in the api script.
 * This function uses the SERVER variable HTTP_HOST to determine what environment we are running 
 * and thus which database, username, password, etc. to use. 
 */
class Config {
    public static function configure() {
        $env = ($_SERVER['HTTP_HOST'] == 'dev.livegather.com') ? ENV_PRODUCTION : ENV_DEVELOPMENT;
        $is_production = $env == ENV_PRODUCTION;
        $dsn = $is_production ?
            'mysql:dbname=livegather_dev;host=mysql.livegather.com' :
            'mysql:dbname=projectc;host=localhost';
        $dbuser = $is_production ? 'livegather' : 'projectc';
        $dbpass = $is_production ? 'liv3g@th3r' : 'projectc';

        try {
            $db = new PDO($dsn, $dbuser, $dbpass);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        } catch (PDOException $e) {
            debug('DATABASE CONNECTION ERROR.');
            halt(500);
        }

        // Setup the various options limonade will keep in memory through the lifecycle of the application.
        option('env', $env);
        option('dsn', $dsn);
        option('db_conn', $db);
        option('controllers_dir', 'src/controllers');
        option('debug', TRUE);
        option('system_username', 'projc');
        option('system_password', 'pr0j(');
        option('session', 'LiveGather');
        option('UPLOAD_DIR', $_SERVER['DOCUMENT_ROOT'].'/special/uploads/');
        option('PHOTOS_DIR', $_SERVER['DOCUMENT_ROOT'].'/photos/');
        option('rpxApiKey', '6af1713bce4897a0067343c5da898e1dccb6862d');  
    }
}
?>
