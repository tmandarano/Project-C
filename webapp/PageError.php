<?php
require_once('src/utils/restutils.php');
require_once('src/utils/logging.php');

class PageError {
  public static function show($code, $url, $exception) {
    debug($exception);
    debug($url);
    debug($code);
    RestUtils::sendResponse(404, 'HTTP status '.$code);
  }
}
?>
