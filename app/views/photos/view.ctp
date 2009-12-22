<table class="split">
<tr>
  <td class="left pane">
    <div>
      <?php echo $this->element('user', array('user'=>$photo['User']))?> <a href="#" class="location">Los Angeles, CA</a> <a href="#" class="time"><?php echo $time->timeAgoInWords($photo['Photo']['datetime'], array('end'=>'+1month'))?></a>
      <p class="caption"><?php echo $photo['Photo']['caption']?></p>
      <div class="clearer"></div>
    </div>
    <div class="the_image">
      <!--div class="left"><a href="#"><p>previous</p><img class="s1" src='/img/mini_pic.jpg' /></a></div-->
      <img id="the_image" class="s3" src="/photos/<?php echo $id; ?>" />
      <!--div class="right"><a href="#"><p>next</p><img class="s1" src='/img/mini_pic.jpg' /></a></div-->
    </div>
    <div class="tags">
      <h1>tags</h1>
      <ul>
        <li><a href="#">driving</a></li>
        <li><a href="#">road</a></li>
        <li><a href="#">trip</a></li>
        <li><a href="#">radar</a></li>
        <li><a href="#">detector</a></li>
        <li><a href="#">+</a></li>
      </ul>
    </div>
    <div class="comments">
        <p><a href="#">Share to Facebook</a></p>
        <p><a href="#">Share to Twitter</a></p>
      <ul class="users comments">
        <li>
          <img src="/users/photo/11" /> <a class="username" href="/users/profile/11">Etaoin Shrdlu</a> <span class="time">35 seconds ago</span>
          <p>Give me a call when you get to school ok? Have fun on the drive down and be safe!</p>
        </li>
        <li>
          <img src="/users/photo/11" /> <a class="username" href="/users/profile/11">Etaoin Shrdlu</a> <span class="time">2 minutes ago</span>
          <p>have fun! good luck at school</p>
        </li>
        <li>
          <img src="/users/photo/11" /> <a class="username" href="/users/profile/11">Etaoin Shrdlu</a>
          <form><input type="text" name="comment" /><input type="submit" value="Comment" /></form>
        </li>
      </ul>
    </div>
  </td>
  <td class="right pane">
    <div class="similar">
      <div class="bubble">Similar photos nearby</div>
      <table class="collage">
        <tr>
        <?php for ($i=0; $i<min(5, count($related)); $i++) { $result = $related[$i];?>
          <td><?php echo $this->element('collagepic', array('photo'=>$result))?></td>
        <?php } ?>
        </tr>
        <tr>
        <?php for ($i=4; $i<min(9, count($related)); $i++) { $result = $related[$i];?>
          <td><?php echo $this->element('collagepic', array('photo'=>$result))?></td>
        <?php } ?>
        </tr>
      </table>
      <div class="bubble">Similar photos</div>
      <table class="collage">
        <tr>
        <?php for ($i=0; $i<min(5, count($related)); $i++) { $result = $related[$i];?>
          <td><?php echo $this->element('collagepic', array('photo'=>$result))?></td>
        <?php } ?>
        </tr>
        <tr>
        <?php for ($i=4; $i<min(9, count($related)); $i++) { $result = $related[$i];?>
          <td><?php echo $this->element('collagepic', array('photo'=>$result))?></td>
        <?php } ?>
        </tr>
      </table>
      <div class="bubble">This photo is...</div>
      <?php echo $this->element('emotion')?>
      <div class="bubble">Location</div>
      <p><a class="location" href="#">Los Angeles, CA</a></p>
      <div id="map_location"></div>
    </div>
  </td>
</tr>
</table>
<?php $javascript->link('photos_view', false); ?>
