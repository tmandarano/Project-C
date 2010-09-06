<?php
require_once('src/utils/db.php');
require_once('src/models/beta.php');

class BetaDAO {
    public static function add_key($email, $key) {
        $beta = new Beta();
        $beta->set_email($email);
        $beta->set_key($key);
        return BetaDAO::save($beta);
    }

    public static function get_key($email) {
        $sql = 'SELECT * FROM beta WHERE email = :email LIMIT 1';
        $keys = find_objects_by_sql($sql, array(':email'=>$email), 'Beta');
        if ($keys) {
            return $keys[0]->get_key();
        } else {
            return null;
        }
    }

    public static function save($beta) {
        return create_object($beta, 'beta', BetaDao::beta_columns());
    }

    public static function beta_columns() {
        return array('email', 'key');
    }
}
?>
