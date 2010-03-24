<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/src/utils/logging.php');

$uploadfile = UPLOAD_DIR . basename($_FILES['userfile']['name']);
$thefile = $_FILES['userfile']['tmp_name'];

if (move_uploaded_file($thefile, $uploadfile)) {
  debug("Successfully moved the file " . $thefile . " to " . $uploadfile);
  return $thefile;
} else {
  // WARNING! DO NOT USE "FALSE" STRING AS A RESPONSE!
  // Otherwise onSubmit event will not be fired
  debug("Error: File upload failed for " . $thefile);
}
