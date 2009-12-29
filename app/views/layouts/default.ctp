<!DOCTYPE html>
<html> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<link rel="stylesheet" type="text/css" href="/css/screen.css" />
<title><?php echo $title_for_layout?> | LiveGather</title> 
</head> 
<body class="<?php echo $pageClass?>"> 
<?php function markSel($name, $env) {
  if (strpos($env, $name) !== false) {
    echo ' class="selected"';
  }
}?>
<div id='header'> 
  <ul class='nav'> 
<?php if ($pageClass != 'home out') {?>
    <li class='logo'><a href="/">LiveGather</a></li> 
    <li<?php markSel('Map', $title_for_layout)?>><a href="/explore/map">Map</a></li> 
    <li<?php markSel('Photos', $title_for_layout)?>><a href="/explore/photos">Photos</a></li> 
    <li<?php markSel('People', $title_for_layout)?>><a href="/explore/people">People</a></li> 
    <?php if ($user) { ?>
      <li<?php markSel('Profile', $title_for_layout)?>><a href="/users/profile/<?php echo $user['id']?>"><?php echo $user['name']?></a></li> 
      <li<?php markSel('Share', $title_for_layout)?>><a href="/share/upload">Share</a></li>
      <li<?php markSel('Settings', $title_for_layout)?>><a href="/users/settings">Settings</a></li>
    <? } ?>
<?php } else {?>
    <li class="logo"><a href="/"><img src="/img/logo.png" /></a></li>
<?php }?>
    <li class="sign"><a href="/users/log<?php if ($user) {?>out<?php }else{?>in<?php }?>">Sign <?php if ($user) {?>out<?php }else{?>in<?php }?></a></li>
  </ul> 
  <ol id="headerstream"></ol>
</div> 
<noscript>
<p>Although we respect your choice to browse the web without Javascript enabled or present, we believe that the emphasis of this site, real-time photo sharing, cannot be well served without it. Please enable Javascript to enable us to create an enjoyable experience.</p>
</noscript>
<div id="softener"></div>
<div id="content">
<?php echo $content_for_layout?>
</div>
<div id="footer">
<div>
<table><tr>
<td>
  <h1><a href="/explore">Explore</a></h1>
  <ul>
  <li><a href="/explore/map">Map</a></li>
  <li><a href="/explore/people">People</a></li>
  <li><a href="/explore/photos">Photos</a></li>
  </ul>
</td>
<td>
  <h1><a href="/share">Share</a></h1>
  <ul>
  <li><a href="/share/mobile">Mobile</a></li>
  <li><a href="/share/upload">Upload</a></li>
  <li><a href="/share/webcam">Webcam</a></li>
  </ul>
</td>
<td>
  <h1><a href="/users/settings">Settings</a></h1>
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
</div>
<div id="scripts">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript" src="/js/global.js"></script>
<?php echo $scripts_for_layout; ?>
</div>
</body> 
</html> 
