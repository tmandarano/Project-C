<?php
class AppController extends Controller {
  var $components = array('Auth');
  var $helpers = array('Javascript', 'Html', 'Form', 'Time');

  function beforeFilter() {
    Security::setHash('md5');

    $this->Auth->fields = array('username' => 'email', 'password' => 'password');
    $this->Auth->loginAction = array('controller'=>'users', 'action'=>'login');
    /* Redirects users from external links to default logged in page (home page) */
    $this->Auth->loginRedirect = array('controller'=>'home');
    $this->Auth->logoutRedirect = array('controller'=>'home');
  }

  function beforeRender() {
    $user = $this->Auth->user();
    $user = $user['User'];
    $this->set(compact('user'));
  }
}
?>
