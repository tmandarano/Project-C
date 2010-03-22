<?php
require_once('baseController.php');

/* Viscous represents the semi-dynamic, semi-static nature of these pages. */
class ViscousController extends baseController {       
  public function home() {
    RestUtils::sendResponse(200, $this->fetch('home.tpl'));
  }
      
  public function settings() {
    RestUtils::sendResponse(200, $this->fetch('settings.tpl'));
  }
}
?>
