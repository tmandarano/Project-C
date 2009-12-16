<!DOCTYPE html>
<html> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<?php echo $html->css('screen')?>
<title><?php echo $title_for_layout?> | LiveGather</title> 
</head> 
<body> 
<?php function markSelected($name, $env) {
  if (strpos($env, $name) !== false) {
    echo ' class="selected"';
  }
}?>
<div id='header'> 
  <ul class='main_buttons'> 
    <li class='logo'><?php echo $html->link('LiveGather', array('controller'=>'home'), array('escape'=>false))?></li> 
    <?php if ($user) { ?>
      <li<?php markSelected('Stream', $title_for_layout)?>><?php echo $html->link($user['name']."'s Stream", array('controller'=>'stream'))?></li> 
      <li<?php markSelected('Profile', $title_for_layout)?>><?php echo $html->link($user['name'], array('controller'=>'users', 'action'=>'profile', $user['id']))?></li> 
      <li<?php markSelected('Upload', $title_for_layout)?>><?php echo $html->link("Upload", array('controller'=>'photos', 'action'=>'add'))?></li>
    <? } ?>
    <li<?php markSelected('Photos', $title_for_layout)?>><?php echo $html->link("Photos", array('controller'=>'explore'))?></li> 
    <li<?php markSelected('People', $title_for_layout)?>><?php echo $html->link("People", array('controller'=>'explore'))?></li> 
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
<div id="centering">
<div id="content">
  <?php echo $content_for_layout?>
</div>
</div>
<div id="foot_bar">
<table><tr>
<td>
  <h1><a href="/share">Share</a></h1>
  <ul>
  <li><a href="/share/mobile">Mobile</a></li>
  <li><a href="/share/webcam">Webcam</a></li>
  </ul>
</td>
<td>
  <h1><a href="/explore">Explore</a></h1>
  <ul>
  <li><a href="/explore/photos">Map</a></li>
  <li><a href="/explore/people">People</a></li>
  <li><a href="/explore/photos">Photos</a></li>
  </ul>
</td>
<td>
  <h1><a href="/about">About</a></h1>
  <ul>
  <li><a href="/about/contact">Contact</a></li>
  <li><a href="/about/faq">FAQ</a></li>
  </ul>
</td>
</tr></table>
</div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<?php echo $scripts_for_layout; ?>
</body> 
</html> 
