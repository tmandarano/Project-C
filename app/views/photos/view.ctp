<div class="photos view">
<table class="split">
<tr>
  <td class="left pane">
    <div>
      <?php echo $this->element('user', array('user'=>$photo['User']))?> in <a href="#" class="location">Los Angeles, CA</a> <a href="#" class="time"><?php echo $time->timeAgoInWords($photo['Photo']['datetime'], array('end'=>'+1month'))?></a>
      <p class="caption"><?php echo $photo['Photo']['caption']?></p>
      <div class="clearer"></div>
    </div>
    <div class="the_image">
      <div class="left"><a href="#"><p>previous</p><img class="s1" src='/img/mini_pic.jpg' /></a></div>
      <img id="the_image" class="s3" src="/photos/<?php echo $id; ?>" />
      <div class="right"><a href="#"><p>next</p><img class="s1" src='/img/mini_pic.jpg' /></a></div>
    </div>
    <div class="comments">
      <div class="actionbox">
        <div class="tags"><strong>tags:</strong> <a href="#">driving</a>, <a href="#">road</a>, <a href="#">trip</a>, <a href="#">radar detector</a> <a href="#">+</a></div>
        <p><a href="#">Share to Facebook</a></p>
        <p><a href="#">Share to Twitter</a></p>
      </div>
      <div class="commentblock">
        <?php echo $this->element('user', array('user'=>$photo['User']))?> <div class="time">35 seconds ago</div>
        <div class="comment">Give me a call when you get to school ok? Have fun on the drive down and be safe!</div>
        <div class="clearer"></div>
      </div>
      <div class="commentblock">
        <?php echo $this->element('user', array('user'=>$photo['User']))?> <div class="time">2 minutes ago</div>
        <div class="comment">have fun! good luck at school</div>
        <div class="clearer"></div>
      </div>
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
        <?php for ($i=4; $i<min(10, count($related)); $i++) { $result = $related[$i];?>
          <td><?php echo $this->element('collagepic', array('photo'=>$result))?></td>
        <?php } ?>
        </tr>
      </table>
      <div class="bubble">Similar photos<div class="right">10</div></div>
      <table class="collage">
        <tr>
        <?php for ($i=0; $i<min(5, count($related)); $i++) { $result = $related[$i];?>
          <td><?php echo $this->element('collagepic', array('photo'=>$result))?></td>
        <?php } ?>
        </tr>
        <tr>
        <?php for ($i=4; $i<min(10, count($related)); $i++) { $result = $related[$i];?>
          <td><?php echo $this->element('collagepic', array('photo'=>$result))?></td>
        <?php } ?>
        </tr>
      </table>
      <div class="bubble"><div class="bubble_center">This photo is...</div><div class="right">&nbsp;</div></div>
      <?php echo $this->element('emotion')?>
      <div class="bubble"><div class="bubble_center">Location</div><div class="right">&nbsp;</div></div>
      <p><a href="#">Los Angeles, CA</a></p>
      <div id="map_location"></div>
    </div>
  </td>
</tr>
</table>
</div>
<?php $javascript->link('photos_view', false); ?>
