<?php
require_once('base_controller.php');
require_once('src/dao/photo_dao.php');

function obj_to_arr($obj) {
    if (!is_object($obj) && !is_array($obj)) {
        return $obj;
    }
    if (is_object($obj)) {
        $obj = get_object_vars($obj);
    }
    return array_map('obj_to_arr', $obj);
}

/* Shards are pieces of html. */
class ShardController extends baseController {       
    public function photo_stub_profile_stream($vars) {
        $photo_id = $path[3];
        if (! $photo_id) {
            $photo_id = $vars[':id'];
        }
        $photos = PhotoDAO::get_photos($photo_id);
        $photo = obj_to_arr($photos[$photo_id]);
        $photo['datetime'] = '25 minutes ago';
        $photo['location'] = 'Bellevue, WA';
        $photo['caption'] = 'A caption';
        $this->assign('photo', $photo);
        return html($this->untemplated_fetch('shards/photo_stub_profile_stream.tpl'));
    }

    public function photo_stub_live_stream($vars) {
        $photo_id = $path[3];
        if (! $photo_id) {
            $photo_id = $vars[':id'];
        }
        $photos = PhotoDAO::get_photos($photo_id);
        $photo = obj_to_arr($photos[$photo_id]);
        $photo['user'] = array('name' => 'Tony Mandarano');
        $photo['datetime'] = '25 minutes ago';
        $photo['location'] = 'Bellevue, WA';
        $photo['caption'] = 'A caption';
        $this->assign('photo', $photo);
        return html($this->untemplated_fetch('shards/photo_stub_live_stream.tpl'));
    }

    public function photo_stub_explore_stream($vars) {
        $photo_id = $path[3];
        if (! $photo_id) {
            $photo_id = $vars[':id'];
        }
        $photos = PhotoDAO::get_photos($photo_id);
        $photo = obj_to_arr($photos[$photo_id]);
        $photo['user'] = array('name' => 'Tony Mandarano');
        $photo['datetime'] = '25 minutes ago';
        $photo['location'] = 'Bellevue, WA';
        $photo['caption'] = 'A caption';
        $this->assign('photo', $photo);
        return html($this->untemplated_fetch('shards/photo_stub_explore_stream.tpl'));
    }
}
?>
