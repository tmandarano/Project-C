<h1>You need to sign in before continuing.</h1>
<?php echo $form->create('User', array('action'=>'login'))?>
<table>
<tr><th>Email</th><td><input type="text" name="data[User][email]" id="UserEmail" /></td></tr>
<tr><th>Password</th><td><input type="password" name="data[User][password]" id="UserPassword" /></td></tr>
<tr><th></th><td><?php echo $form->end('Sign in') ?></td></tr>
</table>
</form>
