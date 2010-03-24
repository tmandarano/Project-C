<div class="photo">
  <a href="/photos/view/{$photo.id}"><img src="/photos/{$photo.id}/3" /></a>
  <a class="like" href="">Like</a>
</div>
<div class="detail">
  <p><span class="time">{$photo.datetime}</span> <span
  class="location">{$photo.location}</span></p>
  <p><span class="caption">{$photo.caption}</span></p>
  <p><a href="#">7 people</a> have commented</p>
  <p>comment</p>
  <p>Tags. {foreach from=$photo.tags item=tag}<a href="#">{$tag.tag}</a>, {/foreach}</p>
  <p>add</p>
</div>
