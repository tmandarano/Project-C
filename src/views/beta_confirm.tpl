<!DOCTYPE html>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /> 
<link rel='icon' type='image/png' href='/favicon.png' />
<link rel='shortcut icon' href='/favicon.ico' />
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.1/build/reset/reset-min.css" />
<link rel='stylesheet' type='text/css' href='/css/custom-theme/jquery-ui-1.8.2.custom.css' />
<link rel='stylesheet' type='text/css' href='/css/screen.css' />
<link rel='stylesheet' type='text/css' href='/css/beta.css' />
<title>Confirm | LiveGather Beta</title>
</head>
<body class="beta_confirm">

<div id="header">
<div>
<img src="/img/logo/medium_no_tagline.png">
<p><em>See</em> and <em>show</em> what's happening near you.</p>
</div>
</div>

<div id="content">

<h1><p>Please verify you are in the beta.</p></h1>

<p>Please enter your email and beta key.</p>

{if $smarty.session.error != 1}
<p class="error">{$smarty.session.error}</p>
{/if}

<form class="beta_confirmed" action="/beta/confirm" method="POST">
<table>
  <tr>
    <td>
      <input type="text" name="email" class="defaultable" value="email" />
    </td>
  </tr>
  <tr>
    <td>
      <input type="text" name="key" class="defaultable" value="key" />
    </td>
  </tr>
  <tr>
    <td>
      <input type="submit" class="clickable" value="let's go!" />
    </td>
  </tr>
</table>
</form>

<img src="/img/globe.png" />
</div>
<div id="footer"></div>
<script type="text/javascript" src="/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript"
src="http://www.google.com/jsapi?key=ABQIAAAATXJifusyeTqIXK5-oRfMqRSslEfASqMaD8v-0vRzO2Czj85WYhQPZi5KFZ5icheikRhGGD0OteWQRQA"></script>{*dev.livegather.com*}
<script type="text/javascript">
{literal}var LG = {NO_HEADERSTREAM: true};{/literal}
</script>
<script type="text/javascript" src="/js/global.js"></script>
</body>
</html>
