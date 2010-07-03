<?php
require_once('src/utils/db.php');
require_once('comment_dao.php');
require_once('tag_dao.php');
require_once('src/models/photo.php');

class PhotoDAO {
    public static function get_photos() {
        $sql = 'SELECT * FROM photo';
        $photos = find_objects_by_sql($sql, null, 'Photo');

        foreach($photos as $photo) {
            /*
            $comments = CommentDAO::get_comments_for_photo($photo->get_id());
            $photo->set_comments($comments);
            */
            $tags = TagDAO::get_tags_for_photo($photo->get_id());            
            $photo->set_tags($tags);
        }

        return $photos;
    }

    public static function get_photo_by_id($id) {
        $sql = 'SELECT * FROM photo WHERE id = :id';
        $photos = find_objects_by_sql($sql, array(':id'=>$id), 'Photo');

        foreach($photos as $photo) {
            //$comments = CommentDAO::get_comments_for_photo($photo->get_id());
            //$photo->set_comments($comments);

            $tags = TagDAO::get_tags_for_photo($photo->get_id());            
            $photo->set_tags($tags);
        }

        if ($photos && count($photos) > 0) {
            return $photos[0];
        }

        return null;
    }

    public static function get_photos_by_user_id($user_id) {
        $sql = 'SELECT * FROM user_photos up JOIN photo p ON up.photo_id = p.id ';
        $sql .= 'WHERE up.user_id = :user_id';

        $params = array('user_id'=>$user_id);

        $photos = find_objects_by_sql($sql, $params, 'Photo');

        foreach($photos as $photo) {
            //$comments = CommentDAO::get_comments_for_photo($photo->get_id());
            //$photo->set_comments($comments);

            $tags = TagDAO::get_tags_for_photo($photo->get_id());            
            $photo->set_tags($tags);
        }

        return $photos;
    }

    public static function get_photos_by_user_id_recent($user_id, $days) {
        $sql = 'SELECT * FROM user_photos up JOIN photo p ON up.photo_id = p.id ';
        $sql .= 'WHERE up.user_id = :user_id ';
        $sql .= 'AND p.date_added > DATE_SUB(NOW(), INTERVAL :days DAY)';

        $params = array('user_id'=>$user_id, 'days'=>$days);

        $photos = find_objects_by_sql($sql, $params, 'Photo');

        return $photos;
    }

    public static function get_photos_by_tag_id($tag_id) {
        $sql = 'SELECT * FROM photo WHERE id IN (SELECT photo_id FROM ';
        $sql .= 'photo_tags WHERE tag_id = :tag)';

        $photos = find_objects_by_sql($sql, array(':tag' => $tag_id), 'Photo');

        return $photos;
    }

    public static function get_recent_photos($limit = 10) {
        $sql = 'SELECT * FROM photo ORDER BY date_added DESC LIMIT :limit';
        $photos = find_objects_by_sql($sql, array(':limit' => $limit), 'Photo');

        foreach($photos as $photo) {
            //$comments = CommentDAO::get_comments_for_photo($photo->get_id());
            //$photo->set_comments($comments);
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

        if(!$update) {
            $photo->set_date_added($date);
        }
        $photo->set_date_modified($date);
        
        $photo_id = create_object($photo, 'photo', PhotoDao::photo_columns());

        /*
        $comments = $photo->get_comments();

        foreach($comments as $comment) {
            $comment_id = CommentDAO::save($comment); 

            $photo_comment = new stdClass();
            $photo_comment->photo_id = $photo_id;
            $photo_comment->comment_id = $comment_id;

            create_object($photo_comment, 'photo_comments',
                          array('photo_id', 'comment_id'));
        }
        */

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
        update_object($photo, 'photo', PhotoDao::photo_columns());
    }

    private static function photo_columns() {
        return array('id', 'url', 'latitude', 'longitude', 
                     'caption', 'date_added', 'date_modified');
    }
}
?>
