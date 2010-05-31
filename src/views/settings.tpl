<div id="settings">
<div class="expandable basic">
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

<div class="expandable auths">
  <h1>External Connections</h1>
  <div class="content">
    <table class="auths">
     <tr>
       <td><img src="/img/signup/connect_google.png" /></td>
       <td><img src="http://wiki.developers.facebook.com/images/f/f5/Connect_white_large_long.gif" /></td>
     </tr>
    </table>
    <img src="/img/button_save_changes.png" />
  </div>
</div>

<div class="expandable privacy">
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
</div>

{capture name=scripts}
<script type="text/javascript" src="/js/settings.js"></script>
{/capture}
