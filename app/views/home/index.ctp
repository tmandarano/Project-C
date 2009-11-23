<div class="home">
<table style="width: 100%;">
  <tr>
    <td>
    <div id="updating_map" style="border: 1px solid; float: right; margin-left: 1em;">
      <div id="map" style="width: 400px; height: 350px"></div>
      <div id="updating_map_stream" style="height: 50px;background-color: black;"></div>
    </div>

    <h1 class="title">Show your friends what you are doing, see what they are doing, and discover people with similar interests.</h1>

    <div style="position: relative; height: 56px; vertical-align: center;">
      <img src="/img/bubble_left.png" style="height: 56px;" />
      <p class="question"><span style="color: #999;">There's just one question...</span> what do you see?</p>
    <img src="/img/signup.png" style="position: absolute; top: 0; left: 42ex;"/>
    </div>
    
    <div class="stream">
      <h1>See <strong>local</strong>.</h1>
      <div class="tools">
        <?php echo $this->element('emotion')?>
        <a href="#">See <strong>interesting</strong></a> last <a href="#">hour</a>, <a href="#">day</a>, <a href="#">month</a></div>
      <table class="pictures">
        <tr>
          <?php //echo $this->element('picture', array('picture'=>$recentPictures[$i]));?>
          <?php for ($i=0; $i<8; $i++) {?>
            <td><img src='/img/mini_pic.jpg' /></td>
          <?php }?>
        </tr>
      </table>
    </div>
    <div class="stream">
      <h1>See <strong>global</strong>.</h1>
      <div class="tools">
        <?php echo $this->element('emotion')?>
        <a href="#">See <strong>interesting</strong></a> last <a href="#">hour</a>, <a href="#">day</a>, <a href="#">month</a></div>
      <table class="pictures">
        <tr>
          <?php //echo $this->element('picture', array('picture'=>$recentPictures[$i]));?>
          <?php for ($i=0; $i<8; $i++) {?>
            <td><img src='/img/mini_pic.jpg' /></td>
          <?php }?>
        </tr>
      </table>
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
