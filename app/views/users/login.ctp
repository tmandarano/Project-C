<div style="padding-top: 15px; margin: auto;">
<?php echo $session->flash('auth')?>
<?php echo $form->create('User', array('action'=>'login'))?>
<table>
<tr><td><?php echo $form->input('email')?></td></tr>
<tr><td><?php echo $form->input('password')?></td></tr>
</table>
<?php echo $form->end('Sign in') ?>
</div>
