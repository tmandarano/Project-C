<?php
require_once('src/utils/db.php');

class TagDAO {
	public static function get_tags() {
        $sql = 'SELECT * FROM tag';
        $tags = find_objects_by_sql($sql, null, 'Tag');

        return $tags;
    }

	public static function get_tags_by_id($id) {
        $sql = 'SELECT * FROM tag WHERE id = :id';
        $tags = find_objects_by_sql($sql, array(':id'=>$id), 'Tag');

        return $tags;
    }

    public static function get_tags_for_photo($photo_id) {
        $sql = "SELECT t.id, t.tag as tag, t.date_added as date_added, t.date_modified as date_modified ";
        $sql .= "FROM photo_tags pt join tag t on pt.tag_id = t.id WHERE pt.photo_id = :photo_id";

        $tags = find_objects_by_sql($sql, array(':photo_id'=>$photo_id), 'Tag');

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
