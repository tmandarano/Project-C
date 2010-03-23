<?php
require_once('baseController.php');

/* Viscous represents the semi-dynamic, semi-static nature of these pages. */
class ViscousController extends baseController {       
  public function home() {
    if ($user or true) {
      $this->assign('title', '');
      $this->assign('class', 'home in');
      RestUtils::sendResponse(200, $this->fetch('signedin.tpl'));
    } else {
      $this->assign('title', '');
      $this->assign('class', 'home out');
      RestUtils::sendResponse(200, $this->fetch('home.tpl'));
    }
  }
      
  public function about_contact() {
    $this->assign('title', 'Contact | About');
    $this->assign('class', 'about contact');
    RestUtils::sendResponse(200, $this->fetch('about_contact.tpl'));
  }
      
  public function about_faq() {
    $this->assign('title', 'FAQ | About');
    $this->assign('class', 'about faq');
    RestUtils::sendResponse(200, $this->fetch('about_faq.tpl'));
  }
}
?>
