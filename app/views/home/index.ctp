<div class="home">
<table>
  <tr>
    <td>
    <h1 class="title">Show your friends what you are doing, see what they are doing, and discover people with similar interests.</h1>
    <div><img src="/img/bubble_left.png" /><div class="question" style="display: inline; background: url('/img/bubble_center.png');">There's just one question...what do you see?</div></div>
    
    <div>
      See more photos here!
    </div>
    <div style="position: absolute; bottom: 1.8em; font-size: 1.5em;">Use LiveGather to easily update your Facebook and Twitter. Logos</div>
    </td>
    <td>
      <div id="updating_map" style="border: 1px solid; margin-bottom: 1em;">
      <div id="map" style="width: 400px; height: 350px"></div>
      <div id="updating_map_stream" style="height: 50px;background-color: black;"></div>
      </div>
    </td>
  </tr>
</table>
<table class="footer">
  <tr>
    <td>
      <h1><strong>Show</strong> what you are doing.</h1>
      <p>Life's greatest moments can't be described with words.</p>
    </td>
    <td>
      <h1><strong>See</strong> the world in real-time.</h1>
      <p>See local. See global. See what's interesting.</p>
      <img src="/img/globe.png" />
    </td>
    <td>
      <h1><strong>Discover</strong> like-minded people.</h1>
      <p>Connect with people who have similar interests.</p>
      <img src="/img/people.png" />
    </td>
  </tr>
</table>
</div>
<?php $javascript->link('home', false); ?>
<script type="text/javascript">
var PROJC = PROJC ? PROJC : {};
PROJC.recentPictures = [
<?php
foreach ($recentPictures as &$result) {
  $pic = $result['Picture'];
  $user = $result['User'];
  echo "{id:'".$pic['id']."',caption:'".$pic['caption']."',lat:'".$pic['lat']."',lng:'".$pic['lng']."',User:{name:'".$user['name']."'}},";
} ?>
];
</script>
