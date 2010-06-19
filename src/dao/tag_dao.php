<?php
require_once('src/utils/db.php');

class TagDAO {
    public static function get_tags() {
        $sql = 'SELECT * FROM tag';
        $tags = find_objects_by_sql($sql, null, 'Tag');

        return $tags;
    }

    public static function get_tag_by_id($id) {
        $sql = 'SELECT * FROM tag WHERE id = :id';
        $tags = find_objects_by_sql($sql, array(':id'=>$id), 'Tag');
        if (!empty($tags)) {
            return $tags[0];
        }
        return null;
    }

    public static function get_tag_by_tag($tag) {
        $sql = 'SELECT * FROM tag WHERE tag = LOWER(:tag)';
        $tags = find_objects_by_sql($sql, array(':tag' => $tag), 'Tag');
        if (!empty($tags)) {
            return $tags[0];
        }
        return null;
    }

    public static function get_recent_tags($limit) {
        $sql = 'SELECT * FROM tag ORDER BY date_added DESC LIMIT :limit';
        $tags = find_objects_by_sql($sql, array(':limit'=>$limit), 'Tag');
        return $tags;
    }

    public static function get_trending_tags($limit) {
        // Done by the hour
        $sql =  'SELECT * FROM tag WHERE date_added > NOW() - MAKETIME(1, 0, 0) ';
        $sql .= 'ORDER BY date_added DESC LIMIT :limit';
        $tags = find_objects_by_sql($sql, array(':limit'=>$limit), 'Tag');
        return $tags;
    }

    public static function get_tags_by_user_id($id) {
        $sql = 'SELECT * FROM tag WHERE id IN (SELECT tag_id FROM ';
        $sql .= 'photo_tags pt, user_photos up WHERE ';
        $sql .= 'up.photo_id = pt.photo_id AND user_id = :id) ';
        $sql .= 'ORDER BY date_added DESC';
        $tags = find_objects_by_sql($sql, array(':id' => $id), 'Tag');
        return $tags;
    }

    public static function get_tags_by_photo_id($id) {
        return TagDAO::get_tags_for_photo($id);
    }

    public static function get_tags_for_photo($id) {
        $sql = 'SELECT * FROM tag WHERE id IN (SELECT tag_id FROM photo_tags ';
        $sql .= 'WHERE photo_id = :id) ORDER BY date_added DESC';
        $tags = find_objects_by_sql($sql, array(':id' => $id), 'Tag');
        return $tags;
    }

    public static function save($tag) {
        $sql = 'SELECT id FROM tag WHERE LOWER(tag) = LOWER(:tag)';
        $tags = find_objects_by_sql($sql, array(':tag'=>$tag->get_tag()), 'Tag');

        if(count($tags) > 0) {
            return $tags[0]->get_id();
        }

        $tag_id = create_object($tag, 'tag', TagDAO::tag_columns());

        return $tag_id;
    }
    
    public static function tag_columns() {
        return array('id', 'tag', 'date_added', 'date_modified');
    }        
}
?>
