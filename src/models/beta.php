<?php

/**
 * @internal
 * @property string $email The email of the user that signed up
 * @property string $key The key that was assigned to them.
 */
class Beta {
    public $email;
    public $key;
    
    public function get_email() {
        return $this->email;
    }

    public function set_email($email) {
        $this->email = $email;
    }

    public function get_key() {
        return $this->key;
    }

    public function set_key($key) {
        $this->key = $key;
    }
}
?>
