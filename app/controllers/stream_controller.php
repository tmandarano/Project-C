<?php
class StreamController extends AppController {
  var $name = 'Stream';
  var $uses = 'Photo';

  function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow('index', 'social');
  }

  function index() {
    $recentPhotos = $this->Photo->find('all',
      array('order'=>'Photo.datetime DESC', 'limit'=>20));
    $this->set(compact('recentPhotos'));
  }

  function social() {
    $recentPhotos = $this->Photo->find('all',
      array('order'=>'Photo.datetime DESC', 'limit'=>20));
    $this->set(compact('recentPhotos'));
  }
}
?>
