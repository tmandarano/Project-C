<?php
require_once('src/models/photo.php');
require_once('src/dao/photo_dao.php');
require_once('src/dao/user_dao.php');
require_once('src/utils/helpers.php');

option('PHOTOS_IOS_DIR', 'iOS');
option('PHOTOS_IOS_RETINA_DIR', 'iOS_retina');

// Ensure the directories exist
function ensure_dir($dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
}
$IOS_DIR = option('PHOTOS_DIR') . option('PHOTOS_IOS_DIR');
ensure_dir($IOS_DIR);
ensure_dir($IOS_DIR . '/' . 't');
ensure_dir($IOS_DIR . '/' . 's');
ensure_dir($IOS_DIR . '/' . 'm');
ensure_dir($IOS_DIR . '/' . 'f');
$IOS_RETINA_DIR = option('PHOTOS_DIR') . option('PHOTOS_IOS_RETINA_DIR');
ensure_dir($IOS_RETINA_DIR);
ensure_dir($IOS_RETINA_DIR . '/' . 't');
ensure_dir($IOS_RETINA_DIR . '/' . 's');
ensure_dir($IOS_RETINA_DIR . '/' . 'm');
ensure_dir($IOS_RETINA_DIR . '/' . 'f');

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

function _meters_to_deg_lat($meters) {
    return $meters / 111131.745;
}

function _meters_to_deg_lng($meters, $lat) {
    // 111200 m = 1 degree at equator.
    // rough estimate of the degrees of longitude matching the meters given at 
    // a given latitude.
    if (!$lat or $lat == 0) {
        return $meters / 111200;
    } else { 
        return $meters / (111200 * cos($lat * M_PI / 180));
    }
}

function photos_recent_by_circle() {
    check_system_auth();

    $limit = var_to_i(params('limit'));
    $radius = var_to_i(params('radius'));
    $coord = explode(',', params('coord'));
    if (count($coord) != 2) {
        halt(400);
    }

    $lat = $coord[0];
    $lng = $coord[1];

    $dlat = _meters_to_deg_lat($radius);
    $dlng = _meters_to_deg_lng($radius, $lat);

    $points = array();
    $points[] = array($lat + $dlat, $lng);
    $points[] = array($lat, $lng + $dlng);
    $points[] = array($lat - $dlat, $lng);
    $points[] = array($lat, $lng - $dlng);

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
    $image_types = array('image/jpeg');//, 'image/gif', 'image/png');
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
    $photo->set_name($data['userfile']);
    $photo->set_latitude($data['latitude']);
    $photo->set_longitude($data['longitude']);
    
    $returned_id = PhotoDAO::save($photo);
    $photo->set_id($returned_id);
    save_photo($photo);

    return json($photo);
}

function save_photo($photo) {
    // Store the uploaded file in the toplevel.
    $new_filename = _get_photo_filename($photo) . '.' . _get_photo_extension($photo);
    $target_path = option('PHOTOS_DIR') . $new_filename;
    $photo->set_url("/photos/" . $new_filename);
    $upload_path = option('UPLOAD_DIR') . $photo->get_name();
    copy($upload_path, $target_path);
    unlink($upload_path);

    PhotoDAO::update($photo);

    // Image sizes needed:
    //            small   medium  full
    // iOS normal 61x61   125x130 290x360
    // iOS retina 122x122 250x260 580x520
    // 
    // I am designating a hierarchy within the photos directory anticipating 
    // more device specific sizes.
    // photos/
    //   originals can be stored in top level
    //   iOS/
    //     s/
    //     m/
    //     f/
    //   iOS_retina/
    //     s/
    //     m/
    //     f/
    _generate_iOS_photos($photo);
    _generate_iOS_retina_photos($photo);
}

function _get_photo_extension($photo) {
    $name = $photo->get_name();
    if ($name) {
        return strtolower(substr($name, strrpos($name, '.') + 1));
    } else {
        return 'jpg';
    }
}

function _get_photo_filename($photo) {
    return 'IMG'.$photo->get_id();
}

/** Tell if image is wider than it is tall. A square qualifies also.
 * Arg:
 *      img - the PHP image
 * Return:
 *      true if the image is wider than it is tall or as wide as it is tall.
 */
function _is_image_horizontal($img) {
    return imagesX($img) >= imagesY($img);
}

function _generate_iOS_photos($photo) {
    $dir = option('PHOTOS_DIR') . '/' . option('PHOTOS_IOS_DIR');
    $filename = _get_photo_filename($photo);
    $src_filename = option('PHOTOS_DIR') . $filename . '.' . _get_photo_extension($photo);
    debug('SOURCE: ' . $src_filename);

    imagegif(_thumbnailify_jpeg($src_filename, 50, 50), $dir . '/' . 't' . '/' . $filename . '.gif');
    imagegif(_thumbnailify_jpeg($src_filename, 61, 61), $dir . '/' . 's' . '/' . $filename . '.gif');
    imagegif(_thumbnailify_jpeg($src_filename, 125, 130), $dir . '/' . 'm' . '/' . $filename . '.gif');
    imagejpeg(_thumbnailify_jpeg($src_filename, 290, 360), $dir . '/' . 'f' . '/' . $filename . '.jpg', 100);
}

