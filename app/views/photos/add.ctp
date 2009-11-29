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
<table class="split">
<tr>
<td class="left pane">
<?php echo $form->file('Photo.photo')?>

<br />
<br />
<label for="PhotoCaption">Caption</label><?php echo $form->text('Photo.caption')?>
</td>
<td class="right pane">
<p>Please click on the map where the photo was taken</p>
<input type="hidden" name="data[Photo][location]" id="location" />
<div id="map" style="height: 400px; width: 400px;"></div>
</td>
</tr>
</table>
<div style="text-align: center; font-size: xx-large;">
<?php echo $form->end('Share') ?>
</div>
<?php $javascript->link('photos_add', false); ?>
