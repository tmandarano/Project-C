<h1>Photo upload</h1>
<?php if (isset($results)) { ?>
<h2>Uploaded photos:</h2>
<ul>
<?php foreach ($results['ids'] as $pic) { echo '<li>'.$pic.'</li>'; } ?>
</ul>
<h2>Errors</h2>
<ul>
<?php foreach ($results['errors'] as $pic) { echo '<li>'.$pic.'</li>'; } ?>
</ul>
<?php } ?>

<?php echo $form->create('Photo', array('type'=>'file'))?>
<table>
  <tr>
    <th>Photo</th>
    <td><?php echo $form->file('Photo.photo')?></td>
  </tr>
  <tr>
    <th><label for="PhotoCaption">Caption</label></th>
    <td><?php echo $form->text('Photo.caption')?></td>
  </tr>
  <tr>
    <th>Location</th>
    <td>
      <input type="hidden" name="data[Photo][location]" id="location" />
      <div id="map" style="height: 400px; width: 400px;"></div>
    </td>
    <td>Please click on the map where the photo was taken</td>
  </tr>
  <tr>
    <th></th>
    <td><span style="font-size: x-large;"><?php echo $form->end('Share') ?></span></td>
  </tr>
</table>
<?php $javascript->link('photos_add', false); ?>
