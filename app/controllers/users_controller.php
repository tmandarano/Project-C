<?php
class UsersController extends AppController {
  var $name = 'Users';
  var $uses = array('User', 'Photo');
  var $components = array('RequestHandler');
  var $helpers = array('Form', 'Time');

  function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow('login', 'add', 'photo');
  }

  /* Views */
  function index() {
  }

  function profile() {
    $this->pageTitle = $this->Auth->user('name')."'s Profile";
    $this->User->id = $this->Auth->user('id');
    $user = $this->User->find();
    $recentPhotos = $this->Photo->find('all', array('order'=>'DateTime DESC', 'limit'=>20));
    $interests = explode(';', $user['User']['interests']);
    $this->set(compact('recentPhotos', 'interests'));
  }

  function add() {
    $this->pageTitle = "Upload";
    if (!empty($this->data)) {
      $this->User->create();
      $this->User->set($this->data);
      if ($this->User->validates()) {
        $savestatus = $this->User->save();
        if ($savestatus) {
          $this->set('status', 'User ADDED');
        } else {
          $this->set('status', 'User NOT ADDED');
        }
      } else {
        $this->set('status', 'invalid');
      }
    }
  }

  function settings() {
    
  }

  function photo($id) {
    $upload_dir = DS.'webroot'.DS.'img'.DS.'db'.DS.'1'.DS;
    $user = $this->User->find('first', array('conditions'=>array('User.id'=>$id)));
    $userphoto = $user['User']['photo_id'];
    if (is_null($userphoto)) {
      $photo['id'] = 'userunknown';
      $photo['caption'] = 'no_profile_picture';
      $upload_dir = DS.'webroot'.DS.'img'.DS;
    } else {
      $photo = $this->Photo->find('first', array('conditions'=>array('Photo.id'=>$userphoto)));
      $photo = $photo['Photo'];
    }

    /* Send the photo */
    $this->view = 'Media';
    $params = array(
      'id'=>$photo['id'].'.jpg',
      'name'=>$photo['caption'],
      'download'=>false,
      'extension'=>'jpg',
      'path'=>$upload_dir,
      'mimeType'=>'image/jpeg',
      'cache'=>60*60*24*7
    );
    $this->set($params);
  }

  /* Authentication */
  function login() {}
  function logout() {
    $this->Session->setFlash('Signed out');
    $this->redirect($this->Auth->logout());
  }
}
?>
