<div id="settings">
{*<div class="expandable basic">
  <h1>Basic Information</h1>
  <div class="content">
    <form action="/">
      <div class="info">
        <div class="userphoto">
          <h1>Profile Photo</h1>
          <img src="/img/50x50.jpg" /><br />
          <p><a href="#">Choose a photo</a></p>
        </div>
        <table>
          <tr><th>Display name</th><td><input type="text" /></td>
          <tr><th>       Email</th><td><input type="text" /></td>
          <tr><th>    Birthday</th><td><input class="birthday" type="text" /></td>
          <tr><th>    Location</th><td><input type="text" /></td>
        </table>
      </div>
      <img src="/img/button_save_changes.png" />
    </form>
  </div>
</div>
*}

<div class="expandable auths">
  <h1>Connections</h1>
  <div class="content">
    <p>Signed in but you can't see your photos? Your account has never been
    signed into using this service. Please reauthenticate with a service that
    you have used to sign in before and we will link your photos.</p>
    <iframe
    src="http://livegather.rpxnow.com/openid/embed?token_url=http%3A%2F%2Fdev.livegather.com%2Frpx.php&flags=hide_sign_in_with"
      scrolling="no" frameBorder="no" allowtransparency="true"
      style="width:400px;height:240px"></iframe>
  </div>
</div>

{*<div class="expandable privacy">
  <h1>Privacy Settings</h1>
  <div class="content">
    <table>
      <tr><th>Who can see your profile?</th><td>Everyone</td></tr>
      <tr><th>Who can see your photos?</th><td>Everyone</td></tr>
      <tr><th>Show your exact location?</th><td>Yes</td></tr>
    </table>
    <img src="/img/button_save_changes.png" />
  </div>
</div>
*}
</div>

{capture name=scripts}
<script type="text/javascript" src="/js/settings.js"></script>
<script type="text/javascript">
  RPXNOW.flags = "show_provider_list";
</script>
{/capture}
