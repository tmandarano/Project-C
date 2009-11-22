<table>
<tr>
<td>
  <img src="/img/users/bigtiger23" /><a href="#">@bigtiger23</a>
  <p>crazy wildfires in LA!</p>
  <p class="location">Los Angeles, CA</p>
  <p class="time">30 minutes ago</p>
  <img src="/pictures/<?php echo $id; ?>" />
  <p><a href="#">View comments</a>
</td>
<td>
  <p><a href="#">Share to Facebook</a></p>
  <p><a href="#">Share to Twitter</a></p>
  <div class="similar">
    <h1>Similar pictures nearby</h1>
    <?php foreach ($related as $result) {
      echo "PROJC.html.img.db(3)+PROJC.html.img.db(5)+PROJC.html.img.db('fire_d')+'<br />'+PROJC.html.img.db('fire_e')+PROJC.html.img.db('fire_f')+PROJC.html.img.db('fire_g')";
    } ?>
  </div>
  <div id="map_location"></div>
</td>
</tr>
</table>
