<?php
class HomeController extends AppController {
  var $name = 'Home';
  var $uses = 'Photo';

  function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow('index');
  }

  function index() {
    $this->pageTitle='Home';
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

    /* Show streams if user is signed in */
    if ($this->Auth->user()) {
      $this->set('pageClass', 'home stream');
      $this->render('/home/signedin');
    } else {
      $this->set('pageClass', 'home out');
      $this->render('/home/signedout');
    }
  }
}
?>
