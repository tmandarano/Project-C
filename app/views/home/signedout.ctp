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
              <td><?php echo $this->element('thumbnail', array('photo'=>$recentPhotos[$i]));?></td>
            <?php }?>
          </tr>
        </table>
      </div>
    </td>
    <td class="right pane">
      <div class="updating_map">
        <div id="map"></div>
        <div id="updating_map_stream" style="display: none;"></div>
      </div>
    </td>
  </tr>
</table>
</div>
<script type="text/javascript">
var LG=LG?LG:{};
LG.recentPhotos=['4b149b72-8780-47a2-8b73-663345a3cb7d', '4b12e638-f028-48b9-af33-0a572641192c', '4b12d768-4c24-4d29-bff2-7cac2641192c', '4b12d65c-f9dc-428d-ab64-7d3c2641192c', '4b12d1dd-e77c-4da9-b794-7c2f2641192c', '4b12d1cb-062c-4d22-aa0e-7ba22641192c', '4b121c60-8f54-4d69-b4be-69e62641192c', '4b11f698-68f8-409a-940c-3ec145a3cb7d', '4b11f662-a54c-4d45-9dae-3ec145a3cb7d', '4b11d228-06fc-4bd9-be47-55342641192c', '4b0ed330-4214-4677-92f7-5f702641192c', '4b0ed024-3ac4-4b80-a87d-235f2641192c', '4b0e63ea-4e64-4918-b088-2b942641192c'];
</script>
<?php $javascript->link('home_signedout', false); ?>
