<?php
if (isset($status)) {
  echo $status;
} else {
  echo '<h1>Sign up now!</h1>';
}?>
<?php echo $form->create('User', array('action'=>'add'))?>
<table>
  <tr>
    <td><label for="user">Username</label></td>
    <td><?php echo $form->text('name')?></td>
  </tr>
  <tr>
    <td><label for="Email">Email</label></td>
    <td><?php echo $form->text('email')?></td>
  </tr>
  <tr>
    <td><label for="Password">Password</label></td>
    <td><?php echo $form->password('password')?></td>
  </tr>
</table>
<?php echo $form->end('Add')?>
