<?php

/**
 * @internal
 * @property integer $id The id of the comment.
 * @property string $comment The comment string itself.
 * @property time $date_added The date the object was added to the system.
 * @property time $date_modified The date the object was last modified in the system.
 */
class Comment {
    public $id;
    public $comment;
    public $date_added;
    public $date_modified;
    
    public function get_id() {
        return $this->id;
    }
    
    public function set_id($id) {
        $this->id = $id;
    }
    
    public function get_comment() {
        return $this->comment;
    }
    
    public function set_comment($comment) {
        $this->comment = $comment;
    }

    public function get_date_added() {
        return $this->date_added;
    }
    
    public function set_date_added($date_added) {
        $this->date_added = $date_added;
    }

    public function get_date_modified() {
        return $this->date_modified;
    }
    
    public function set_date_modified($date_modified) {
        $this->date_modified = $date_modified;
    }
}
?>