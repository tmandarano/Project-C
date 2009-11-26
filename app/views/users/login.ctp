<div style="padding-top: 15px; margin: auto;">
<?php echo $session->flash('auth')?>
<?php echo $form->create('User', array('action'=>'login'))?>
<table>
<tr><td>Email</td><td><input type="text" name="data[User][email]" id="UserEmail" /></td></tr>
<tr><td>Password</td><td><input type="password" name="data[User][password]" id="UserPassword" /></td></tr>
</table>
<?php echo $form->end('Sign in') ?>
</div>
