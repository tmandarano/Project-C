<table class="split">
<tr>
  <td class="left pane">
    <div class="info">
      <div class="users">
        <a href="/profile/{$user.id}"><img src="/users/photo/{$user.name}" /></a>
        <a class="username" href="/profile/{$user.id}">{$user.name}</a>
        is in 
        <span class="location">{$photo.location}</span>
        as of
        <span class="time">{$photo.datetime}</span>
        <p class="caption">{$photo.caption}</p>
      </div>
      <div class="prevnext">
        <a href="/api/photos/{$prevPhotoId}">
          <img src="/img/prevnext/prev.png" />
          <img class="thumb" src="/photo/{$prevPhotoId}/2" />
        </a>
        <a href="/api/photos/{$nextPhotoId}">
          <img class="thumb" src="/photo/{$nextPhotoId}/2" />
          <img src="/img/prevnext/next.png" />
        </a>
      </div>
    </div>
    <div class="the_image">
      <img id="the_image" class="s3" src="/photo/{$photo.id}/3" />
    </div>
    <div class="tags">
      <h1>tags:</h1>
      <ul class="tags">
        {foreach from=$photo.tags key=tag item=count}
        <li value="{$count}" title="{$tag}"><a href="{$tag}">{$tag}</a></li>
        {/foreach}
        <li><a href="#">+</a></li>
      </ul>
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
      <h1 class="bichrome"><em>Recent</em> photos nearby.</h1>
      <ul class="collage">
        {foreach from=$nearbyPhotos item=photo}
          <li><a href="/api/photos/{$photo.id}"><img src="/photo/{$photo.id}/0" title="{$photo.caption}" /></a></li>
        {/foreach}
      </ul>
      <h1 class="bichrome"><em>Similar</em> photos.</h1>
      <ul class="collage">
        {foreach from=$similarPhotos item=photo}
          <li><a href="/api/photos/{$photo.id}"><img src="/photo/{$photo.id}/0" title="{$photo.caption}" /></a></li>
        {/foreach}
      </ul>
      <h1 class="bichrome"><em>Location</em>.</h1>
      <p class="location">{$photo.location}</p>
      <div id="map_location"></div>
    </div>
  </td>
</tr>
</table>
{capture name=scripts}
<script type="text/javascript" src="/js/photos_view.js"></script>
{/capture}
