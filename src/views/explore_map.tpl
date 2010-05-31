<div class="search controls">
  <form action="/explore/search" method="GET">
    <input type="text" size="20" name="what" value="What" class="default" />
    <input type="text" size="15" name="where" value="Where" class="default" />
    <input type="submit" value="Search" />
    <select name="network">
      <option value="everyone">Everyone</option>
      <option value="watching">Watching</option>
      <option value="friends">Friends</option>
    </select>
  </form>
</div>
<div id="map_explore" style="height: 500px;"></div>
<div id="results" class="stream">
  <div class="controls">
    <select name="most">
      <option value="recent">Most Recent</option>
      <option value="commented">Most Commented</option>
      <option value="liked">Most Liked</option>
    </select>
    <div id="time">
      <input type="radio" name="radio" id="timeday" checked="checked"><label for="timeday">day</label>
      <input type="radio" name="radio" id="timeweek"><label for="timeweek">week</label>
      <input type="radio" name="radio" id="timemonth"><label for="timemonth">month</label>
    </div>
  </div>
</div>
{capture name="scripts"}
<script type="text/javascript" src="/js/explore_map.js"></script>
{/capture}
