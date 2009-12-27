<?php
class ExploreController extends AppController {
  var $name = 'Explore';
  var $uses = null;

  function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow('index', 'map', 'photos', 'people');
  }

  function index() {
    $this->pageTitle='Explore ';
    $this->set('pageClass', 'explore index');
  }
  function map() {
    $this->pageTitle='Map | Explore ';
    $this->set('pageClass', 'explore map');
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
