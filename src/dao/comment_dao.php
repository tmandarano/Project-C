<?php
require_once('src/utils/db.php');

class CommentDAO {
    public static function getComments() {
        $sql = 'SELECT * FROM comment';
        $columns = find_objects_by_sql($sql, null, 'Column');

        return $columns;
    }

    public static function getCommentsById($id) {
        $sql = 'SELECT * FROM comment WHERE id = :id';
        $columns = find_objects_by_sql($sql, array(':id'=>$id), 'Column');

        return $columns;
    }

    public static function getCommentsForPhoto($photo_id) {
        $sql = 'SELECT c.id, c.comment as comment, c.date_added as date_added, c.date_modified as date_modified ';
        $sql .= 'FROM comment c JOIN photo_comments pc on c.id = pc.comment_id WHERE pc.photo_id = :photo_id';

        $comments = find_objects_by_sql($sql, array(':photo_id'=>$photo_id), 'Comment');

		return $comments;
	}
	
	public static function save($comment) {
        $comment_id = create_object($comment, 'comment', CommentDAO::comment_columns());

		return $comment_id;
	}

    private static function comment_columns() {
        return array('id', 'comment', 'date_added', 'date_modified');
    }
}
?>
