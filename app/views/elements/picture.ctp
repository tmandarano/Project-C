<div class="photo">
  <div class='info'>
    <div class='numcomments'>
      <a href="#">0 <?php echo $html->image('comments.png')?></a>
    </div>
    <div class='time'><?php echo $time->timeAgoInWords($picture['Picture']['datetime'], array('end'=>'+1month'))?></div>
  </div>
  <a href="/pictures/view/<?php echo $picture['Picture']['id']?>">
  <div class='image s2'>
      <?php echo $html->image('/pictures/'.$picture['Picture']['id'], array('title'=>$picture['Picture']['caption']))?>
    <div class="hover">
      <?php echo $picture['Picture']['caption']?>
    </div>
  </div>
  </a>
  <div class="name"><?php echo $picture['User']['name']?></div>
</div>
