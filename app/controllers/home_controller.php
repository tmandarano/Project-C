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
      array('order'=>'Picture.datetime DESC', 'limit'=>16));
    for ($i=0; $i<count($recentPictures); $i++) {
      $recentPictures[$i]['Picture']['lat'] = 34;
      $recentPictures[$i]['Picture']['lng'] = -116;
    }
    $this->set(compact('recentPictures'));
  }
}
?>
