<?php
class ExploreController extends AppController {
  var $name = 'Explore';
  var $uses = null;

  function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow('index', 'photos', 'people');
  }

  function index() {
    $this->pageTitle='Explore ';
    $this->set('pageClass', 'explore index');
  }
  function photos() {
    $this->pageTitle='Photos | Explore ';
    $this->set('pageClass', 'explore photos');
  }
  function people() {
    $this->pageTitle='People | Explore ';
    $this->set('pageClass', 'explore people');
  }
}
?>
