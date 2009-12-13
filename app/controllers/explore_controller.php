<?php
class ExploreController extends AppController {
  var $name = 'Explore';
  var $uses = null;

  function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow('index');
  }

  function index() {
  }
}
?>
