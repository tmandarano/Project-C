<div class="photo">
  <a href="/photos/view/{$photo.id}"><img src="/photos/{$photo.id}/3" /></a>
  <a class="like" href="#like">Like</a>
</div>
<div class="detail">
  <p><span class="time">{$photo.datetime} from</span> <span
  class="location">{$photo.location}</span></p>
  <p><span class="caption">{$photo.caption}</span></p>
  <div class="bottom">
    <p class="comments"><a href="#">7 people</a> have commented</p>
    <form class="comments" name="comments" action="/photos/edit/{$photo.id}" method="post">
      <input name="photo[comment]" type="text" class="default" value="add comment" />
      <input type="submit" value="comment" />
    </form>
    <p>Tags {foreach from=$photo.tags item=tag}<a href="#">{$tag.tag}</a>, {/foreach}</p>
    <form class="tags" name="tags" action="/photos/edit/{$photo.id}" method="post">
      <input name="photo[tag]" type="text" class="default" value="add tag" />
      <input type="submit" value="tag" />
    </form>
  </div>
</div>
