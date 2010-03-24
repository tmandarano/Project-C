<?php
require_once('baseController.php');
require_once('src/model/photo.php');
require_once('src/model/dao/photo_dao.php');

class PhotosController extends BaseController
{       
    public function show()
    {
        $PATH_INFO = $_SERVER["PATH_INFO"];
        $path = explode('/', $PATH_INFO);
        $photo_id = $path[3];
        $data = RestUtils::processRequest();
        $vars = $data->getRequestVars();
        
        if(! $photo_id)
        {
        	$photo_id = $vars['id'];
        }

        $photos = PhotoDAO::getPhotos($photo_id);
        RestUtils::sendResponse(200, json_encode($photos), 'application/json');
    }
	
    public function create()
    {
    	$data = RestUtils::processRequest();
    	$vars = $data->getRequestVars();
        
        $photo = new Photo();

        $tag_str = $vars['tags'];
        $tag_strs = explode(',', $tag_str);
        $tags = array();
        foreach($tag_strs as $tag_var)
        {
            $tag = new Tag();
            $tag->setTag($tag_var);
            $tags[] = $tag;
        }
        $photo->setTags($tags);
        
        $comment_str = $vars['comments'];
        $comment_strs = explode(',', $comment_str);
        $comments = array();
        foreach($comment_strs as $comment_var)
        {
            $comment = new Comment();
            $comment->setComment($comment_var);
            $comments[] = $comment;
        }
        $photo->setComments($comments);
        $photo->setName($vars['userfile']);
        $photo->setLocation($vars['location']);
        
        $returned_id = PhotoDAO::save($photo);
        $photo->setId($returned_id);
        $this->savePhoto($photo);

        RestUtils::sendResponse(200, json_encode($photo), 'application/json');
    }
    
    public function edit($photo)
    {
    	
    }
    
    public function delete()
    {
    	
    }

    public function recent() {
      $num_recent = $path[3];
      if (!$number) {
        $num_recent = 10;
      }
      RestUtils::sendResponse(200, json_encode(PhotoDAO::getRecentPhotos($num_recent)), 'application/json');
    }
    
    private function savePhoto($photo)
    {
        $extension = substr($photo->getName(), strrpos($photo->getName(), '.') + 1); 
        $new_file_name = "IMG" . $photo->getId() . "." . $extension;
    	$target_path = UPLOAD_DIR. $new_file_name;
        
        $photo->setUrl("/images/" . $new_file_name);
        
        $upload_path = UPLOAD_DIR . $photo->getName();
        
        copy($upload_path, $target_path);
        unlink($upload_path);
        PhotoDAO::update($photo);
    }
}

?>
