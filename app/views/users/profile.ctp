<table class="split">
  <tr>
    <td class="left pane">
      <?php $bigPhoto = $recentPhotos[0]['Photo'];?>
      <table class="mostrecent split">
        <tr>
          <td class="left pane"><img src="/photos/<?php echo $bigPhoto['id']?>" /></td>
          <td class="right pane">
            <p><span class="caption"><?php echo $bigPhoto['caption'] ?></span> <span class="time"><?php echo $time->timeAgoInWords($bigPhoto['datetime'], array('end'=>'+1month'))?></span></p>
            <ul class="users comments">
              <li>
                <a href="/users/profile/11"><img src="/users/photo/11" /></a>
                <a href="/users/profile/11" class="username">Etaoin Shrdlu</a>
                <span class="time">35 seconds ago</span>
                <p>Give me a call when you get to school ok? Have fun on the drive down and be safe!</p>
              </li>
              <li>
                <a href="/users/profile/11"><img src="/users/photo/11" /></a>
                <a href="/users/profile/11" class="username">Etaoin Shrdlu</a>
                <span class="time">2 minutes ago</span>
                <p>have fun! good luck at school</p>
              </li>
              <li>
                <a href="/users/profile/11"><img src="/users/photo/11" /></a>
                <a href="/users/profile/11" class="username">Etaoin Shrdlu</a>
              </li>
            </ul>
          </td>
        </tr>
      </table>
      <ol class="detailed stream">
        <?php for ($i=1; $i<min(9,count($recentPhotos)); $i++) {?>
          <?php echo $this->element('detailed_stream', array('photo'=>$recentPhotos[$i]))?>
        <?php }?>
      </ol>
    </td>
    <td class='right pane'>
      <div class="users">
        <a href="/users/profile/11"><img src="/users/photo/11" /></a>
        <a href="/users/profile/11" class="username">Etaoin Shrdlu</a>
        <p><?php echo $userobj['name']?></p>
        <p class="location"><?php echo $userobj['location']?></p>
      </div>
      <h1 class="bubble">Watchers<div class="right"><a href="#">0</a></div></h1> 
      <h1 class="bubble">Watching<div class="right"><a href="#">0</a></div></h1> 
      <h1 class="bubble">Friends<div class="right"><a href="#">0</a></div></h1> 
      <h1 class="bubble">About <?php echo $userobj['name']?> <div class="right"><a href="#">edit</a></div></h1> 
      <ul>
        <li><span class="label">Bio</span> go chargers!</li>
        <li><span class="label">Occupation</span> student</li>
      </ul>
      <h1 class="bubble">Interests <div class="right"><a href="#">edit</a></div></h1> 
      <ul>
        <?php foreach ($userobj['interests'] as $interest) {?>
          <li><?php echo $interest ?></li>
        <?php }?>
      </ul>
      <h1 class="bubble">Similar people <div class="right"><img class="sm_icon" src="/img/sm_icon.png"/></div></h1> 
        <ul class="collage">
          <?php //for ($i=0; $i<min(8, count($related)); $i++) { $result = $related[$i]['Photo'];?>
          <?php for ($i=0; $i<8; $i++) { $result = array('id'=>'', 'caption'=>''); ?>
            <li><a href="/photos/view/<?php echo $result['id']?>"><img src="/photos/<?php echo $result['id']?>/0" title="<?php echo $result['caption']?>" /></a></li>
          <?php } ?>
        </ul>
    </td>
  </tr>
</table>
