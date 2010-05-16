<?php
class User {
	public $id;
	public $username;
	public $email;
	public $password;
    public $date_of_birth;
    public $location;
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

    public function get_password() {
        return $this->password;
    }
    
    public function set_password($password) {
        $this->password = $password;
    }

    public function get_date_of_birth() {
        return $this->date_of_birth;
    }
    
    public function set_date_of_birth($date_of_birth) {
        $this->date_of_birth = $date_of_birth;
    }

    public function get_location() {
        return $this->location;
    }
    
    public function set_location($location) {
        $this->location = $location;
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