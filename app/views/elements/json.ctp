<?php Configure::write('debug', 0);
foreach ($photos as &$photo) {
  $photo['Photo']['datetime'] = $time->timeAgoInWords($photo['Photo']['datetime'], array('end'=>'+1month'));
  $photo = $javascript->object($photo);
}
if (isset($photos[1])) {
  echo '['.implode(',',$photos).']';
} else {
  echo $photos[0];
}?>
