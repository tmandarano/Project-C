<?php /* global settings for PHP when in Project C */

// Enable Project C logging
require_once($_SERVER['DOCUMENT_ROOT'].'/localhost/utils/logging.php');

// Set timezone so apache doesn't complain about relying on server time.
date_default_timezone_set('America/Los_Angeles');

// Set the include path so routing and templating libraries can be included.
set_include_path(get_include_path() .
  PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . '/localhost/config/'.
  PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . '/localhost/utils/'.
  PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . '/libraries/robap-php-router/'.
  PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . '/libraries/smarty/libs/'
);

// See robap-php-router for routing information
// See smarty for templating information

include_once('page_error.php'); 
include_once('php-router.php');

$dev = false;
//$dev = true;

// Database connection information
if($dev)
{
    define('PDO_DSN', 'mysql:dbname=projectc;host=localhost');
    define('PDO_USER', 'projectc');
    define('PDO_PASSWORD', 'projectc');
}
else
{    
    define('PDO_DSN', 'mysql:dbname=livegather;host=db.livegather.com');
    define('PDO_USER', 'livegather');
    define('PDO_PASSWORD', 'liv3g@th3r');
}


// Upload directories
define('UPLOAD_DIR', $_SERVER['DOCUMENT_ROOT'].'/special/uploads/');
define('IMAGES_DIR', $_SERVER['DOCUMENT_ROOT'].'/images/');
?>
