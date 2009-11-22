<!DOCTYPE html>
<html> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<?php echo $html->css('stream_style')?>
<title><?php echo $title_for_layout?> | LiveGather</title> 
</head> 
<body> 
<?php
  function active_on_match($self, $a) {
    if ($a == $self->params['controller']) {
      return array('class'=>'active');
    } else {
      return array();
    }
  }
?>
<div id='header'> 
  <!-- Note: we might end up with a lot of menu items...which won't fit in 1000px -->
  <ul class='main_buttons'> 
<?php function markSelected($name, $env) {
  if ($name == $env) {
    echo ' class="selected"';
  }
}?>
    <li class='logo'><?php echo $html->link('LiveGather', array('controller'=>'home'), array('escape'=>false))?></li> 
    <?php if ($user) { ?>
      <li<?php markSelected('Stream', $title_for_layout)?>><?php echo $html->link($user['name']."'s Stream", array('controller'=>'stream'))?></li> 
      <li<?php markSelected('Profile', $title_for_layout)?>><?php echo $html->link($user['name'], array('controller'=>'users', 'action'=>'profile', $user['id']))?></li> 
      <!--li<?php markSelected('Achievements', $title_for_layout)?>><?php echo $html->link('Achievements', '#')?></li-->
      <li<?php markSelected('Add', $title_for_layout)?>><?php echo $html->link("Upload", array('controller'=>'pictures', 'action'=>'add'))?></li>
    <? } ?>
    <li<?php markSelected('Explore', $title_for_layout)?>><?php echo $html->link("Explore", array('controller'=>'explore'))?></li> 
    <li>
      <?php /*Sign in/out*/
      if ($user) {
        echo $html->link("Sign out", array('controller'=>'users', 'action'=>'logout'));
      } else {
        echo $html->link("Sign in", array('controller'=>'users', 'action'=>'login'));
      } ?>
    </li>
  </ul> 
</div> 
<div id="content">
<div class="textleft">
  <?php echo $content_for_layout?>
</div>
</div>
<div id="foot_bar"></div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<?php echo $scripts_for_layout; ?>
</body> 
</html> 
