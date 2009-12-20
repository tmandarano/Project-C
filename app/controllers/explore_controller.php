<?php
class ExploreController extends AppController {
  var $name = 'Explore';
  var $uses = null;

  function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow('index', 'photos', 'people');
  }

  function index() {
    $this->pageTitle='Explore';
  }
  function photos() {
    $this->pageTitle='Photos | Explore ';
  }
  function people() {
    $this->pageTitle='People | Explore ';
  }
}
?>
