<?php
require_once('special/lib/limonade.php');

class Config {
    public static function configure() {
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
            halt(500);
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
        option('IMAGES_DIR', $_SERVER['DOCUMENT_ROOT'].'/photos/');
    }
}
?>
