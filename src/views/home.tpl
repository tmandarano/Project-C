<div class="pedestal">
<table class="split">
  <tr>
    <td class="left pane">
      <h1 class="title"><em>See</em> and <em>show</em> your world in real-time.</h1>
      <div class="why">
      <p><strong>Show</strong> your friends what you are doing.</p>
      <p><strong>See</strong> what they are doing.</p>
      <p><strong>See</strong> what's happening in the world.</p>
      <p><strong>Discover</strong> like-minded people.</p>
      </div>
      <div class="tags">
        <h1>Trending Tags</h1>
        <ul>
          {php}
          $tags = array('party', 'baseball', 'seahawks', 'car', 'funny', 'lunch');
          $this->assign('tags', $tags);
          {/php}
          {foreach from=$tags item=tag}
            <li><a href="{$tag}">{$tag}</a></li>
          {/foreach}
        </ul>
      </div>
    </td>
    <td class="right pane">
      <div class="updating_map">
        <div id="map"></div>
      </div>
    </td>
  </tr>
</table>
</div>
{capture name=scripts}
<script type="text/javascript" src="/js/home_signedout.js"></script>
{/capture}
