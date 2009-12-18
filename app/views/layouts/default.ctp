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
  <ul class='nav'> 
    <li class='logo'><a href="/">LiveGather</a></li> 
    <?php if ($user) { ?>
      <li<?php markSelected('Profile', $title_for_layout)?>><?php echo $html->link($user['name'], array('controller'=>'users', 'action'=>'profile', $user['id']))?></li> 
    <? } ?>
    <li<?php markSelected('Photos', $title_for_layout)?>><a href="/explore/photos">Photos</a></li> 
    <li<?php markSelected('People', $title_for_layout)?>><a href="/explore/people">People</a></li> 
    <?php if ($user) { ?>
    <li<?php markSelected('Share', $title_for_layout)?>><a href="/share/upload">Share</a></li>
    <li<?php markSelected('Settings', $title_for_layout)?>><a href="/users/settings">Settings</a></li>
    <?php } ?>
    <li><a href="/users/log<?php if ($user) {?>out<?php }else{?>in<?php }?>">Sign <?php if ($user) {?>out<?php }else{?>in<?php }?></a></li>
  </ul> 
  <ol id="headerstream" class="stream">
  </ol>
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
  <li><a href="/share/upload">Upload</a></li>
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
<script type="text/javascript" src="js/global.js"></script>
<?php echo $scripts_for_layout; ?>
</body> 
</html> 
