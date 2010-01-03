<?php
$id = $userinfo['id'];
$name = $userinfo['name'];
$location = $userinfo['location'];
$bio = $userinfo['bio']; //TODO do highlighting of matched words using span.match
$interests = $userinfo['interests']; //TODO do highlighting of matched words using span.match
?>
<li>
  <a href="/users/profile/<?php echo $id?>"><img src="/users/photo/<?php echo $id?>" /></a>
  <a href="/users/profile/<?php echo $id?>" class="username"><?php echo $name?></a>
  <ul>
    <li><span class="label">Location</span> <span class="location"><?php echo $location?></span></li>
    <li><span class="label">Bio</span> <?php echo $bio?></li>
    <li><span class="label">Interests</span> <?php echo $interests?></li>
  </ul>
  <div class="photo"><a href="/photos/view/<?php echo $mostRecentPhoto['id']?>"><img src="/photos/<?php echo $mostRecentPhoto['id']?>/3" /></a></div>
</li>
