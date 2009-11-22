<table class="splitpage" cellpadding="0" cellspacing"0">
  <tr>
    <td class="body">
      <table class="stream" cellpadding='0' cellspacing='10'>
       <tr valign="top">
<?php for ($i=0; $i<min(4,count($recentPictures)); $i++) {?>
  <td align='center'>
    <?php echo $this->element('picture', array('picture'=>$recentPictures[$i]))?>
  </td>
<?php }?>
        </tr> 
        <tr> 
<?php for ($i=4; $i<min(8,count($recentPictures)); $i++) {?>
  <td align='center'>
    <?php echo $this->element('picture', array('picture'=>$recentPictures[$i]))?>
  </td>
<?php }?>
        </tr> 
        <tr> 
<?php for ($i=8; $i<min(12,count($recentPictures)); $i++) {?>
  <td align='center'>
    <?php echo $this->element('picture', array('picture'=>$recentPictures[$i]))?>
  </td>
<?php }?>
        </tr> 
      </table> 
    </td>
    <td class='side_menu'>
      <!--Eventually make user block display an element -->
      <?php echo $html->link($user['name'], array('controller'=>'users', 'action'=>'profile', $user['id']), array('class'=>'user')); ?>
      
      <p>is interested in</p>
      <ul>
      <?php foreach ($interests as $interest) {
        echo '<li>'.$interest.'</li>';
      }?>
      </ul>
    </td>
  </tr>
</table>
