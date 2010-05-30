<table class="split"><tr>
<td class="left pane">
  <table class="controls">
    <tr>
      <td>
        <ul class="stream_type">
          <li class="type">Friends</li>
          <li>Watching</li>
          <li>Everyone</li>
        </ul>
      </td>
      <td>
        <div id="specific_button">see someone specific</div>
      </td>
      <td class="right">
        <ul class="buttons">
          <li><img src="/img/controls_tags.png" /></li>
          <li><img src="/img/controls_block.png" /></li>
          <li><img src="/img/controls_blocks.png" /></li>
        </ul>
      </td>
    </tr>
  </table>
  <div class="tags">
    <h1>Trending Tags.</h1>
    <ul>
      {foreach from=$tags item=tag}
        <li><a href="{$tag}">{$tag}</a></li>
      {/foreach}
    </ul>
  </div>
  <div id="photo_map" class="map"></div>
  <ol class="live stream">
    {foreach from=$stream item=photo_id}
      <li class="photo stub" stub="live_stream|{$photo_id}"></li>
    {/foreach}
  </ol>
  <p class="loadmore"><a href="javascript:void(0);">Load older photos</a></p>
</td>
<td class="right pane">
  <h1 class="monochrome"><em>Live</em>Stream.</h1>
  <h1 class="bichrome"><em>Photos</em> you might like.</h1>
  <ul class="collage">
    {foreach from=$suggestedPhotos item=photo}
      <li><a href="/photos/view/{$photo.id}"><img src="/photos/{$photo.id}/1" /></a></li>
    {/foreach}
  </ul>

  <h1 class="bichrome"><em>People</em> you might like.</h1>
  <ul class="collage">
    {foreach from=$suggestedPeople item=person}
      <li><a href="/profile/{$person.id}"><img src="/users/photo/{$person.id}" /></a></li>
    {/foreach}
  </ul>

  <h1 id="social_stream" class="bubble">Social stream 
    <div class="right"><select><option>Friends</option><option>Family</option></select></div>
  </h1>
<ul class="social stream">
  {foreach from=$social item=e}
    <li>
      <p>
        <a class="username" href="/profile/{$e.user.id}">{$e.user.name}</a>
        {$e.action}
        <a class="username" href="/profile/{$e.actionee.id}">{$e.actionee.name}</a>'s
        <a href="/photos/view/{$e.photo.id}">photo</a>
        {if $e.descriptor}{$e.descriptor}{/if}
      </p>
      <img src="/photos/{$e.photo.id}/1" />
    </li>
  {/foreach}
</ul>
</td>
</tr></table>
{capture name='scripts'}
<script type="text/javascript" src="/js/live_streams.js"></script>
{/capture}
