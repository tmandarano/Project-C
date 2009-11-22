<?php
class Comment extends AppModel {
  var $name = 'Comment';
  var $useTable = 'comments';

  var $belongsTo = array('Picture', 'User');

  var $validate = array(
  );
}
?>
