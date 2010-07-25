<!DOCTYPE html>
<html> 
<head> 
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /> 
<link rel='icon' type='image/png' href='/favicon.png' />
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
    {if $class == 'eye'}
    <li id='search' class='controls search'>
      <form action="" method="GET">
        <input type="text" name="what" class="default controls what"
               value="What" />
        <input type="text" name="where" class="default controls where"
               value="Where" />
        <input type="submit" value="Show" />
      </form>
    </li>
    {/if}
    {if $user != null}
      <li class="account clickable"><span>Account</span>
        <ol>
          <li><a href="/settings">Settings</a></li>
          <li class='sign out'><a href="/signout">Sign out</a></li>
        </ol>
      </li> 
      <li class="profile"><a href="/profile/{$user.id}">{$user.name}</a></li>
    {else}
      <li class='sign in'>
        <a class='rpxnow' onclick='return false;'
           href="https://livegather.rpxnow.com/openid/v2/signin?token_url=http%3A%2F%2F{$smarty.server.HTTP_HOST}%2Fsignin_janrain">
        <input type='submit' value='Sign in/up' /></a>
      </li>
    {/if}
  </ul> 
</div> 
<div id="headerstream"></div>
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
<div id="footer">
  <ul>
    <li><a href="/faq">FAQ</a></li>
    <li><a href="/privacy">Privacy Policy</a></li>
    <li><a href="/tos">Terms of Service</a></li>
    <li><a href="/contact">Contact Us</a></li>
    <li><a href="/team">The LiveGather Team</a></li>
    <li><button id="download">Download</button></li>
    <li class="copyright">Copyright &copy; 2010 LiveGather. All rights reserved.</li>
  </ul>
</div>
<script type="text/javascript" src="/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript"
src="http://www.google.com/jsapi?key=ABQIAAAATXJifusyeTqIXK5-oRfMqRSslEfASqMaD8v-0vRzO2Czj85WYhQPZi5KFZ5icheikRhGGD0OteWQRQA"></script>{*dev.livegather.com*}
<script type="text/javascript" src="/js/global.js"></script>
<script type="text/javascript">
  var rpxJsHost = (("https:" == document.location.protocol) ? "https://" : "http://static.");
  document.write(unescape(["%3Cscript src='", rpxJsHost,
    "rpxnow.com/js/lib/rpx.js' ",
    "type='text/javascript'%3E%3C/script%3E"].join('')));
</script>
<script type="text/javascript">
  RPXNOW.overlay = true;
  RPXNOW.language_preference = 'en';
  {if $smarty.session && is_array($smarty.session.signup)}
    LG.G.signupInfo = {literal}{{/literal}
      email: {literal}"{/literal}{$smarty.session.signup.email}{literal}"{/literal},
      username: {literal}"{/literal}{$smarty.session.signup.username}{literal}"{/literal},
      display: {literal}"{/literal}{$smarty.session.signup.display}{literal}"{/literal},
      identifier: {literal}"{/literal}{$smarty.session.signup.identifier}{literal}"{/literal},
      providerName: {literal}"{/literal}{$smarty.session.signup.providerName}{literal}"{/literal}
    {literal}};{/literal}
  {/if}
</script>
{if $smarty.capture.scripts}{$smarty.capture.scripts}{/if}
</body> 
</html> 
