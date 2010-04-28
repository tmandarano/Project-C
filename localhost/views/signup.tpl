{if $status}
  {$status}
{else}
  <h1>Sign up now!</h1>
{/if}
<form action="/users/create" method="post">
<table>
  <tr>
    <td><label for="fullname">Fullname</label></td>
    <td><input id="fullname" name="fullname" type="text"></input></td>
  </tr>
  <tr>
    <td><label for="date_of_birth">Date of Birth</label></td>
    <td><input id="date_of_birth" name="date_of_birth" type="text"></input></td>
  </tr>
  <tr>
    <td><label for="email">Email</label></td>
    <td><input id="email" name="email" type="text"></input></td>
  </tr>
  <tr>
    <td><label for="password">Password</label></td>
    <td><input id="password" name="password" type="password"></input></td>
  </tr>
</table>
<input name="commit" type="submit" value="Add" />
</form>
