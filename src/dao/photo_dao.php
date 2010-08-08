<?php
require_once('src/utils/db.php');
require_once('comment_dao.php');
require_once('tag_dao.php');
require_once('src/models/photo.php');
require_once('src/models/user.php');

function pair_points($points) { return $points[0].' '.$points[1]; };

class PhotoDAO {
    private static function get_user_id($photo_id) {
        $sql = 'SELECT user_id FROM user_photos WHERE photo_id = :id';
        $stmt = option('db_conn')->prepare($sql);
        $stmt->bindParam(':id', $photo_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row[0];
    }

    public static function get_photos($status=Photo::STATUS_ACTIVE) {
        $sql = 'SELECT * FROM photo WHERE status = :status';
        $photos = find_objects_by_sql($sql, array(':status'=>$status), 'Photo');

        foreach($photos as $photo) {
            $tags = TagDAO::get_tags_for_photo($photo->get_id());            
            $photo->set_tags($tags);
            $photo->set_user_id(PhotoDAO::get_user_id($photo->get_id()));
        }

        return $photos;
    }

    public static function get_photo_by_id($id, $status=Photo::STATUS_ACTIVE) {
        $sql = 'SELECT * FROM photo WHERE id = :id AND status = :status';
        $photos = find_objects_by_sql($sql, array(':id'=>$id, ':status'=>$status), 'Photo');

        foreach($photos as $photo) {
            $tags = TagDAO::get_tags_for_photo($photo->get_id());            
            $photo->set_tags($tags);
            $photo->set_user_id(PhotoDAO::get_user_id($photo->get_id()));
        }

        if ($photos && count($photos) > 0) {
            return $photos[0];
        }

        return null;
    }

    public static function get_photos_by_user_id($user_id) {
        $sql = 'SELECT * FROM user_photos up JOIN photo p ON up.photo_id = p.id ';
        $sql .= 'AND up.user_id = :user_id AND status = :status';

        $params = array('user_id'=>$user_id, 'status'=>Photo::STATUS_ACTIVE);

        $photos = find_objects_by_sql($sql, $params, 'Photo');

        foreach($photos as $photo) {
            $tags = TagDAO::get_tags_for_photo($photo->get_id());            
            $photo->set_tags($tags);
        }

        return $photos;
    }

    public static function get_photos_by_user_id_recent($user_id, $days) {
        $sql = 'SELECT * FROM user_photos up JOIN photo p ON up.photo_id = p.id ';
        $sql .= 'AND up.user_id = :user_id ';
        $sql .= 'AND p.date_added > DATE_SUB(NOW(), INTERVAL :days DAY) AND p.status = :status';

        $params = array('user_id'=>$user_id, 'days'=>$days, 'status'=>Photo::STATUS_ACTIVE);

        $photos = find_objects_by_sql($sql, $params, 'Photo');

        return $photos;
    }

    public static function get_photos_by_tag_id($tag_id) {
        $sql = 'SELECT * FROM photo JOIN photo_tags ON ';
        $sql .= 'photo.id = photo_tags.photo_id AND tag_id = :tag ';
        $sql .= 'AND status = :status';

        $photos = find_objects_by_sql(
            $sql, array(':tag' => $tag_id,
                        ':status'=>Photo::STATUS_ACTIVE), 'Photo');

        return $photos;
    }

    public static function get_recent_photos($limit = 10) {
        $sql = 'SELECT * FROM photo WHERE status = :status ORDER BY date_added DESC LIMIT :limit';
        $photos = find_objects_by_sql($sql, array(':status'=>Photo::STATUS_ACTIVE, ':limit' => $limit), 'Photo');

        foreach($photos as $photo) {
            $tags = TagDAO::get_tags_for_photo($photo->get_id());            
            $photo->set_tags($tags);
        }

        return $photos;
    }

    public static function get_recent_photos_by_area($points, $limit = 10) {
        $polygon_str = implode(', ', array_map('pair_points', $points));

        $sql = "SELECT * FROM photo WHERE status = :status AND MBRContains(GeomFromText('Polygon((".$polygon_str."))'), geolocation)";
        $sql .= " ORDER BY date_added DESC LIMIT :offset";

        $photos = find_objects_by_sql($sql, array(':status'=>Photo::STATUS_ACTIVE, ':offset' => $limit), 'Photo');

        foreach($photos as $photo) {
            $tags = TagDAO::get_tags_for_photo($photo->get_id());            
            $photo->set_tags($tags);
        }

        return $photos;
    }

    public static function add_tag($photo_id, $tag) {
        $tagobj = new Tag();
        $tagobj->set_tag($tag);
        $tag_id = TagDAO::save($tagobj);
        $sql = 'INSERT IGNORE INTO photo_tags (photo_id, tag_id) VALUES (:photo_id, :tag_id)';
        $db = option('db_conn');
        $stmt = $db->prepare($sql);
        $stmt->execute(array(':photo_id' => $photo_id, ':tag_id' => $tag_id));
        return $db->lastInsertId();
    }

    public static function delete_tag($photo_id, $tag) {
        $tag = TagDAO::get_tag_by_tag($tag);
        if (!$tag) {
            return 404;
        }
        $sql = 'DELETE FROM photo_tags WHERE photo_id = :photo_id AND tag_id = :tag_id';
        $db = option('db_conn');
        $stmt = $db->prepare($sql);
        $stmt->execute(array(':photo_id' => $photo_id, ':tag_id' => $tag->get_id()));
        return 200;
    }

    public static function save($photo) {
        $now = time();
        $date = date("Y-m-d H:i:s", $now);
        $photo->set_date_modified($date);
        $photo->set_date_added($date);

        $geolocation = "POINT(".$photo->get_latitude()." ".$photo->get_longitude().")";

        $photo->set_geolocation($geolocation);

        $status = $photo->get_status();
        if(empty($status)) {
            $photo->set_status(Photo::STATUS_ACTIVE);
        }
        
        $photo_id = create_object($photo, 'photo', PhotoDao::photo_columns(), array('','','geomfromtext'));

        $tags = $photo->get_tags();

        foreach($tags as $tag) {
            $tag_id = TagDAO::save($tag);
            
            $photo_tag = new stdClass();
            $photo_tag->photo_id = $photo_id;
            $photo_tag->tag_id = $tag_id;
            
            create_object($photo_tag, 'photo_tags', array('photo_id', 'tag_id'));
        }

        $user_photo = new stdClass();
        $user_photo->user_id = $photo->get_user_id();
        $user_photo->photo_id = $photo_id;
    
        create_object($user_photo, 'user_photos', array('user_id', 'photo_id'));
        
        return $photo_id;
    }
	
    public static function update($photo) {
        $now = time();
        $date = date("Y-m-d H:i:s", $now);
        $photo->set_date_modified($date);

        $photo_id = update_object($photo, 'photo', PhotoDao::photo_update_columns());

        $tags = $photo->get_tags();

        foreach($tags as $tag) {
            $tag_id = TagDAO::save($tag);
            
            $photo_tag = new stdClass();
            $photo_tag->photo_id = $photo_id;
            $photo_tag->tag_id = $tag_id;
            
            create_object($photo_tag, 'photo_tags', array('photo_id', 'tag_id'));
        }

        $user_photo = new stdClass();
        $user_photo->user_id = $photo->get_user_id();
        $user_photo->photo_id = $photo_id;
    
        create_object($user_photo, 'user_photos', array('user_id', 'photo_id'));

        return $photo_id;
    }

    private static function photo_columns() {
        return array('id', 'url', 'geolocation', 'latitude', 'longitude', 
                     'caption', 'status', 'date_added', 'date_modified');
    }

    private static function photo_update_columns() {
        return array('id', 'url', 'latitude', 'longitude', 
                     'caption', 'status', 'date_modified');
    }
}
?>
