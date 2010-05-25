<?php
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

    public function save($user) {
        $sql = 'INSERT INTO user (username, email, password, date_of_birth, ';
        $sql .= 'location, date_added, date_modified) ';
        $sql .= 'VALUES (:username, :email, :password, :date_of_birth, :location, NOW(), NOW())';

        $birthday = date("Y-m-d H:i:s", strtotime($user->get_date_of_birth())); 

        $params = array(':username'=>$user->get_username(),
                        ':email'=>$user->get_email(),
                        ':password'=>$user->get_password(),
                        ':date_of_birth'=>$birthday,
                        ':location'=>$user->get_location());

        $users = find_objects_by_sql($sql, $params, 'User');
        return $users;
    }

}
?>
