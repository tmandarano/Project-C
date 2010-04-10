<?php
require_once('src/utils/connection_factory.php');

class TagDAO
{
	public static function getTags($id = null, $photo_id = null)
	{
		$conn = ConnectionFactory::getFactory()->getConnection();
		$rs;

		if($id)
		{
			$rs = $conn->query('SELECT * FROM tag WHERE id = '. $id)->fetchAll(PDO::FETCH_ASSOC);;
		}
		else
		{
			$rs = $conn->query('SELECT * FROM tag')->fetchAll(PDO::FETCH_ASSOC);;
		}

		if($photo_id)
		{
            $sql = "SELECT t.id, t.tag as tag, t.date_added as date_added, t.date_modified as date_modified ";
            $sql .= "FROM photo_tags pt join tag t on pt.tag_id = t.id WHERE pt.photo_id = " . $photo_id)
			$rs = $conn->query($sql)->fetchAll(PDO::FETCH_OBJ);
		}

		return $rs;
	}


	public function save($tag)
	{
		$conn = ConnectionFactory::getFactory()->getConnection();

		$rs = $conn->query("SELECT id from tag where tag = \"" . $tag->getTag() . "\"")->fetch(PDO::FETCH_OBJ);
        if($rs)
        {
        	return $rs->id;
        }
	
        $sql = "INSERT INTO tag (tag, date_added, date_modified) VALUES (:tag, NOW(), NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":tag", $tag->getTag(), PDO::PARAM_STR);
        $stmt->bindParam(":date_added", $tag->getDateAdded(), PDO::PARAM_STR);
        $stmt->execute();

		$return_id = $conn->lastInsertId();
		return $return_id;
	}
}
?>