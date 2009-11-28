<?php
class StreamController extends AppController {
  var $name = 'Stream';
  var $uses = 'Picture';
  var $helpers = array('Form', 'Time');

  function beforeFilter() {
    $this->Auth->allow('index', 'social');
  }

  function index() {
    $recentPictures = $this->Picture->find('all',
      array('order'=>'Picture.datetime DESC', 'limit'=>20));
    for ($i=0; $i<count($recentPictures); $i++) {
      $recentPictures[$i]['Picture']['lat'] = 23;
      $recentPictures[$i]['Picture']['lng'] = -117;
    }
    $this->set(compact('recentPictures'));
  }

  function social() {
    $recentPictures = $this->Picture->find('all',
      array('order'=>'Picture.datetime DESC', 'limit'=>20));
    for ($i=0; $i<count($recentPictures); $i++) {
      $recentPictures[$i]['Picture']['lat'] = 23;
      $recentPictures[$i]['Picture']['lng'] = -117;
    }
    $this->set(compact('recentPictures'));
  }
}
?>
