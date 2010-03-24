<table class="split">
  <tr>
    <td class="left pane">
      <h1 class="username"><a href="/users/profile/11" class="username">{$user.name}</a></h1>
      <a href="/users/profile/11"><img src="/users/photo/11" class="userphoto" /></a>
      <ul class="actions">
        <li><a href="/users/watch/{$user.id}">Watch this person</a></li>
        <li><a href="/users/message/{$user.id}">Send message</a></li>
      </ul>
      <h1 class="bichrome"><em>Similar</em> people.</h1> 
        <ul class="collage">
          {foreach from=$similarPeople item=person}
            <li><a href="/profile/{$person.id}"><img src="/users/photo/{$person.id}" /></a></li>
          {/foreach}
        </ul>
      <h1 class="bichrome"><em>Tags</em> from my life.</h1> 
      <ul class="tagcloud">
        {foreach from=$tags key=tag item=count}
          <li value="{$count}" title="{$tag}"><a href="{$tag}">{$tag}</a></li>
        {/foreach}
      </ul>
      <h1 class="bichrome"><em>About</em> me.</h1> 
      <dl class="user info">
        <dt>Location</dt><dd><a href="{$user.location}">{$user.location}</a></dd>
        <dt>Occupation</dt><dd><a href="#{$user.occupation}">{$user.occupation}</a></dd>
        <dt>Bio</dt><dd><a href="#{$user.bio}">{$user.bio}</a></dd>
      </dl>
    </td>
    <td class='right pane'>
      <div class="most_recent">
        <p><span class="caption">{$mostRecent.caption}</span>
           <span class="time">{$mostRecent.datetime} from</span>
           <span class="location">{$mostRecent.location}</span></p>
      </div>
      <ol class="profile stream">
        {foreach from=$recentPhotos item=photo_id}
          <li class="photo stub" stub="profile_stream|{$photo_id}"></li>
        {/foreach}
      </ol>
      <p style="text-align: center; font-size: larger;"><a href="#">Load older photos</a></p>
    </td>
  </tr>
</table>
{capture name=scripts}
<script type="text/javascript" src="/js/users_profile.js"></script>
<script type="text/javascript" src="/js/jquery.tagcloud.min.js"></script>
{/capture}
