<?php
Configure::write('debug', 0);
$photo['Photo']['datetime'] = $time->timeAgoInWords($photo['Photo']['datetime'], array('end'=>'+1month'));
echo $javascript->object($photo);
?>
