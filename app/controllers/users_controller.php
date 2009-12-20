<?php
class UsersController extends AppController {
  var $name = 'Users';

  function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow('login', 'add', 'photo', 'profile');
  }

  function add() {
    $this->pageTitle = "Sign up";
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
    $this->set('pageClass', 'users add');
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
      $photo = $this->User->Photo->find('first', array('conditions'=>array('Photo.id'=>$userphoto)));
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

  function profile($id=null) {
    if (is_null($id)) {
      $id = $this->Auth->user('id');
    }
    if (is_null($id)) {
      $this->redirect('/');
    } else {
      $userobj = $this->User->find('first', array('conditions'=>array('User.id'=>$id)));
      $userobj = $userobj['User'];
      $this->pageTitle = $userobj['name']."'s Profile";
      $recentPhotos = $this->User->Photo->find('all', array('order'=>'DateTime DESC',
        'conditions'=>array('User.id'=>$userobj['id']), 'limit'=>20));
      $interests = explode(';', $userobj['interests']);
      $this->set(compact('userobj', 'recentPhotos', 'interests'));
    }
    $this->set('pageClass', 'users profile');
  }

  function settings() {
    $this->pageTitle='Settings';
    $this->set('pageClass', 'users settings');
  }

  /* Authentication */
  function login() {
    $this->set('pageClass', 'users login');
  }
  function logout() {
    $this->Session->setFlash('Signed out');
    $this->redirect($this->Auth->logout());
  }
}
?>
