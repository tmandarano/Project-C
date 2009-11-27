<div class="pictures_view">
<table>
<tr>
  <td class="left pane">
    <div>
    <?php echo $this->element('user', array('user'=>$picture['User']))?> is in <a href="#" class="location">Los Angeles, CA</a> as of <a href="#" class="time"><?php echo $time->timeAgoInWords($picture['Picture']['datetime'], array('end'=>'+1month'))?></a>
    <p class="caption"><?php echo $picture['Picture']['caption']?></p>
    <div class="clearer"></div>
    </div>
    <img id="the_image" src="/pictures/<?php echo $id; ?>" />
    <div class="tags"><strong>tags:</strong> <a href="#">driving</a>, <a href="#">road</a>, <a href="#">trip</a>, <a href="#">radar detector</a> <a href="#">+</a></div>
    <div class="comments">
      <div class="actionbox">
        <p><a href="#">Share to Facebook</a></p>
        <p><a href="#">Share to Twitter</a></p>
      </div>
      <div class="commentblock">
        <?php echo $this->element('user', array('user'=>$picture['User']))?> <div class="time">35 seconds ago</div>
        <div class="comment">Give me a call when you get to school ok? Have fun on the drive down and be safe!</div>
        <div class="clearer"></div>
      </div>
      <div class="commentblock">
        <?php echo $this->element('user', array('user'=>$picture['User']))?> <div class="time">2 minutes ago</div>
        <div class="comment">have fun! good luck at school</div>
        <div class="clearer"></div>
      </div>
    </div>
  </td>
  <td class="right pane">
    <div class="nav">
      <a href="#"><p>previous</p><img src='/img/mini_pic.jpg' /></a>
      <a href="#"><p>next</p><img src='/img/mini_pic.jpg' /></a>
    </div>
    <div class="similar">
      <div class="bubble"><div class="bubble_center">Similar photos nearby</div><div class="right">&nbsp;</div></div>
      <?php foreach ($related as $result) {
        echo $this->element('thumbnail', array('picture'=>$result));
      } ?>
      <div class="bubble"><div class="bubble_center">Similar photos</div><div class="right">&nbsp;</div></div>
      <?php foreach ($related as $result) {
        echo $this->element('thumbnail', array('picture'=>$result));
      } ?>
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
