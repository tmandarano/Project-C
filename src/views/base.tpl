<!DOCTYPE html>
<html> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<link rel="icon" type="image/png" href="/img/favicon.png" />
<link rel="shortcut icon" href="/favicon.ico" />
<link rel="stylesheet" type="text/css" href="/css/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="/css/screen.css" />
<title>{if $title}{$title} | {/if}LiveGather</title> 
</head> 
{php}flush(){/php}
<body class="{$class}"> 
{php}
function markSel($name, $env) {
  if (strpos($env, $name) !== false) {
    echo ' class="selected"';
  }
}
{/php}
<div id='header'> 
  <ul class='nav'> 
{if $class eq 'home out'}
    <li class="logo"><a href="/"><img src="/img/logo/medium_no_tagline.png" /></a></li>
{else}
    <li class="logo"><a href="/"><img src="/img/logo/small_no_tagline.png" /></a></li>
{/if}
    <li{php}markSel('Map', $title){/php}>
      <a href="/explore/map"><strong>Explore</strong>Map</a>
    </li> 
    <li{php}markSel('Photos', $title){/php}>
      <a href="/explore/photos"><strong>Explore</strong>Photos</a>
    </li> 
    {if $user != null}
      <li{php} markSel('Profile', $title){/php}><a
      href="/profile/{$user.id}">{$user.name}</a></li> 
      <li{php}markSel('Share', $title){/php}><a href="/share/upload">Share</a></li>
      <li{php}markSel('Settings', $title){/php}><a href="/settings">Settings</a></li>
      <li class="sign out"><a href="/signout">Sign out</a></li>
    {else}
      <li class="sign in">
        <img class="up" src="/img/signup/signup.png" />
        <img class="in" src="/img/signup/signin.png" />
      </li>
    {/if}
  </ul> 
  <ol id="headerstream"></ol>
</div> 
<noscript>
<p>Although we respect your choice to browse the web without Javascript
enabled or present, we believe that the emphasis of this site, real-time photo
sharing, cannot be well served without it. Please enable Javascript to enable
us to create an enjoyable experience.</p>
</noscript>
<div id="softener"></div>
<div id="content">
{php}flush(){/php}
{include file=$content}
</div>
<div id="footer">
<div>
<table><tr>
<td>
  <h1>Explore</h1>
  <ul>
  <li><a href="/explore/map">Map</a></li>
  <li><a href="/explore/photos">Photos</a></li>
  </ul>
</td>
<td>
  <h1>Share</h1>
  <ul>
  <li><a href="/share/mobile">Mobile</a></li>
  <li><a href="/share/upload">Upload</a></li>
  <li><a href="/share/webcam">Webcam</a></li>
  </ul>
</td>
<td>
  <h1><a href="/settings">Settings</a></h1>
</td>
<td>
  <h1>About</h1>
  <ul>
  <li><a href="/about/contact">Contact</a></li>
  <li><a href="/about/faq">FAQ</a></li>
  </ul>
</td>
</tr></table>
</div>
</div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript"
src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript" src="/js/global.js"></script>
{if $smarty.capture.scripts}{$smarty.capture.scripts}{/if}
</body> 
</html> 
