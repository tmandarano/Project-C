<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/localhost/utils/restutils.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/localhost/utils/logging.php');

class PageError {
  public static function show($code, $url, $exception) {
    debug("Exception: " . $exception . "\n" . "URL: " . $url . "\n" . "Code: " . $code);

    RestUtils::sendResponse(400, 'HTTP status '.$code);
  }
}
?>
