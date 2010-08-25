<?php
require_once('src/models/photo.php');
require_once('src/dao/photo_dao.php');
require_once('src/dao/user_dao.php');
require_once('src/utils/helpers.php');

function photos_get() {
    $status = check_status_param();

    $photos = PhotoDao::get_photos($status);
    
    return json($photos);
}

function photos_get_by_id() {
    check_system_auth();

    $status = check_status_param();

    $photos = PhotoDao::get_photo_by_id(var_to_i(params('id')), $status);
    return json($photos);
}

function photos_recent() {
    check_system_auth();

    $limit = var_to_i(params('limit'));
    return json(PhotoDao::get_recent_photos($limit));
}

function photos_recent_by_area() {
    // sw, ne
    check_system_auth();

    $limit = var_to_i(params('limit'));

    $coords = explode(';', params('coords'));

    if (count($coords) != 2) {
        halt(400);
    }

    for ($i = 0; $i < count($coords); $i += 1) {
        $pts = explode(',', $coords[$i]);
        $coords[$i] = array($pts[0], $pts[1]);
    }

    $points = array($coords[0]);
    $points[] = array($coords[0][0], $coords[1][1]);
    $points[] = array($coords[1][0], $coords[1][1]);
    $points[] = array($coords[1][0], $coords[0][1]);
    $points[] = $coords[0];

    return json(PhotoDao::get_recent_photos_by_area($points, $limit));
}

function photos_get_by_user_id() {
    check_system_auth();

    $user_id = var_to_i(params('id'));
    $photos = PhotoDao::get_photos_by_user_id($user_id);

    return json($photos);
}

function photos_get_by_user_id_limited() {
    check_system_auth();

    $user_id = var_to_i(params('id'));
    $offset = var_to_i(params('offset'));
    $limit = var_to_i(params('limit'));
    $photos = PhotoDao::get_photos_by_user_id_limited($user_id, $offset, $limit);

    return json($photos);
}

function photos_get_by_user_id_recent() {
    check_system_auth();

    $user_id = var_to_i(params('id'));
    $days = var_to_i(params('days'));
    $photos = PhotoDao::get_photos_by_user_id_recent($user_id, $days);

    return json($photos);
}

function photos_get_by_tag_id() {
    check_system_auth();

    $tag_id = var_to_i(params('id'));
    $photos = PhotoDao::get_photos_by_tag_id($tag_id);

    return json($photos);
}

function photos_create() {
    check_system_auth();

    $expected_vars = array('identifier', 'caption', 'response', 'tags', 'latitude', 'longitude');
    $required_vars = array('caption', 'response', 'tags', 'latitude', 'longitude');

    $data = get_json_or_post_input($expected_vars);

    // Check to make sure all expected values are present
    foreach($required_vars as $var) {
        if(! isset($data[$var])) {
            debug("The required parameter ".$var." was not sent.");
            halt(400);
        }
    }
   
    $user = get_user_by_session_or_identifier($data['identifier']);
    if(!$user) {
        return halt(401);
    }

    $photo = new Photo();
    $photo->set_user_id($user->get_id());
    
    $tag_str = $data['tags'];
    $tag_strs = explode(',', $tag_str);
    $tags = array();

    foreach($tag_strs as $tag_var) {
        $tag = new Tag();
        $tag->set_tag($tag_var);
        $tags[] = $tag;
    }
    $photo->set_tags($tags);

    $photo->set_caption($data['caption']);
    $photo->set_name($data['response']);
    $photo->set_latitude($data['latitude']);
    $photo->set_longitude($data['longitude']);
    
    $returned_id = PhotoDAO::save($photo);
    $photo->set_id($returned_id);
    save_photo($photo);

    return json($photo);
}

function save_photo($photo) {
    $extension = substr($photo->get_name(), 
                        strrpos($photo->get_name(), '.') + 1); 
    $extension = strtolower($extension);
    $new_file_name = "IMG" . $photo->get_id() . "." . $extension;
    $target_path = option('PHOTOS_DIR') . $new_file_name;
    
    $photo->set_url("/photos/" . $new_file_name);

    $upload_path = option('UPLOAD_DIR') . $photo->get_name();
    copy($upload_path, $target_path);

    unlink($upload_path);
    PhotoDAO::update($photo);
}

