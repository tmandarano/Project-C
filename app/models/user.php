<?php
class User extends AppModel {
  var $name = 'User';
  var $useTable = 'users';

  var $hasMany = array(
    'Photo' => array(
      'className'  => 'Photo'
    ),
    'Comment' => array(
      'className' => 'Comment'
    )
  );
}
?>
