<div class="home out">
<table class="split">
  <tr>
    <td class="left pane">
    <p class="title"><strong>Show</strong> your friends what you are doing,
                     <strong>see</strong> what they are doing, and
                     <strong>discover</strong> like-minded people
                     all in <strong>real-time</strong>.
    </p>

    <table class="action">
      <tr>
        <td class="question">
          <p>There's just one question...</p>
          <p class="question">What do you see?</p>
        </td>
        <td class="connect">
          <a href="/users/add"><img src="/img/signup.png" /></a>
          <a href="/users/add"><img src="http://wiki.developers.facebook.com/images/f/f5/Connect_white_large_long.gif" /></a>
        </td>
      </tr>
    </table>
    
    <div class="stream">
      <h1>Popular <div class="tools"><?php echo $this->element('timelocup')?><?php echo $this->element('emotion')?></div></h1>
      <table class="photos">
        <tr>
          <?php for ($i=0; $i<min(8,count($recentPhotos)); $i++) {?>
            <td><?php echo $this->element('thumbnail', array('photo'=>$recentPhotos[$i]));?></td>
          <?php }?>
        </tr>
      </table>
    </div>
    <div class="stream">
      <h1>People <div class="tools"><?php echo $this->element('timelocup')?></div></h1>
      <table class="photos">
        <tr>
          <?php for ($i=8; $i<min(16,count($recentPhotos)); $i++) {?>
            <td><?php echo $this->element('thumbnail', array('photo'=>$recentPhotos[$i]));?></td>
          <?php }?>
        </tr>
      </table>
    </div>
    </td>
    <td class="right pane">
      <h1>Live public stream</h1>
      <div class="updating_map">
        <div id="map"></div>
        <div id="updating_map_stream"></div>
      </div>
    </td>
  </tr>
</table>
<table class="footer">
  <tr>
    <td>
      <h1><strong>Show</strong> what you are doing.</h1>
      <p>Show, don't tell. Use your mobile phone to upload in real-time.</p>
      <img src="/img/home/mobiles.png" />
    </td>
    <td>
      <h1><strong>See</strong> the world in real-time.</h1>
      <p>See local. See global. See what's interesting.</p>
      <div class="links">
        <ul class="left">
          <li><a href="#">Latest News</a></li>
          <li><a href="#">Events</a></li>
          <li><a href="#">Sports</a></li>
          <li><a href="#">Concerts</a></li>
        </ul>
        <ul class="right">
          <li><a href="#">Friends</a></li>
          <li><a href="#">Family</a></li>
          <li><a href="#">Celebrities</a></li>
        </ul>
      </div>
      <img src="/img/home/globe.png" />
    </td>
    <td>
      <h1><strong>Discover</strong> like-minded people.</h1>
      <p>Connect with people who have similar interests.</p>
      <img src="/img/home/people.png" />
    </td>
  </tr>
</table>
</div>
<?php $javascript->link('home', false); ?>
<script type="text/javascript">
var LG=LG?LG:{};
LG.recentPhotos=[<?php
$jsons = array();
foreach ($recentPhotos as &$photo) { $jsons[] = $this->element('json', array('photo' => $photo)); }
echo join(',', $jsons)?>];
</script>
