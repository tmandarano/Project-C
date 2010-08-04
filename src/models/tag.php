<?php

/**
 * @internal
 * @property integer $id The id of the tag
 * @property string $tag The tag text itself.
 * @property time $date_added The date the objected was added to the system.
 * @property time $date_modified The date the object was last modified in the system.
 */
class Tag {
    public $id;
    public $tag;
    public $date_added;
    public $date_modified;
    
    public function get_id() {
        return $this->id;
    }
    
    public function set_id($id) {
        $this->id = $id;
    }
    
    public function get_tag() {
        return $this->tag;
    }
    
    public function set_tag($tag) {
        $this->tag = $tag;
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
