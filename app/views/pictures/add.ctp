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
<? } ?>
<?php
  echo $form->create('Picture', array('type'=>'file'));
  echo $form->file('Picture.picture');
  echo $form->text('Picture.caption');
  echo $form->end('Upload');
?>
