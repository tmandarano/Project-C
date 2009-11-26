<div class="pictures_view">
<table>
<tr>
<td>
<img src="/img/mini_pic.jpg" /><a href="/users/profile/<?php echo $picture['User']['name']?>">@<?php echo $picture['User']['name'] ?></a>
  <p><?php echo $picture['Picture']['caption']?></p>
  <p class="location">Los Angeles, CA</p>
  <p class="time">30 minutes ago</p>
  <img id="the_image" src="/pictures/<?php echo $id; ?>" />
  <p><a href="#">View comments</a>
</td>
<td style="padding-left: 1em">
  <p><a href="#">Share to Facebook</a></p>
  <p><a href="#">Share to Twitter</a></p>
  <div class="similar">
    <h1>Similar pictures nearby</h1>
    <?php foreach ($related as $result) {
      echo $this->element('thumbnail', array('picture'=>$result));
    } ?>
  </div>
  <div id="map_location"></div>
</td>
</tr>
</table>
</div>
