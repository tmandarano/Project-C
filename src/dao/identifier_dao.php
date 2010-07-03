<?php
require_once('src/utils/db.php');
require_once('src/models/identifier.php');
require_once('src/utils/logging.php');

class IdentifierDAO {
    public static function get_identifiers() {
        $sql = 'SELECT * FROM identifier';
        $identifiers = find_objects_by_sql($sql, null, 'Identifier');
        
        return $identifiers;
    }

    public static function get_identifier_by_id($id) {
        $sql = 'SELECT * FROM identifier WHERE id = :id';

        $identifiers = find_objects_by_sql($sql, array(':id'=>$id), 
                                           'Identifier');

        if (!empty($identifiers)) {
            return $identifiers[0];
        }
        return null;
    }

    public static function get_identifier_by_identifier($identifier) {
        $sql = 'SELECT * FROM identifier WHERE identifier = :identifier';
        $identifiers = find_objects_by_sql($sql, 
                                           array(':identifier'=>$identifier), 
                                           'Identifier');

        if (!empty($identifiers)) {
            return $identifiers[0];
        }
        return null;
    }

    public static function get_identifiers_by_user_id($user_id) {
        $sql = 'SELECT * FROM identifier WHERE user_id = :user_id';
        $identifiers = find_objects_by_sql($sql, 
                                           array(':user_id'=>$user_id), 
                                           'Identifier');

        return $identifiers;
    }

    public function save($identifier, $update = false) {
        $now = time();
        $date = date("Y-m-d H:i:s", $now);

        if(!$update) {
            $identifier->set_date_added($date);
        }
        $identifier->set_date_modified($date);


        if($update) {
            $identifier_id = update_object($identifier, 
                                           'identifier', 
                                           IdentifierDao::get_columns());
        } else {
            $identifier_id = create_object($identifier, 'identifier', 
                                           IdentifierDao::get_columns()); 
        }

        return $identifier_id;
    }

    private static function get_columns() {
        return array('id', 'user_id', 'identifier', 'name',
                     'date_added', 'date_modified');
    }
}
?>
