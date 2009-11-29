<h1>Photo upload</h1>
<?php if (isset($results)) { ?>
Uploaded photos: (
<?php foreach ($results['ids'] as $pic) {
  echo $pic;
} ?>) <br />
Errors: (
<?php foreach ($results['errors'] as $pic) {
  echo $pic;
} ?>)
<?php } ?>

<table>
<tr>
<td>
<?php
  echo $form->create('Photo', array('type'=>'file'));
  echo $form->file('Photo.picture');
?>

<br />
<label for="PhotoCaption">Caption</label><?php echo $form->text('Photo.caption')?>
</td>
<td>
<p>Please click on the map where the photo was taken</p>
<input type="hidden" name="data[Photo][location]" id="location" />
<div id="map" style="height: 400px; width: 400px;"></div>
</td>
</tr>
</table>
<?php echo $form->end('Upload') ?>
<?php $javascript->link('photos_add', false); ?>
