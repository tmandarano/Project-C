<?php
require_once('comment_dao.php');
require_once('tag_dao.php');
require_once('src/model/photo.php');
require_once('src/model/comment.php');
require_once('src/model/tag.php');
require_once('src/utils/connection_factory.php');

class PhotoDAO
{
    public static function getPhotos($id = null)
    {
        $conn = ConnectionFactory::getFactory()->getConnection();

        $rs;
        if($id)
        {
            $rs = $conn->query("SELECT * FROM photo WHERE id = ". $id)->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            $rs = $conn->query("SELECT * FROM photo")->fetchAll(PDO::FETCH_ASSOC);
        }

        return PhotoDAO::recordsToPhotos($rs);
    }

    public static function getRecentPhotos($limit = 10) {
      $conn = ConnectionFactory::getFactory()->getConnection();

      $rs = $conn->query("SELECT * FROM photo ORDER BY date_added DESC LIMIT ". $limit)->fetchAll(PDO::FETCH_ASSOC);

      return PhotoDAO::recordsToPhotos($rs);
    }
    
    public function save($photo)
    {
    	$conn = ConnectionFactory::getFactory()->getConnection();
		
        $sql = "INSERT INTO photo (url, location) VALUES (:url, :location)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":url", $photo->getUrl(), PDO::PARAM_STR);
        $stmt->bindParam(":location", $photo->getLocation(), PDO::PARAM_STR);
        $stmt->execute();
        $photo_id = $conn->lastInsertId();
        $photo->setId($photo_id); 

        $comments = $photo->getComments();
        foreach($comments as $comment)
        {
            $comment_id = CommentDAO::save($comment); 
            
            $sql = "INSERT INTO photo_comments (photo_id, comment_id) VALUES (:photo_id, :comment_id)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":photo_id", $photo_id, PDO::PARAM_INT);
            $stmt->bindParam(":comment_id", $comment_id, PDO::PARAM_INT);
            $stmt->execute();
        }

        foreach($photo->getTags() as $tag)
        {
        	$tag_id = TagDAO::save($tag);
            
            $sql = "INSERT INTO photo_tags (photo_id, tag_id) VALUES (:photo_id, :tag_id)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":photo_id", $photo_id, PDO::PARAM_INT);
            $stmt->bindParam(":tag_id", $tag_id, PDO::PARAM_INT);
            $stmt->execute();
        }
        
        return $photo_id;
	}
	
	public function update($photo)
	{
        $conn = ConnectionFactory::getFactory()->getConnection();
        
        $sql = "UPDATE photo set url = :url where id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":url", $photo->getUrl(), PDO::PARAM_STR);
        $stmt->bindParam(":id", $photo->getId(), PDO::PARAM_INT);
        
        if($stmt->execute())
            debug('success');
        else {
            debug($conn->errorCode());
            debug('failure');
        }
	}

    private static function recordsToPhotos($records) {
      $photos = array();
      foreach($records as $record) {
        $photo = new Photo();
        $photo->setId($record['id']);
        $photo->setUrl($record['url']);
        $photo->setLocation($record['location']);
        $photo->setDateAdded($record['date_added']);
        $photo->setDateModified($record['date_added']);
            
        $comments = CommentDAO::getComments(null, $record['id']);
        $photo->setComments($comments);
        
        $tags = TagDAO::getTags(null, $record['id']);
        $photo->setTags($tags);
        
        $photos[$record['id']] = $photo;
      }
      return $photos;
    }
}
?>
