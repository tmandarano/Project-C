<?php
require_once('src/utils/db.php');
require_once('src/models/user.php');
require_once('src/utils/logging.php');

class UserDAO {
    public static function get_users() {
        $sql = 'SELECT * FROM user';
        $users = find_objects_by_sql($sql, null, 'User');
        
        return $users;
    }

    public static function get_user_by_id($id) {
        $sql = 'SELECT * FROM user WHERE id = :id';
        $users = find_objects_by_sql($sql, array(':id'=>$id), 'User');

        if (!empty($users)) {
            return $users[0];
        }
        return null;
    }

    public static function get_user_by_photo_id($photo_id) {
        $sql = 'SELECT * FROM user WHERE id IN (SELECT user_id FROM user_photos ';
        $sql .= 'WHERE photo_id = :photo_id)';
        $users = find_objects_by_sql($sql, array(':photo_id'=>$photo_id), 'User');

        if (!empty($users)) {
            return $users[0];
        }
        return null;
    }

    public static function get_user_by_identifier($identifier) {
        $sql = 'SELECT * FROM user WHERE identifier = :id ';
        $users = find_objects_by_sql($sql, array(':id'=>$identifier), 'User');

        if($users && count($users) > 0) {
            $user = $users[0];
            return $user;
        } else {
            return null;	
        }
    }

    public function save($user, $update = false) {
        $now = time();
        $user->set_date_modified($now);
        $user->set_date_added($now);

        if($update) {
            $user_id = update_object($user, 'user', UserDao::user_columns());
        } else {
            $user_id = create_object($user, 'user', UserDao::user_columns());        
        }

        return $user_id;
    }

    private static function user_columns() {
        return array('id', 'username', 'email', 'photo_url', 'identifier',
                     'date_added', 'date_modified');
    }
}
?>
