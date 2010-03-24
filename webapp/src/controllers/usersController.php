<?php
require_once('baseController.php');

class UsersController extends BaseController {       

  public function show() {
    RestUtils::sendResponse(501);
  }
      
  public function create() {
    RestUtils::sendResponse(501);
  }
  
  public function edit() {
    RestUtils::sendResponse(501);
  }
  
  public function delete() {
    RestUtils::sendResponse(501);
  }

  public function photo() {
    header('Location: /img/50x50.jpg');
    exit;
  }

}
?>
