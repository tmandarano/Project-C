<table class="split">
  <tr>
    <td class="left pane">
      <h1 class="username"><a href="" class="username"></a></h1>
      <a href=""><img src="" class="userphoto" /></a>
{*    <ul class="actions">
        <li><a href="/users/watch/{$user.id}">Watch this person</a></li>
        <li><a href="/users/message/{$user.id}">Send message</a></li>
      </ul>
*}      <h1 class="bichrome"><em>Similar</em> people.</h1> 
        <ul class="collage similar people"></ul>
      <h1 class="bichrome"><em>Tags</em> from my life.</h1> 
      <ul class="tagcloud"></ul>
{*      <h1 class="bichrome"><em>About</em> me.</h1> 
      <dl class="user info">
        <dt>Location</dt><dd><a href="{$user.location}">{$user.location}</a></dd>
        <dt>Occupation</dt><dd><a href="#{$user.occupation}">{$user.occupation}</a></dd>
        <dt>Bio</dt><dd><a href="#{$user.bio}">{$user.bio}</a></dd>
      </dl>
*}    </td>
    <td class='right pane'>
      <ol id="stream" class="profile stream"></ol>
      <p class="loadmore"><a href="javascript:void(0);">Load older photos</a></p>
    </td>
  </tr>
</table>
{capture name=scripts}
<script type="text/javascript">
var _ = LG.profile = defaultTo(LG.profile, {literal}{}{/literal});
_.user = {$profile_user};
_.recentPhotos = {$recentPhotos};
_.similarPeople = {$similarPeople};
_.tags = {$tags};
</script>
<script type="text/javascript" src="/js/profile.js"></script>
<script type="text/javascript" src="/js/jquery.tagcloud.min.js"></script>
{/capture}
