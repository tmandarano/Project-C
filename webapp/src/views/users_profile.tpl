<table class="split">
  <tr>
    <td class="left pane">
      <table class="mostrecent split">
        <tr>
          <td class="left pane"><img src="/photos/{$mainPhoto.id}" /></td>
          <td class="right pane">
            <p><span class="caption">{$mainPhoto.caption}</span> <span class="time">{$mainPhoto.datetime}</span></p>
            <ul class="users comments">
              <li>
                <a href="/users/profile/11"><img src="/users/photo/11" /></a>
                <a href="/users/profile/11" class="username">Etaoin Shrdlu</a>
                <span class="time">35 seconds ago</span>
                <p>Give me a call when you get to school ok? Have fun on the drive down and be safe!</p>
              </li>
              <li>
                <a href="/users/profile/11"><img src="/users/photo/11" /></a>
                <a href="/users/profile/11" class="username">Etaoin Shrdlu</a>
                <span class="time">2 minutes ago</span>
                <p>have fun! good luck at school</p>
              </li>
              <li>
                <a href="/users/profile/11"><img src="/users/photo/11" /></a>
                <a href="/users/profile/11" class="username">Etaoin Shrdlu</a>
              </li>
            </ul>
          </td>
        </tr>
      </table>
      <ol class="detailed stream">
        {foreach from=$recentPhotos item=photo}
          {include file='_detailed_stream.tpl' photo=$photo}
        {/foreach}
      </ol>
    </td>
    <td class='right pane'>
      <div class="users">
        <a href="/users/profile/11"><img src="/users/photo/11" /></a>
        <a href="/users/profile/11" class="username">Etaoin Shrdlu</a>
        <p>{$user.name}</p>
        <p class="location">{$user.location}</p>
      </div>
      <h1 class="bubble">Watchers<div class="right"><a href="#">0</a></div></h1> 
      <h1 class="bubble">Watching<div class="right"><a href="#">0</a></div></h1> 
      <h1 class="bubble">Friends<div class="right"><a href="#">0</a></div></h1> 
      <h1 class="bubble">About {$user.name} <div class="right"><a href="#">edit</a></div></h1> 
      <ul>
        <li><span class="label">Bio</span> go chargers!</li>
        <li><span class="label">Occupation</span> student</li>
      </ul>
      <h1 class="bubble">Interests <div class="right"><a href="#">edit</a></div></h1> 
      <ul>
        {foreach from=$user.interests item=interest}
          <li>{$interest}</li>
        {/foreach}
      </ul>
      <h1 class="bubble">Similar people <div class="right"><img class="sm_icon" src="/img/sm_icon.png"/></div></h1> 
        <ul class="collage">
          {foreach from=$related item=photo}
            <li><a href="/photos/view/{$photo.id}"><img src="/photos/{$photo.id}/0" title="{$photo.caption}" /></a></li>
          {/foreach}
        </ul>
    </td>
  </tr>
</table>
