<div class="explore stream">
  <h1><?php echo $h?> <div class="tools"><?php echo $this->element('timelocup')?><?php echo $this->element('emotion')?></div></h1>
  <ol>
    <?php for ($i=0; $i<min(8,count($photos)); $i++) { $photo = $photos[$i]['Photo']?>
    <li><a href="#" onclick="viewpic('<?php echo $photo['id']?>')"><img src="/photos/<?php echo $photo['id']?>/1" /></a></li>
    <?php }?>
  </ol>
</div>
