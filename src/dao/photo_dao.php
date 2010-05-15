<?php
require_once('src/utils/db.php');
require_once('comment_dao.php');
require_once('tag_dao.php');
require_once('src/models/photo.php');

class PhotoDAO {
    public static function getPhotos() {
        $sql = 'SELECT * FROM photo';
        $photos = find_objects_by_sql($sql, null, 'Photo');

        foreach($photos as $photo) {
            $comments = CommentDAO::getCommentsForPhoto($photo->getId());
            $photo->setComments($comments);
            $tags = TagDAO::getTagsForPhoto($photo->getId());            
            $photo->setTags($tags);
        }

        return $photos;
    }

    public static function getPhotoById($id) {
        $sql = 'SELECT * FROM photo WHERE id = :id';
        $photos = find_objects_by_sql($sql, array(':id'=>$id), 'Photo');

        foreach($photos as $photo) {
            $comments = CommentDAO::getCommentsForPhoto($photo->getId());
            $photo->setComments($comments);

            $tags = TagDAO::getTagsForPhoto($photo->getId());            
            $photo->setTags($tags);
        }

        return $photos;
    }

    public static function getRecentPhotos($limit = 10) {
        $sql = 'SELECT * FROM photo ORDER BY date_added DESC LIMIT :limit';
        $photos = find_objects_by_sql($sql, NULL, 'Photo');
        
        foreach($photos as $photo) {
            $comments = CommentDAO::getComments(null, $photo->getId());
            $photo->setComments($comments);
            $tags = TagDAO::getTags(null, $photo->getId());            
            $photo->setTags($tags);
        }
        
        return $photos;
    }
    
    public static function save($photo) {
        $now = time();
        $photo->setDateModified($now);
        $photo->setDateAdded($now);
        
        $photo_id = create_object($photo, 'photo', PhotoDao::photo_columns());

        $comments = $photo->getComments();

        foreach($comments as $comment) {
            $comment_id = CommentDAO::save($comment); 

            $photo_comment = new stdClass();
            $photo_comment->photo_id = $photo_id;
            $photo_comment->comment_id = $comment_id;

            create_object($photo_comment, 'photo_comments', array('photo_id', 'comment_id'));
        }

        foreach($photo->getTags() as $tag) {
            $tag_id = TagDAO::save($tag);
            
            $photo_tag = new stdClass();
            $photo_tag->photo_id = $photo_id;
            $photo_tag->tag_id = $tag_id;
            
            create_object($photo_tag, 'photo_tags', array('photo_id', 'tag_id'));
        }

        $user_photo = new stdClass();
        $user_photo->user_id = $photo->getUserId();
        $user_photo->photo_id = $photo_id;
    
        create_object($user_photo, 'user_photos', array('user_id', 'photo_id'));
        
        return $photo_id;
    }
	
    public static function update($photo) {
        update_object($photo, 'photo', PhotoDao::photo_columns());
    }

    private static function photo_columns() {
        return array('id', 'url', 'latitude', 'longitude', 'location', 'caption', 'date_added', 'date_modified');
    }
}
?>
