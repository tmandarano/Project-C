<div class="users profile">
<table class="split">
  <tr>
    <td class="left pane">
      <table>
        <tr>
        <?php $bigPhoto = $recentPhotos[0]['Photo'];?>
        <td><img src="/photos/<?php echo $bigPhoto['id']?>" style="width: 216px;" /></td>
          <td>
          <p style="color: #55aa11; font-size: large"><?php echo $bigPhoto['caption'] ?></p><?php echo $time->timeAgoInWords($bigPhoto['datetime'], array('end'=>'+1month'))?>
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
      <?php echo $this->element('user', array('user'=>$userobj))?>
      <p>Name <?php echo $userobj['name']?></p>
      <p>Location <?php echo $userobj['location']?></p>
      <div class="bubble">About <?php echo $userobj['name']?> <div class="right"><a href="#">Edit</a></div></div> 
      <ul>
      <li><strong>bio:</strong> go chargers!</li>
      <li><strong>occupation:</strong> student</li>
      </ul>
      <div class="bubble">Interests <div class="right"><a href="#">Edit</a></div></div> 
      <ul>
      <?php foreach ($interests as $interest) {
        echo '<a href="#">'.$interest.'</a>, ';
      }?>
      </ul>
      <div class="bubble">Similar people <div class="right"><img class="sm_icon" src="/img/sm_icon.png"/></div></div> 
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
</div>
