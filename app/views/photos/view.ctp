<table class="split">
<tr>
  <td class="left pane">
    <div class="users">
      <a href="/users/profile/<?php echo $photo['User']['id']?>"><img src="/users/photo/<?php echo $photo['User']['name']?>" /></a>
      <a class="username" href="/users/profile/<?php echo $photo['User']['id']?>"><?php echo $photo['User']['name']?></a>
      <span class="time"><?php echo $time->timeAgoInWords($photo['Photo']['datetime'], array('end'=>'+1month'))?></span>
      <span class="location"><?php echo $photo['Photo']['location'] ?></span>
      <p class="caption"><?php echo $photo['Photo']['caption']?></p>
    </div>
    <div class="the_image">
      <img id="the_image" class="s3" src="/photos/<?php echo $id; ?>" />
    </div>
    <div class="comments">
      <?php if (count($photo['Comment']) <= 0) {?>
        <p>No comments yet. Be the first to add one!</p>
      <?php }?>
      <?php if (!$user) {?>
          <p>Please <a href="#" class="sign in">Sign in</a> to add comments.</p>
      <?php }?>
      <ul class="users comments">
        <?php if ($user) {?>
        <li>
        <a href="/users/profile/<?php echo $user['id']?>"><img src="/users/photo/<?php echo $user['id']?>" /></a> <a class="username" href="/users/profile/<?php echo $user['id']?>"><?php echo $user['name']?></a>
          <form class="comments" name="comments" action="/photos/comment" method="post"><input type="hidden" name="photo_id" value="<?php echo $photo['Photo']['id']?>" /><input type="text" name="comment" class="default" value="add comment" /><input type="submit" value="add" /></form>
        </li>
        <?php }?>
        <?php foreach ($photo['Comment'] as $comment) {?>
          <li>
            <a href="/users/profile/<?php echo $comment['user_id']?>"><img src="/users/photo/<?php echo $comment['user_id']?>" /></a>
            <a class="username" href="/users/profile/<?php echo $comment['user_id']?>"><?php echo $comment['User']['name']?></a>
            <span class="time"><?php echo $time->timeAgoInWords($comment['datetime'], array('end'=>'+1month'))?></span>
            <p><?php echo $comment['comment']?></p>
          </li>
        <? }?>
      </ul>
    </div>
  </td>
  <td class="right pane">
    <div class="similar">
      <h1 class="bubble">Similar photos nearby</h1>
      <ul class="collage">
        <?php for ($i=0; $i<min(10, count($related)); $i++) { $result = $related[$i]['Photo'];?>
          <li><a href="/photos/view/<?php echo $result['id']?>"><img src="/photos/<?php echo $result['id']?>/0" title="<?php echo $result['caption']?>" /></a></li>
        <?php } ?>
      </ul>
      <h1 class="bubble">Similar photos</h1>
      <ul class="collage">
        <?php for ($i=0; $i<min(10, count($related)); $i++) { $result = $related[$i]['Photo'];?>
          <li><a href="/photos/view/<?php echo $result['id']?>"><img src="/photos/<?php echo $result['id']?>/0" title="<?php echo $result['caption']?>" /></a></li>
        <?php } ?>
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
      <?php echo $this->element('emotion')?>
      <h1 class="bubble">Location</h1>
      <p class="location"><?php echo $photo['Photo']['location'] ?></p>
      <div id="map_location"></div>
    </div>
  </td>
</tr>
</table>
<?php $javascript->link('photos_view', false); ?>
