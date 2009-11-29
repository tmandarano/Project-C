<div class="infoed photo">
  <div class='info'>
    <div class='numcomments'>
      <a href="#">0 <?php echo $html->image('comments.png')?></a>
    </div>
    <div class='time'><?php echo $time->timeAgoInWords($photo['Photo']['datetime'], array('end'=>'+1month'))?></div>
  </div>
  <a href="/photos/view/<?php echo $photo['Photo']['id']?>">
  <div class='image s2'>
      <?php echo $html->image('/photos/'.$photo['Photo']['id'], array('title'=>$photo['Photo']['caption']))?>
    <div class="hover">
      <?php echo $photo['Photo']['caption']?>
    </div>
  </div>
  </a>
  <div class="name"><?php echo $photo['User']['name']?></div>
</div>
