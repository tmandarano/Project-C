<?php
require_once('baseservice.php');
require_once('localhost/utils/restutils.php');
require_once('localhost/models/photo.php');
require_once('localhost/models/dao/photo_dao.php');

class PhotoService extends BaseService 
{       
    public function process() 
    {
        $data = RestUtils::processRequest();
        
        switch($data->getMethod())
        {
            case 'get':
                $vars = $data->getRequestVars();
              	$photos = PhotoDAO::getPhotos($vars['id']);

                RestUtils::sendResponse(200, json_encode($photos), 'application/json');
                break;
            case 'post':
                $vars = $data->getRequestVars();

                $photo = new Photo();

                $tagVal = $vars['tags'];
                $tagStrs = explode(',', $tagVal);
                $tags = array();
                foreach($tagStrs as $tagVar)
                {
                	$tag = new Tag();
                	$tag->setTag($tagVar);
                    $tags[] = $tag;
                }
                $photo->setTags($tags);
                
                $commentVal = $vars['comments'];
                $commentStrs = explode(',', $commentStr);
                $comments = array();
                foreach($commentStrs as $commentVar)
                {
                    $comment = new Comment();
                    $comment->setComment($commentVar);
                    $comments[] = $comment;
                }
                $photo->setComments($comments);
                
               	$photo->setUrl($vars['url']);
                $photo->setLocation($vars['location']);
            	$returnedId = PhotoDAO::save($photo);
            	RestUtils::sendResponse(200, json_encode($returnedId), 'application/json');
            	break;
        }
    }
}

?>
