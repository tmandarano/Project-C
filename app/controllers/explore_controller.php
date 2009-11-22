<?php
class ExploreController extends AppController {
  var $name = 'Explore';
  var $uses = null;
  var $helpers = array('Form');

  function beforeFilter() {
    $this->Auth->allow('index');
  }

  function index() {
  }
}
?>