function photo_by_size() {
    $target = 270;
    switch (params('size')) {
    case 0: $target = 30; break;
    case 1: $target = 40; break;
    case 2: $target = 50; break;
    case 3: $target = 270; break;
    case 'o': $target = 270; break;
    }
    $photo = PhotoDao::get_photo_by_id(var_to_i(params('id')));
    $extension = 'jpg';
    if ($photo) {
        $filename = option('PHOTOS_DIR') . 'IMG' . $photo->get_id() . '.' . $extension;
    } else {
        $filename = '';
    }
    if (file_exists($filename)) {
        $src = imagecreatefromjpeg($filename);
        $width = imagesx($src);
        $height = ImageSy($src);
        $x = $target;
        $y = $height * $target / $width;
        $scaled = ImageCreateTrueColor($x, $y);
        $scaled = ImageCreateTrueColor($x, $y);
        ImageCopyResampled($scaled, $src, 0, 0, 0, 0, $x, $y, $width, $height);
        header('Content-Type: image/jpeg');
        imagejpeg($scaled);
    } else {
        header('Cache-Control: public');
        header('Expires: '.date(DateTime::RFC1123, time() + 31556926));
        header('Content-Type: image/jpeg');
        switch ($target) {
        case 30: header('Location: /img/30x30.jpg'); exit;
        case 40: header('Location: /img/40x40.jpg'); exit;
        case 50: header('Location: /img/50x50.jpg'); exit;
        case 270: header('Location: /img/270x270.jpg'); exit;
        }
    }

    return;
}

function photo() {
    params('size', 'o');
    return photo_by_size();
}

function photos_add_tag() {
    check_system_auth();

    $photo_id = var_to_i(params('id'));
    $tag = params('tag');
    return json(PhotoDAO::add_tag($photo_id, $tag));
}

function photos_delete_tag() {
    check_system_auth();

    $photo_id = var_to_i(params('id'));
    $tag = params('tag');
    return json(PhotoDAO::delete_tag($photo_id, $tag));
}

function photos_upload() {
    $image_types = array('image/jpeg', 'image/gif', 'image/png');
    $type = $_FILES['userfile']['type'];
    if(! in_array($type, $image_types)) {
        debug("ERROR: File upload failed for " . $_FILES['userfile']['name'] . 
              ". Not a valid image type. Type is " . $type . ".");
        halt(403);
    }
    // Use the file upload tmp_name as tempname unlinks the file
    // and makes it no longer an "uploaded file", breaking move_uploaded_file()
    $tempname = $_FILES['userfile']['tmp_name'];

    // Grab the extension so we retain it
    $ext = strtolower(array_pop(explode(".", $_FILES['userfile']['name'])));
    $uploadfilename = str_replace("/tmp/", "", $tempname) . "." . $ext;
    $uploadfilepath = option('UPLOAD_DIR') . $uploadfilename;

    if (move_uploaded_file($tempname, $uploadfilepath)) {
        debug("Successfully moved the file " . $tempname . " to " . $uploadfilepath);
        return json($uploadfilename);
    } else {
        debug("ERROR: File upload failed for " . $tempname);
        // WARNING! DO NOT USE "FALSE" STRING AS A RESPONSE!
        // Otherwise onSubmit event will not be fired
        halt(500);
    }
}

function photos_update() {
    //    check_system_auth();

var_dump($_SERVER['REQUEST_METHOD']);
var_dump($_SERVER['REQUEST_URI']);

// if (($stream = fopen('php://input', "r")) !== FALSE) {
    debug('zzzzzzzzzzz');
    //    var_dump(file_get_contents($stream));
    //    halt();
    // }
    $post_vars = array('test'=>'');
    $z = file_get_contents("php://input");
    parse_str($z, $post_vars);
    debug($data['test']);
    debug(serialize($post_vars));
    halt();
    // Fill data with POST parameters if there is no incoming JSON
    if (!$data) {
        $p = env('PUT');
        $p = $p['REQUEST'];
        if ($p) {
            $vars = array('caption', 'userfile', 'tags', 'latitude', 
                          'longitude');
            foreach ($vars as $var) {
                $data[$var] = $p[$var];
            }
        }
    }

    $photo = PhotoDao::get_photo_by_id($data['id']);
    
    $user = get_user_by_session_or_id();
    if(!$user) {
        return halt(401);
    }

    $tag_str = $data['tags'];
    $tag_strs = explode(',', $tag_str);
    $tags = array();

    foreach($tag_strs as $tag_var) {
        $tag = new Tag();
        $tag->set_tag($tag_var);
        $tags[] = $tag;
    }
    $old_tags = $photo->get_tags();
    $current_tags = array_merge($tags, $old_tags);
    $photo->set_tags($current_tags);

    if(isset($data['caption']) && ! empty($data['caption'])) {
        $photo->set_caption($data['caption']);
    }

    if(isset($data['userfile']) && ! empty($data['userfile'])) {
        $photo->set_name($data['userfile']);
    }

    if(isset($data['latitude']) && ! empty($data['latitude'])) {
        $photo->set_latitude($data['latitude']);
    }

    if(isset($data['longitude']) && ! empty($data['longitude'])) {
        $photo->set_longitude($data['longitude']);
    }
    
    $returned_id = PhotoDAO::update($photo);

    if(isset($data['userfile'])) {
        save_photo($photo);
    }

    return json($photo);
}

?>
