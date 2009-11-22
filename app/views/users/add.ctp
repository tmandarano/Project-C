<?php
if (isset($status)) {
  echo $status;
} else {
  echo 'Sign up now!';
}?>
<?php
  echo $form->create('User', array('action'=>'add'));
  echo $form->input('email');
  echo $form->input('password');
  echo $form->end('Add');
?>
