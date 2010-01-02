<table class="split">
<tr>
  <td class="left pane">
    <div class="users">
      <a href="/users/profile/11"><img src="/users/photo/11" /></a>
      <a class="username" href="/users/profile/11">Jimmy World</a>
      <span class="time"><?php echo $time->timeAgoInWords($photo['Photo']['datetime'], array('end'=>'+1month'))?></span>
      <span class="location"><?php echo $photo['Photo']['location'] ?></span>
      <p class="caption"><?php echo $photo['Photo']['caption']?></p>
    </div>
    <div class="the_image">
      <img id="the_image" class="s3" src="/photos/<?php echo $id; ?>" />
    </div>
    <div class="comments">
      <ul class="users comments">
        <li>
          <a href="/users/profile/11"><img src="/users/photo/11" /></a> <a class="username" href="/users/profile/11">Etaoin Shrdlu</a>
          <form class="comments" name="comments" action="/comments/add" method="post"><input type="text" class="default" value="add comment" /><input type="submit" value="add" /></form>
        </li>
        <li>
          <a href="/users/profile/11"><img src="/users/photo/11" /></a> <a class="username" href="/users/profile/11">Etaoin Shrdlu</a> <span class="time">35 seconds ago</span>
          <p>Give me a call when you get to school ok? Have fun on the drive down and be safe!</p>
        </li>
        <li>
          <a href="/users/profile/11"><img src="/users/photo/11" /></a> <a class="username" href="/users/profile/11">Etaoin Shrdlu</a> <span class="time">2 minutes ago</span>
          <p>have fun! good luck at school</p>
        </li>
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
