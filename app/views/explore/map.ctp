<div id="shelf">
<table>
<tr>
<td>
<?php echo $form->create(false, array('explore/search'));?>
<input type="text" size="9" />
<?php echo $form->end('Search');?>
</td>
<td><a href="#">Most Interesting</a></td>
<td><a href="#">Most Recent</a></td>
<td><p>__Current_Location</p></td>
<td class="zeitgeist">
  <p>
    <a href="#" onclick="viewpic('4b11f662-a54c-4d45-9dae-3ec145a3cb7d');">football</a>
    <a href="#" onclick="viewpic('4b11f662-a54c-4d45-9dae-3ec145a3cb7d');">game</a>
    <a href="#" onclick="viewpic('4b11f662-a54c-4d45-9dae-3ec145a3cb7d');">car</a>
    <a href="#" onclick="viewpic('4b11f662-a54c-4d45-9dae-3ec145a3cb7d');">speech</a>
    <a href="#" onclick="viewpic('4b11f662-a54c-4d45-9dae-3ec145a3cb7d');">beach</a>
  </p>
</td>
</tr>
</table>
</div>
<div id="map_explore" style="height: 700px;"></div>
<?php $javascript->link('explore', false); ?>
