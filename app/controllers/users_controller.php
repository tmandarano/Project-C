<?php
class UsersController extends AppController {
  var $name = 'Users';
  var $uses = array('User', 'Picture');
  var $components = array('RequestHandler');
  var $helpers = array('Form', 'Time');

  function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow('login', 'add');
  }

  /* Views */
  function index() {
  }

  function profile() {
    $this->pageTitle = $this->Auth->user('name')."'s Profile";
    $this->User->id = $this->Auth->user('id');
    $user = $this->User->find();
    $recentPictures = $this->Picture->find('all', array('order'=>'DateTime DESC', 'limit'=>20));
    $interests = explode(';', $user['User']['interests']);
    $this->set(compact('recentPictures', 'interests'));
  }

  function add() {
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

  /* Authentication */
  function login() {
  }
  function logout() {
    $this->Session->setFlash('Logout');
    $this->redirect($this->Auth->logout());
  }
}
?>
