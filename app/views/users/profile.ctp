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
                <?php echo $this->element('user', array('user'=>$userobj))?> <span class="time">35 seconds ago</span>
                <p>Give me a call when you get to school ok? Have fun on the drive down and be safe!</p>
              </li>
              <li>
                <?php echo $this->element('user', array('user'=>$userobj))?> <span class="time">2 minutes ago</span>
                <p>have fun! good luck at school</p>
              </li>
              <li>
                <?php echo $this->element('user', array('user'=>$userobj))?>
              </li>
            </ul>
          </td>
        </tr>
      </table>
      <table class="stream" cellpadding='0' cellspacing='10'>
       <tr valign="top">
        <?php for ($i=1; $i<min(5,count($recentPhotos)); $i++) {?>
          <td><?php echo $this->element('photo', array('photo'=>$recentPhotos[$i]))?></td>
        <?php }?>
        </tr> 
        <tr> 
        <?php for ($i=5; $i<min(9,count($recentPhotos)); $i++) {?>
          <td><?php echo $this->element('photo', array('photo'=>$recentPhotos[$i]))?></td>
        <?php }?>
        </tr> 
      </table> 
    </td>
    <td class='right pane'>
      <div class="userinfo">
      <?php echo $this->element('user', array('user'=>$userobj))?>
      <p><?php echo $userobj['name']?></p>
      <p><?php echo $userobj['location']?></p>
      </div>
      <h1 class="bubble">Watchers<div class="right"><a href="#">0</a></div></h1> 
      <h1 class="bubble">Watching<div class="right"><a href="#">0</a></div></h1> 
      <h1 class="bubble">Friends<div class="right"><a href="#">0</a></div></h1> 
      <h1 class="bubble">About <?php echo $userobj['name']?> <div class="right"><a href="#">edit</a></div></h1> 
      <ul>
        <li><strong>bio:</strong> go chargers!</li>
        <li><strong>occupation:</strong> student</li>
      </ul>
      <h1 class="bubble">Interests <div class="right"><a href="#">edit</a></div></h1> 
      <ul>
        <?php $interests = array(); foreach ($interests as $interest) { $interests[] = '<a href="#">'.$interest.'</a>'; } echo join(', ', $interests); ?>
      </ul>
      <h1 class="bubble">Similar people <div class="right"><img class="sm_icon" src="/img/sm_icon.png"/></div></h1> 
        <table class="collage" cellpadding="0" cellspacing="3"> 
          <tr> 
            <td><a href="#"><img src="/img/mini_pic.jpg"></a></td> 
            <td><a href="#"><img src="/img/mini_pic.jpg"></a></td> 
            <td><a href="#"><img src="/img/mini_pic.jpg"></a></td> 
            <td><a href="#"><img src="/img/mini_pic.jpg"></a></td> 
            <td><a href="#"><img src="/img/mini_pic.jpg"></a></td> 
          </tr>
          <tr>
            <td><a href="#"><img src="/img/mini_pic.jpg"></a></td> 
            <td><a href="#"><img src="/img/mini_pic.jpg"></a></td> 
            <td><a href="#"><img src="/img/mini_pic.jpg"></a></td> 
            <td><a href="#"><img src="/img/mini_pic.jpg"></a></td> 
            <td><a href="#"><img src="/img/mini_pic.jpg"></a></td> 
          </tr> 
        </table> 
    </td>
  </tr>
</table>
