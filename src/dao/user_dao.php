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

        if($users && count($users) > 0) {
            $user = $users[0];
            return $user;
        } else {
            return null;
        }
    }

    public static function get_user_by_photo_id($photo_id) {
        $sql = 'SELECT * FROM user u join user_photos up on u.id = up.user_id ';
        $sql .= 'WHERE photo_id = :photo_id';
        $users = find_objects_by_sql($sql, array(':photo_id'=>$photo_id), 'User');

        $user = $users[0];
        return $user;
    }

    public static function get_user_by_standard_auth($username, $password) {
        $sql = 'SELECT * FROM user WHERE username = :username AND ';
        $sql .= 'password = :password';
        $users = find_objects_by_sql($sql, array(':username'=>$username,
                                                 ':password'=>$password),
                                     'User');

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

        $birthday = date("Y-m-d H:i:s", strtotime($user->get_date_of_birth())); 
        $user->set_date_of_birth($birthday);

        if($update) {
            $user_id = update_object($user, 'user', UserDao::user_columns());
        } else {
            $user_id = create_object($user, 'user', UserDao::user_columns());        
        }
        debug($user_id);
        return $user_id;
    }

    private static function user_columns() {
        return array('id', 'username', 'email', 'password', 'location',
                     'date_of_birth', 'date_added', 'date_modified');
    }
}
?>
