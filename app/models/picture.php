<?php
class Picture extends AppModel {
  var $name = 'Picture';
  var $useTable = 'pictures';

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
