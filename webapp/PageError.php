<?php
require_once('src/utils/logging.php');

class PageError
{
    public static function show($code, $url, $exception)
    {
    	debug($exception);
        debug($url);
        debug($code);
    }
}
?>