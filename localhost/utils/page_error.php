<?php
require_once('localhost/utils/restutils.php');
require_once('localhost/utils/logging.php');

class PageError {
  public static function show($code, $url, $exception) {
    debug('PAGE ERROR');
    debug($exception);
    debug($url);
    debug($code);
    RestUtils::sendResponse(404, 'HTTP status '.$code);
  }
}
?>
