<?php
class User {
    public $id;
    public $username;
    public $email;
    public $photo_url;
    public $status;
    public $date_added;
    public $date_modified;
    
    public function get_id() {
        return $this->id;
    }
    
    public function set_id($id) {
        $this->id = $id;
    }

    public function get_username() {
        return $this->username;        
    }
    
    public function set_username($username) {
        $this->username = $username;
    }
    
    public function set_email($email) {
        $this->email = $email;
    }

    public function get_email() {
        return $this->email;
    }

    public function get_photo_url() {
        return $this->photo_url;
    }
    
    public function set_photo_url($photo_url) {
        $this->photo_url = $photo_url;
    }

    public function get_status() {
        return $this->status;
    }
    
    public function set_status($status) {
        $this->status = $status;
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
