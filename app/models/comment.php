<?php
class Comment extends AppModel {
  var $name = 'Comment';
  var $useTable = 'comments';

  var $belongsTo = array('Photo', 'User');

  var $validate = array(
  );
}
?>
