<div class="stream social">
<table class="split">
<tr>
    <td class="left pane"> 
      <ul class='explore_options'> 
        <li>stream:</li> 
        <li><a href='/stream'>Photo</a></li>
        <li class="selected">
          <img src="/img/option_button_left.png"><div>Social</div><img src="/img/option_button_right.png"></li> 
        <li class="second">display:</li> 
        <li class="selected">
          <img src="/img/option_button_left.png"><div>All</div><img src="/img/option_button_right.png"></li> 
        <li><a href='#'>Friends</a></li> 
        <li><a href='#'>Family</a></li> 
        <li><a href='#'>Coworkers</a></li> 
      </ul> 
      <table class="stream"> 
        <tr valign="top"> 
        <?php for ($i=0; $i<min(4,count($recentPhotos)); $i++) {?>
          <td><?php echo $this->element('photo', array('photo'=>$recentPhotos[$i]))?></td>
        <?php }?>
        </tr> 
        <tr> 
        <?php for ($i=4; $i<min(8,count($recentPhotos)); $i++) {?>
          <td><?php echo $this->element('photo', array('photo'=>$recentPhotos[$i]))?></td>
        <?php }?>
        </tr> 
        <tr> 
        <?php for ($i=8; $i<min(12,count($recentPhotos)); $i++) {?>
          <td><?php echo $this->element('photo', array('photo'=>$recentPhotos[$i]))?></td>
        <?php }?>
        </tr> 
      </table> 
    </td> 
    <td class="right pane"> 
        <div class='usrname'>tmandarano</div> 
        <div class='profile_preview'><img class='mini_avatar' src='/img/avatar.jpg'>Just hanging out at the beach...</div> 
        <div class='profile_update_time'>6 hours ago</div> 

        <div class="bubble"> 
          <div class="bubble_center">Watchers</div> 
          <div class="right" align="right">0</div> 
        </div> 
        <div class="bubble"> 
          <div class="bubble_center">Watching</div> 
          <div class="right">0</div> 
        </div> 
        <div class="bubble"> 
          <div class="bubble_center">Friends</div> 
          <div class="right">0</div> 
        </div> 
        
        <div class="hrule"></div> 
        
        <div class="bubble">Most Interesting<div class="right">hr day wk</div></div> 
        <table class="collage"> 
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
        
        <div class="bubble">People <div class="right"><img class="sm_icon" src="/img/sm_icon.png"/></div></div>
        <table class="collage"> 
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
        
        <div class="bubble">Photos <div class="right"><img class="sm_icon" src="/img/sm_icon.png"/></div></div>
        <table class="collage"> 
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
