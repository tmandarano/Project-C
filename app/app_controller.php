<?php
class AppController extends Controller {
  var $uses = null;
  var $components = array('Auth', 'RequestHandler');
  var $helpers = array('Javascript', 'Form');

  function beforeFilter() {
    Security::setHash('md5');

    $this->Auth->fields = array(
      'username' => 'email',
      'password' => 'password'
    );
    $this->Auth->loginAction = array(
      'controller'=>'users',
      'action'=>'login'
    );
    /* Redirect users signing in from home page to their stream */
    if ($this->Session->read('Auth.redirect') == '/') {
      $this->Session->write('Auth.redirect', '/users/profile');
    }
    /* Redirects users coming from external links to a default
     * logged in page (the profile page) */
    $this->Auth->loginRedirect = array(
      'controller'=>'users',
      'action'=>'profile'
    );
    $this->Auth->logoutRedirect = array(
      'controller'=>'home'
    );

    $this->RequestHandler->setContent('json', 'application/json');
  }

  function beforeRender() {
    $user = $this->Auth->user();
    $user = $user['User'];
    $this->set(compact('user'));
  }
}
?>
