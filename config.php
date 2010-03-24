<?php /* global settings for PHP when in Project C */

// Set timezone so apache doesn't complain about relying on server time.
date_default_timezone_set('America/Los_Angeles');

// Set the include path so routing and templating libraries can be included.
set_include_path(get_include_path() .
  PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . '/lib/robap-php-router/'.
  PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . '/lib/smarty/libs/'
);

// Database connection information
define('PDO_DSN', 'mysql:dbname=livegather;host=db.livegather.com');
define('PDO_USER', 'livegather');
define('PDO_PASSWORD', 'liv3g@th3r');
//define('PDO_DSN', 'mysql:dbname=projectc;host=localhost');
//define('PDO_USER', 'projectc');
//define('PDO_PASSWORD', 'projectc');

// Upload directories
define('UPLOAD_DIR', $_SERVER['DOCUMENT_ROOT'].'/uploads/');
?>
