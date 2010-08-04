<?php

/**
 * @internal
 * @property integer $id The id of the session.
 */
class Session {
    public $id;
    public $email;
    public $password;
    
    public function get_id() {
        return $this->id;
    }
    
    public function set_id($id) {
        $this->id = $id;
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
}
?>