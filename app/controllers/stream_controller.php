<?php
class StreamController extends AppController {
  var $name = 'Stream';
  var $uses = 'Photo';
  var $helpers = array('Form', 'Time');

  function beforeFilter() {
    $this->Auth->allow('index', 'social');
  }

  function index() {
    $recentPhotos = $this->Photo->find('all',
      array('order'=>'Photo.datetime DESC', 'limit'=>20));
    for ($i=0; $i<count($recentPhotos); $i++) {
      $recentPhotos[$i]['Photo']['lat'] = 23;
      $recentPhotos[$i]['Photo']['lng'] = -117;
    }
    $this->set(compact('recentPhotos'));
  }

  function social() {
    $recentPhotos = $this->Photo->find('all',
      array('order'=>'Photo.datetime DESC', 'limit'=>20));
    for ($i=0; $i<count($recentPhotos); $i++) {
      $recentPhotos[$i]['Photo']['lat'] = 23;
      $recentPhotos[$i]['Photo']['lng'] = -117;
    }
    $this->set(compact('recentPhotos'));
  }
}
?>
