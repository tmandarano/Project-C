<?php
require_once('src/models/user.php');

class UserDAO {
    public static function get_users() {
        $sql = 'SELECT * FROM user';
        $users = find_objects_by_sql($sql, null, 'User');
        return $users;
    }

    public static function get_user_by_id($id) {
        $sql = 'SELECT * FROM user WHERE id = :id';
        $users = find_objects_by_sql($sql, array(':id'=>$id), 'User');

        $user = $users[0];
        return $users;
    }

    public static function get_user_by_standard_auth($username, $password) {
        $sql = 'SELECT * FROM user where username = :username and password = :password';
        $users = find_objects_by_sql($sql, array(':username'=>$username,':password'=>$password), 'User');

        $user = $users[0];
        return $users;
    }

    public function save($user) {
        $sql = 'INSERT INTO user (username, email, password, date_of_birth, location, date_added, date_modified) ';
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
