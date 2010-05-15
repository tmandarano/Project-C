<?php
require_once('src/models/user.php');

class UserDAO {
    public static function getUsers() {
        $sql = 'SELECT * FROM user';
        $users = find_objects_by_sql($sql, null, 'User');
        return $users;
    }

    public static function getUserById($id) {
        $sql = 'SELECT * FROM user WHERE id = :id';
        $users = find_objects_by_sql($sql, array(':id'=>$id), 'User');

        $user = $users[0];
        return $users;
    }

    public static function getUserByStandardAuth($username, $password) {
        $sql = 'SELECT * FROM user where username = :username and password = :password';
        $users = find_objects_by_sql($sql, array(':username'=>$username,':password'=>$password), 'User');

        $user = $users[0];
        return $users;
    }

    public function save($user) {
        $sql = 'INSERT INTO user (username, email, password, date_of_birth, location, date_added, date_modified) ';
        $sql .= 'VALUES (:username, :email, :password, :date_of_birth, :location, NOW(), NOW())';

        $birthday = date("Y-m-d H:i:s", strtotime($user->getDateOfBirth())); 

        $params = array(':username'=>$user->getUsername(),
                        ':email'=>$user->getEmail(),
                        ':password'=>$user->getPassword(),
                        ':date_of_birth'=>$birthday,
                        ':location'=>$user->getLocation());

        $users = find_objects_by_sql($sql, $params, 'User');
        return $users;
    }
}
?>
