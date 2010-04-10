<?php
require_once('src/model/comment.php');
require_once('src/utils/connection_factory.php');

class CommentDAO
{
    public static function getComments($id = null, $photo_id = null)
	{
        $conn = ConnectionFactory::getFactory()->getConnection();						
        
        $rs;
        if($id)
        {
            $rs = $conn->query('SELECT * FROM comment WHERE id = '. $id)->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            $rs = $conn->query('SELECT * FROM comment')->fetchAll(PDO::FETCH_ASSOC);
        }

        if($photo_id)
        {
        	$sql = "SELECT c.id, c.comment as comment, c.date_added as date_added, c.date_modified as date_modified ";
            $sql = "FROM comment c JOIN photo_comments pc on c.id = pc.comment_id WHERE pc.photo_id = " . $photo_id);
        	$rs = $conn->query($sql)->fetchAll(PDO::FETCH_OBJ);
        }

        return $rs;
	}
	
	public function save($comment)
	{
        $conn = ConnectionFactory::getFactory()->getConnection();

        $sql = "INSERT INTO comment (comment, date_added, date_modified) VALUES (:comment, NOW(), NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":comment", $comment->getComment(), PDO::PARAM_STR);

        $stmt->execute();

        $return_id = $conn->lastInsertId(); 
        return $return_id;
	}

    public function delete($id = null)
    {
        $conn = ConnectionFactory::getFactory()->getConnection();
        
        $sql = "DELETE FROM comment WHERE comment.id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $this->setId($conn->lastInsertId()); 
    }
}
?>