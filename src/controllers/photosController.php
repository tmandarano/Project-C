<?php
require_once('src/models/photo.php');
require_once('src/dao/photo_dao.php');
require_once('src/utils/helpers.php');

function photos_get() {
    $photos = PhotoDao::getPhotos();
    return html(json($photos));
}

function photos_get_by_id() {
    $photos = PhotoDao::getPhotoById(filter_var(params('id')));
    return html(json($photos));
}

function photos_create() {
    $user = checkAuth();
    
    $data = getJSONInput();
        
    $photo = new Photo();
    $photo->setUserId($user->getId());
    
    $tag_str = $data['tags'];
    $tag_strs = explode(',', $tag_str);
    $tags = array();

    foreach($tag_strs as $tag_var) {
        $tag = new Tag();
        $tag->setTag($tag_var);
        $tags[] = $tag;
    }
    $photo->setTags($tags);
    
    $comment_str = $data['comments'];
    $comment_strs = explode(',', $comment_str);
    $comments = array();
    
    foreach($comment_strs as $comment_var) {
        $comment = new Comment();
        $comment->setComment($comment_var);
        $comments[] = $comment;
    }

    $photo->setCaption($data['caption']);
    $photo->setComments($comments);
    $photo->setName($data['userfile']);
    $photo->setLatitude($data['latitude']);
    $photo->setLongitude($data['longitude']);
    $photo->setLocation($data['location']);
    
    $returned_id = PhotoDAO::save($photo);
    $photo->setId($returned_id);
    savePhoto($photo);

    return html(json($photo));
}

function savePhoto($photo) {
    $extension = substr($photo->getName(), strrpos($photo->getName(), '.') + 1); 
    $new_file_name = "IMG" . $photo->getId() . "." . $extension;
    $target_path = option('IMAGES_DIR') . $new_file_name;
    
    $photo->setUrl("/images/" . $new_file_name);
    
    $upload_path = option('UPLOAD_DIR') . $photo->getName();
    copy($upload_path, $target_path);

    unlink($upload_path);
    PhotoDAO::update($photo);
}

?>