function _generate_iOS_retina_photos($photo) {
    $dir = option('PHOTOS_DIR') . '/' . option('PHOTOS_IOS_RETINA_DIR');
    $filename = _get_photo_filename($photo);
    $src_filename = option('PHOTOS_DIR') . $filename . '.' . _get_photo_extension($photo);

    imagegif(_thumbnailify_jpeg($src_filename, 50, 50), $dir . '/' . 't' . '/' . $filename . '.gif');
    imagegif(_thumbnailify_jpeg($src_filename, 122, 122), $dir . '/' . 's' . '/' . $filename . '.gif');
    imagegif(_thumbnailify_jpeg($src_filename, 250, 260), $dir . '/' . 'm' . '/' . $filename . '.gif');
    imagejpeg(_thumbnailify_jpeg($src_filename, 520, 580), $dir . '/' . 'f' . '/' . $filename . '.jpg', 100);
}

function _scaled_to_x($img, $px) {
    return array($px, imagesY($img) * $px / imagesX($img));
}

function _scaled_to_y($img, $px) {
    return array(imagesX($img) * $px / imagesY($img), $px);
}

function _thumbnailify_image($img, $x, $y) {
    // I want to scale to the axis that will result in there still being too 
    // much image left on the other axis; I can crop that.
    $width = imagesX($img);
    $height = imagesY($img);

    $scaled_to_x = _scaled_to_x($img, $x);
    $scaled_to_y = _scaled_to_y($img, $y);

    $cimg = imagecreatetruecolor($x, $y);
    if ($scaled_to_x[1] < $y) {
        // Scaling to the x axis makes y axis too small.
        $simg = _scale_image($img, $y, false);
        $pt = imagesx($simg) / 2 - $x / 2;
        imagecopyresized($cimg, $simg, 0, 0, $pt, 0, $x, $y, $pt + $x, $y);
    } else {
        // Scaling to the y axis makes x axis too small.
        $simg = _scale_image($img, $x, true);
        $pt = imagesy($simg) / 2 - $y / 2;
        imagecopyresized($cimg, $simg, 0, 0, 0, $pt, $x, $y, $x, $pt + $y);
    }
    return $cimg;
}

function _thumbnailify_jpeg($filename, $x, $y) {
    if (file_exists($filename)) {
        return _thumbnailify_image(imageCreateFromJpeg($filename), $x, $y);
    }
    halt(404);
}

/** Scale the given image.
 * Args:
 *      img - the PHP image to scale
 *      x - the target pixel size
 *      xAxis - boolean indicating x or y axis.
 * Return:
 *      scaled PHP image with the indicated axis at x pixels.
 */
function _scale_image($img, $px, $xAxis) {
    if (!$img) {
        return null;
    }
    $width = imagesX($img);
    $height = imagesY($img);
    $scaled = array($width, $height);
    if ($xAxis) {
        $scaled = _scaled_to_x($img, $px);
    } else {
        $scaled = _scaled_to_y($img, $px);
    }
    $scaledimg = ImageCreateTrueColor($scaled[0], $scaled[1]);
    ImageCopyResampled($scaledimg, $img, 0, 0, 0, 0,
                       $scaled[0], $scaled[1], $width, $height);
    return $scaledimg;
}

function photos_image_by_platform() {
    // Getting a photo using /api/photos/:id/full/{s,m,f}
    // will give the original size photo.
    $platform = params('platform');
    $ALLOWED_PLATFORMS = array('full',
                               option('PHOTOS_IOS_DIR'),
                               option('PHOTOS_IOS_RETINA_DIR'));
    $platformOK = false;
    foreach ($ALLOWED_PLATFORMS as $p) {
        if ($platform == $p) {
            $platformOK = true;
            break;
        }
    }

    if (!$platformOK) {
        debug('Illegal platform given during attempt to get photo:', $platform);
        halt(401);
    }

    $size = params('size');
    $ALLOWED_SIZES = array('t', 's', 'm', 'f');
    $sizeOK = false;
    foreach ($ALLOWED_SIZES as $s) {
        if ($size == $s) {
            $sizeOK = true;
            break;
        }
    }

    if (!$sizeOK) {
        debug('Illegal size given during attempt to get photo:', $size);
        halt(401);
    }

    $photo = PhotoDao::get_photo_by_id(var_to_i(params('id')));

    if (!$photo) {
        halt(404);
    }

    $path = '';
    if ($platform != 'full') {
        $path .= $platform;
        $path .= '/' . $size;
    }
    $path .= '/' . _get_photo_filename($photo);
    if ($size == 'f') {
        $path .= '.jpg';
    } else {
        $path .= '.gif';
    }

    if (!file_exists(option('PHOTOS_DIR') . $path)) {
        // try to generate the photos on the fly
        _generate_iOS_photos($photo);
        _generate_iOS_retina_photos($photo);
    }

    if (file_exists(option('PHOTOS_DIR') . $path)) {
        header('Cache-Control: public');
        header('Expires: '.date(DateTime::RFC1123, time() + 31556926));
        if ($size == 'f') {
            header('Content-Type: image/jpeg');
        } else {
            header('Content-Type: image/gif');
        }
        readfile(option('PHOTOS_DIR') . $path);
    } else {
        halt(404);
    }

    return;
}

?>
