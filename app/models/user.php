<?php
class User extends AppModel {
  var $name = 'User';
  var $useTable = 'users';

  var $hasMany = array(
    'Picture' => array(
      'className'  => 'Picture'
    ),
    'Comment' => array(
      'className' => 'Comment'
    )
  );
}
?>
