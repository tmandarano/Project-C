<?php
class Photo extends AppModel {
  var $name = 'Photo';
  var $useTable = 'photos';

  var $belongsTo = 'User';
  var $hasMany = 'Comment';

  var $validate = array(
    'user_id' => array(
    'rule' => 'numeric',
    'required' => true,
    'message' => 'Invalid user.')
  );
}
?>
