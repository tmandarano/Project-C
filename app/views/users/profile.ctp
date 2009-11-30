<div class="users profile">
<table class="split">
  <tr>
    <td class="body">
      <table class="stream" cellpadding='0' cellspacing='10'>
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
    <td class='right pane'>
      <?php echo $this->element('user', array('user'=>$userobj))?>
      <p>is interested in</p>
      <ul>
      <?php foreach ($interests as $interest) {
        echo '<li>'.$interest.'</li>';
      }?>
      </ul>
    </td>
  </tr>
</table>
</div>
