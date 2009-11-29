<div class="home">
<table class="split">
  <tr>
    <td>
    <h1 class="title">Show your friends what you are doing, see what they are doing, and discover people with similar interests.</h1>

    <div class="question">
      <img src="/img/bubble_left.png" />
      <p><span>There's just one question...</span> what do you see?
      <a href="/users/add"><img src="/img/signup.png"/></a>
      </p>
    </div>
    
    <div class="stream">
      <h1>See <strong>local</strong>.</h1>
      <div class="tools">
        <?php echo $this->element('emotion')?>
        <a href="#">See <strong>interesting</strong></a> last <a href="#">hour</a>, <a href="#">day</a>, <a href="#">month</a></div>
      <table>
        <tr>
          <?php for ($i=0; $i<min(8,count($recentPhotos)); $i++) {?>
            <td><?php echo $this->element('thumbnail', array('photo'=>$recentPhotos[$i]));?></td>
          <?php }?>
        </tr>
      </table>
    </div>
    <div class="stream">
      <h1>See <strong>global</strong>.</h1>
      <div class="tools">
        <?php echo $this->element('emotion')?>
        <a href="#">See <strong>interesting</strong></a> last <a href="#">hour</a>, <a href="#">day</a>, <a href="#">month</a></div>
      <table>
        <tr>
          <?php for ($i=8; $i<min(16,count($recentPhotos)); $i++) {?>
            <td><?php echo $this->element('thumbnail', array('photo'=>$recentPhotos[$i]));?></td>
          <?php }?>
        </tr>
      </table>
    </div>
    </td>
    <td>
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
var LG = LG ? LG : {};
LG.recentPhotos = [
<?php
foreach ($recentPhotos as &$result) {
  $pic = $result['Photo'];
  $user = $result['User'];
  echo "{id:'".$pic['id']."',caption:\"".$pic['caption']."\",lat:".$pic['location'][1].",lng:".$pic['location'][0].",time:'".$time->timeAgoInWords($pic['datetime'], array('end'=>'+1month'))."',User:{id:".$user['id'].",name:'".$user['name']."'}},";
} ?>
];
</script>
