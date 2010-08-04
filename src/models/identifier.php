<?php

/**
 * @internal
 * @property integer $id The id of the identifier.
 * @property integer $user_id The id of the user in the LiveGather system to whom this identifier belongs.
 * @property string $identifier The string value representing the unique OpenID/FaceBook/Twitter ID.
 * @property string $name The name of the identifying service. (e.g. Twitter, Facebook, etc.).
 * @property time $date_added The date the objected was added to the system.
 * @property time $date_modified The date the object was last modified in the system.
 */
class Identifier {
    public $id;
    public $user_id;
    public $identifier;
    public $name;
    public $date_added;
    public $date_modified;
    
    public function get_id() {
        return $this->id;
    }
    
    public function set_id($id) {
        $this->id = $id;
    }

    public function get_user_id() {
        return $this->user_id;        
    }
    
    public function set_user_id($user_id) {
        $this->user_id = $user_id;
    }
    
    public function set_identifier($identifier) {
        $this->identifier = $identifier;
    }

    public function get_identifier() {
        return $this->identifier;
    }

    public function set_name($name) {
        $this->name = $name;
    }

    public function get_name() {
        return $this->name;
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
