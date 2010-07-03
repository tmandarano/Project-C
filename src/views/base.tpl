<!DOCTYPE html>
<html> 
<head> 
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /> 
<link rel='icon' type='image/png' href='/img/favicon.png' />
<link rel='shortcut icon' href='/favicon.ico' />
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.1/build/reset/reset-min.css" />
{*<link rel="stylesheet" type="text/css" href="/css/yui-reset-2.8.1.min.css" />*}
<link rel='stylesheet' type='text/css' href='/css/custom-theme/jquery-ui-1.8.2.custom.css' />
<link rel='stylesheet' type='text/css' href='/css/screen.css' />
<title>{if $title}{$title} | {/if}LiveGather</title> 
</head> 
{php}flush(){/php}
<body class="{$class}"> 
<div id='header'>
  <ul class='nav'>
    <li id='logo'><a href="/"><img src="/img/logo/small_no_tagline.png" /></a></li>
    <li id='search' class='controls search'>
      <form action="/explore/search" method="GET">
        <input type="text" name="search" class="default controls search"
               value="Search" />
        <input type="submit" value="Show" />
      </form>
    </li>
    {if $user != null}
      <li class='sign out'><a href="/api/signout">Sign out</a></li>
      <li class='profile'><a href="/profile/{$user.id}">{$user.name}</a></li> 
    {else}
      <li class='sign in'>
        <a class='rpxnow' onclick='return false;'
           href="https://livegather.rpxnow.com/openid/v2/signin?token_url=http%3A%2F%2F{$smarty.server.HTTP_HOST}%2Fapi%2Fsessions%2Fjanrain">
        <input type='submit' value='Sign in/up' /></a>
      </li>
    {/if}
  </ul> 
</div> 
<noscript>
<p>Although we respect your choice to browse the web without Javascript enabled
or present, we believe that the emphasis of this site, real-time photo sharing,
cannot be well served without it. Please enable Javascript to help us create an
enjoyable experience.</p>
</noscript>
<div id="content">
{php}flush(){/php}
{include file=$content}
</div>
{*
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
*}
<script type="text/javascript" src="/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="/js/global.js"></script>
{if $smarty.capture.scripts}{$smarty.capture.scripts}{/if}

<script type="text/javascript">
  var rpxJsHost = (("https:" == document.location.protocol) ? "https://" : "http://static.");
  document.write(unescape(["%3Cscript src='", rpxJsHost,
    "rpxnow.com/js/lib/rpx.js' ",
    "type='text/javascript'%3E%3C/script%3E"].join('')));
</script>
<script type="text/javascript">
  RPXNOW.overlay = true;
  RPXNOW.language_preference = 'en';
</script>
</body> 
</html> 
