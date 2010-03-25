<?php
require_once('baseController.php');
require_once('src/model/dao/photo_dao.php');

function objToArr($obj) {
  if (!is_object($obj) && !is_array($obj)) {
    return $obj;
  }
  if (is_object($obj)) {
    $obj = get_object_vars($obj);
  }
  return array_map('objToArr', $obj);
}

/* Shards are pieces of html. */
class ShardController extends baseController {       
  public function photo_stub_profile_stream($vars) {
    $photo_id = $path[3];
    if (! $photo_id) {
      $photo_id = $vars[':id'];
    }
    $photos = PhotoDAO::getPhotos($photo_id);
    $photo = objToArr($photos[$photo_id]);
    $photo['datetime'] = '25 minutes ago';
    $photo['location'] = 'Bellevue, WA';
    $photo['caption'] = 'A caption';
    $this->assign('photo', $photo);
    RestUtils::sendResponse(200, $this->untemplatedFetch('shards/photo_stub_profile_stream.tpl'));
  }
}
?>
