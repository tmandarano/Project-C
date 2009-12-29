<div class="pedestal">
<table class="split">
  <tr>
    <td class="left pane">
      <h1 class="title">Share your life as you live it, in real-time.</h1>
      <div class="why">
      <p><strong>Show</strong> your friends what you are doing.</p>
      <p><strong>See</strong> what they are doing.</p>
      <p><strong>Discover</strong> like-minded people.</p>
      </div>
      <div class="connect">
        <a href="/users/add"><img src="/img/signup.png" /></a> <span>or</span> <a href="/users/add"><img src="http://wiki.developers.facebook.com/images/f/f5/Connect_white_large_long.gif" /></a>
      </div>
      <div class="stream">
        <h1>Explore <div class="tools"><?php echo $this->element('timelocup')?><?php echo $this->element('emotion')?></div></h1>
        <table class="photos">
          <tr>
            <?php for ($i=0; $i<min(8,count($recentPhotos)); $i++) {?>
              <?php $photo = $recentPhotos[$i]['Photo']?>
              <td class="s1"><a href="#" onclick="viewpic('<?php echo $photo['id']?>')"><img src="/photos/<?php echo $photo['id']?>/1" /></a></td>
            <?php }?>
          </tr>
        </table>
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
<?php $javascript->link('home_signedout', false); ?>
