{if $status}
  {$status}
{else}
  <h1>Sign up now!</h1>
{/if}
<form action="/users/add" method="post">
<table>
  <tr>
    <td><label for="user">Username</label></td>
    <td><input id="user_name" name="user[name]" type="text"></input></td>
  </tr>
  <tr>
    <td><label for="Email">Email</label></td>
    <td><input id="user_email" name="user[email]" type="text"></input></td>
  </tr>
  <tr>
    <td><label for="Password">Password</label></td>
    <td><input id="user_password" name="user[password]" type="password"></input></td>
  </tr>
</table>
<input name="commit" type="submit" value="Add" />
</form>
