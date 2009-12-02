<?php
class ExploreController extends AppController {
  var $name = 'Explore';

  function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow('index');
  }

  function index() {
  }
}
?>
