<table class="split">
<tr>
  <td class="left pane">
    <div class="users">
      <a href="/profile/{$user.id}"><img src="/users/photo/{$user.name}" /></a>
      <a class="username" href="/profile/{$user.id}">{$user.name}</a>
      <span class="time">{$photo.datetime}</span>
      <span class="location">{$photo.location}</span>
      <p class="caption">{$photo.caption}</p>
    </div>
    <div class="the_image">
      <img id="the_image" class="s3" src="/photos/{$photo.id}/3" />
    </div>
    <div class="comments">
      {if count($photo.comment) <= 0}
        <p>No comments yet. Be the first to add one!</p>
      {/if}
      {if !$session.user}
        <p>Please <a href="#" class="sign in">Sign in</a> to add comments.</p>
      {/if}
      <ul class="users comments">
        {if $session.user}
        <li>
        <a href="/profile/{$user.id}"><img src="/users/photo/{$user.id}" /></a> <a class="username" href="/profile/{$user.id}">{$user.name}</a>
          <form class="comments" name="comments" action="/photos/comment"
          method="post"><input type="hidden" name="photo_id"
          value="{$photo.id}" /><input type="text" name="comment" class="default" value="add comment" /><input type="submit" value="add" /></form>
        </li>
        {/if}
        {foreach from=$photo.comment item=comment}
          <li>
            <a href="/profile/{$comment.user.id}"><img src="/users/photo/{$comment.user.id}" /></a>
            <a class="username" href="/profile/{$comment.user.id}">{$comment.user.name}</a>
            <span class="time">{$comment.datetime}</span>
            <p>{$comment.comment}</p>
          </li>
        {/foreach}
      </ul>
    </div>
  </td>
  <td class="right pane">
    <div class="similar">
      <h1 class="bubble">Similar photos nearby</h1>
      <ul class="collage">
        {foreach from=$related item=photo}
          <li><a href="/photos/view/{$photo.id}"><img src="/photos/{$photo.id}/0" title="{$photo.caption}" /></a></li>
        {/foreach}
      </ul>
      <h1 class="bubble">Similar photos</h1>
      <ul class="collage">
        {foreach from=$similarPhotos item=photo}
          <li><a href="/photos/view/{$photo.id}"><img src="/photos/{$photo.id}/0" title="{$photo.caption}" /></a></li>
        {/foreach}
      </ul>
      <h1 class="bubble">Share</h1>
        <p><a href="#">Share to Facebook</a></p>
        <p><a href="#">Share to Twitter</a></p>
      <h1 class="bubble">Tags</h1>
      <ul class="tags">
        <li><a href="#">driving</a></li>
        <li><a href="#">road</a></li>
        <li><a href="#">greenery</a></li>
        <li><a href="#">trip</a></li>
        <li><a href="#">radar</a></li>
        <li><a href="#">detector</a></li>
        <li><a href="#">+</a></li>
      </ul>
      <h1 class="bubble">This photo is...</h1>
      {include file=_emotion.tpl}
      <h1 class="bubble">Location</h1>
      <p class="location">{$photo.location}</p>
      <div id="map_location"></div>
    </div>
  </td>
</tr>
</table>
{capture name=scripts}
<script type="text/javascript" src="/js/photos_view.js"></script>
{/capture}
