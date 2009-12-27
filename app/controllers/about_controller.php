<?php
class AboutController extends AppController {
  var $name = 'About';
  var $uses = null;

  function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow('index', 'contact', 'faq');
  }

  function index() {
    $this->pageTitle='About ';
    $this->set('pageClass', 'about index');
  }
  function contact() {
    $this->pageTitle='Contact | About ';
    $this->set('pageClass', 'about contact');
  }
  function faq() {
    $this->pageTitle='FAQ | About ';
    $this->set('pageClass', 'about faq');
  }
}
?>

