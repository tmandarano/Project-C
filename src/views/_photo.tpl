<div class="infoed photo">
  <div class='info'>
    <div class='numcomments'>
      <a href="#">{$photo.comments|@count} <img src='/img/comments.png'/></a>
    </div>
    <div class='time'>{$photo.datetime}</div>
  </div>
  <a href="/photos/view/{$photo.id}">
  <div class='image s2'>
    <img src='/photos/{$photo.id}/2' title='{$photo.caption}' />
    <div class="hover">
      <div class="name">{$photo.user.name}</div>
      {$photo.caption}
    </div>
  </div>
  </a>
</div>
