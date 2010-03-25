<table class="split"><tr>
<td class="left pane">
  <ol class="detailed stream">
    {assign name='photos' value='array(0, 1, 2, 3, 4)'}
    {foreach from=$photos item=photo}
      {include file='_detailed_stream.tpl', photo=$photo}
    {/foreach}
  </ol>
</td>
<td class="right pane">
<ul>
<li>2 new comments</li>
<li>2 requests</li>
<li>1 message</li>
</ul>
<h1 class="bubble">Social Stream <div class="right"><select><option>Friends</option><option>Family</option></select></div></h1>
<ul class="social stream">
  <li><p><a class="username" href="/profile/11">Tony Mandarano</a> commented on <a class="username" href="/profile/12">John Last</a>'s <a href="/photos/view/1">photo</a></p><img src="/photos/4b12d1dd-e77c-4da9-b794-7c2f2641192c/1" /></li>
  <li><p><a class="username" href="/profile/11">Will Noble</a> likes <a class="username" href="/profile/12">Jeff Other</a>'s <a href="/photos/view/1">photo</a></p><img src="/photos/4b12d1dd-e77c-4da9-b794-7c2f2641192c/1" /></li>
  <li><p><a class="username" href="/profile/11">Elliot Otherbody</a> thinks <a class="username" href="/profile/12">Nolan Somebody</a>'s <a href="/photos/view/1">photo</a> is <img src="/img/emotions/happy_l.png" /></p><img src="/photos/4b12d1dd-e77c-4da9-b794-7c2f2641192c/1" /></li>
  <li><p><a class="username" href="/profile/11">John Yayay</a> commented on <a class="username" href="/profile/12">Amir Boobooboo</a>'s <a href="/photos/view/1">photo</a></p><img src="/photos/4b12d1dd-e77c-4da9-b794-7c2f2641192c/1" /></li>
  <li><p><a class="username" href="/profile/11">Jessica Parker</a> thinks <a class="username" href="/profile/12">Sarah Lie</a>'s <a href="/photos/view/1">photo</a> is <img src="/img/emotions/cute_l.png" /></p><img src="/photos/4b12d1dd-e77c-4da9-b794-7c2f2641192c/1" /></li>
</ul>
</td>
</tr></table>
