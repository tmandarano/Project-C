<div id="shelf">
<table>
<tr>
<td>
<form action="/explore/search" method="get">
<input type="text" size="9" />
<input name="commit" type="submit" value="Search" />
</form>
</td>
<td>
  <ul class="stream_type">
    <li class="type">Friends</li>
    <li>Watching</li>
    <li>Everyone</li>
  </ul>
</td>
<td><a href="#">Most Interesting</a></td>
<td><a href="#">Most Recent</a></td>
<td><p>__Current_Location</p></td>
<td class="zeitgeist">
  <p>
    <a href="#" onclick="viewpic('451');">football</a>
    <a href="#" onclick="viewpic('451');">game</a>
    <a href="#" onclick="viewpic('451');">car</a>
    <a href="#" onclick="viewpic('451');">speech</a>
    <a href="#" onclick="viewpic('451');">beach</a>
  </p>
</td>
</tr>
</table>
</div>
<div id="map_explore" style="height: 700px;"></div>
{capture name="scripts"}
<script type="text/javascript" src="/js/explore_map.js"></script>
{/capture}
