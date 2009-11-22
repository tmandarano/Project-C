<div class="medium picture">
  <div class='caption'>
    <div class='comments'>
      <a href="#">0 <?php echo $html->image('comments.png')?></a>
    </div>
    <div class='time'><?php echo $time->timeAgoInWords($picture['Picture']['datetime'], array('end'=>'+1month'))?></div>
  </div>
  <div class='image'>
  <?php echo $html->image('/pictures/'.$picture['Picture']['id'], array('title'=>$picture['Picture']['caption'], 'height'=>'148px', 'width'=>'164px'))?>
    <div class="hover">
      <?php echo $picture['Picture']['caption']?>
    </div>
  </div>
  <div class="name"><?php echo $picture['User']['name']?></div>
</div>
