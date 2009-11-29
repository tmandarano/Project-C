<?php
class HomeController extends AppController {
  var $name = 'Home';
  var $uses = 'Photo';
  var $helpers = array('Html', 'Time');

  function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow('index');
  }

  function index() {
    $recentPhotos = $this->Photo->find('all',
      array('order'=>'Photo.datetime DESC', 'limit'=>16));
    for ($i=0; $i<count($recentPhotos); $i++) {
      $recentPhotos[$i]['Photo']['lat'] = 34;
      $recentPhotos[$i]['Photo']['lng'] = -116;
    }
    $this->set(compact('recentPhotos'));
  }
}
?>
