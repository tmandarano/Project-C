<?php
class HomeController extends AppController {
  var $name = 'Home';
  var $uses = 'Picture';
  var $helpers = array('Html', 'Time');

  function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow('index');
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
}
?>
