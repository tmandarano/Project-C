<?php
require_once('src/models/photo.php');
require_once('src/dao/photo_dao.php');
require_once('src/utils/helpers.php');

function photos_get() {
    check_system_auth();

    $photos = PhotoDao::get_photos();
    return json($photos);
}

function photos_get_by_id() {
    check_system_auth();

    $photos = PhotoDao::get_photo_by_id(filter_var(params('id')));
    return json($photos);
}

function photos_recent() {
    check_system_auth();

    $limit = intval(filter_var(params('limit'), FILTER_VALIDATE_INT));
    return json(PhotoDao::get_recent_photos($limit));
}

function photos_get_by_user_id() {
    check_system_auth();

    $user_id = filter_var(params('id'));
    $photos = PhotoDao::get_photos_by_user_id($user_id);

    return json($photos);
}

function photos_get_by_user_id_recent() {
    check_system_auth();

    $user_id = filter_var(params('id'));
    $days = filter_var(params('days'));
    $photos = PhotoDao::get_photos_by_user_id_recent($user_id, $days);

    return json($photos);
}

function photos_create() {
    $user = check_auth();
    
    $data = get_json_input();

    if(! $user) {
	return array();	
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
    
    $comment_str = $data['comments'];
    $comment_strs = explode(',', $comment_str);
    $comments = array();
    
    foreach($comment_strs as $comment_var) {
        $comment = new Comment();
        $comment->set_comment($comment_var);
        $comments[] = $comment;
    }
 
    if(empty($data['userfile'])) {
	halt(400, "", "");        
    }
 
    $photo->set_caption($data['caption']);
    $photo->set_comments($comments);
    $photo->set_name($data['userfile']);
    $photo->set_latitude($data['latitude']);
    $photo->set_longitude($data['longitude']);
    $photo->set_location($data['location']);
    
    $returned_id = PhotoDAO::save($photo);
    $photo->set_id($returned_id);
    save_photo($photo);

    return json($photo);
}

function save_photo($photo) {
    $extension = substr($photo->get_name(), strrpos($photo->get_name(), '.') + 1); 
    $new_file_name = "IMG" . $photo->get_id() . "." . $extension;
    $target_path = option('IMAGES_DIR') . $new_file_name;
    
    $photo->set_url("/images/" . $new_file_name);
    
    $upload_path = option('UPLOAD_DIR') . $photo->get_name();
    copy($upload_path, $target_path);

    unlink($upload_path);
    PhotoDAO::update($photo);
}

function photo_by_size() {
    header('Content-Type: image/png');
    switch (params('size')) {
    case 0: header('Location: /img/30x30.jpg'); exit;
    case 1: header('Location: /img/50x50.jpg'); exit;
    case 2: header('Location: /img/50x50.jpg'); exit;
    case 3: header('Location: /img/270x270.jpg'); exit;
    case 'o': header('Location: /img/270x270.jpg'); exit;
    }
}

function photo() {
    params('size', 'o');
    return photo_by_size();
}

?>