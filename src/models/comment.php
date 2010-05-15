<?php
class Comment {
	public $id;
	public $comment;
    public $data_added;
    public $date_modified;
	
	public function getId() {
		return $this->id;
	}
	
    public function setId($id) {
        $this->id = $id;
    }
	
    public function getComment() {
        return $this->comment;
    }
    
    public function setComment($comment) {
	    $this->comment = $comment;
	}

    public function getDateAdded() {
        return $this->date_added;
    }
    
    public function setDateAdded($date_added) {
        $this->date_added = $date_added;
    }

    public function getDateModified() {
        return $this->date_modified;
    }
    
    public function setDateModified($date_modified) {
        $this->date_modified = $date_modified;
    }
}
?>