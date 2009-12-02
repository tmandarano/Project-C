<?php
class HomeController extends AppController {
  var $name = 'Home';
  var $uses = 'Photo';

  function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow('index');
  }

  function index() {
    $recentPhotos = $this->Photo->find('all',
      array('order'=>'Photo.datetime DESC', 'limit'=>16));
    for ($i=0; $i<count($recentPhotos); $i++) {
      $photo = $recentPhotos[$i]['Photo']['location'];
      if (empty($photo[0])) {
        $recentPhotos[$i]['Photo']['location'][0] = '-181';
      }
      if (empty($photo[1])) {
        $recentPhotos[$i]['Photo']['location'][1] = '-91';
      }
    }
    $this->set(compact('recentPhotos'));
  }
}
?>
