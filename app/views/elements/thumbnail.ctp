<div class='photo s1'>
  <a href="/photos/view/<?php echo $photo['Photo']['id']?>">
    <?php echo $html->image('/photos/'.$photo['Photo']['id'], array('title'=>$photo['Photo']['caption']))?>
  </a>
</div>
