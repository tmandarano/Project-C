<!-- TODO This is bad. Make this work in css somehow. -->
<style type="text/css">
#content {
  width: 100%;
  padding: 0;
}
</style>
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
  <h1>Zeitgeist</h1>
  <p>
    <a href="#" onclick="viewpic(1);">football</a>
    <a href="#" onclick="viewpic(2);">game</a>
    <a href="#" onclick="viewpic(3);">car</a>
    <a href="#" onclick="viewpic(4);">speech</a>
    <a href="#" onclick="viewpic(5);">beach</a>
  </p>
</td>
</tr>
</table>
</div>
<div id="map_explore" style="height: 700px;"></div>
<?php $javascript->link('explore', false); ?>
<?php $javascript->link('viewpic', false); ?>
