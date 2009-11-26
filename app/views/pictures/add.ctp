<h1>Picture upload</h1>
<?php if (isset($results)) { ?>
Uploaded pictures: (
<?php foreach ($results['ids'] as $pic) {
  echo $pic;
} ?>) <br />
Errors: (
<?php foreach ($results['errors'] as $pic) {
  echo $pic;
} ?>)
<?php } ?>

<?php
  echo $form->create('Picture', array('type'=>'file'));
  echo $form->file('Picture.picture');
?>
<p>Please click on the map where the picture was taken</p>
<input type="hidden" name="data[Picture][location]" id="location" />
<div id="map" style="height: 400px; width: 400px;"></div>
<p>Caption</p>
<?php
  echo $form->text('Picture.caption');
  echo $form->end('Upload');
?>
<?php $javascript->link('pictures_add', false); ?>
