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
        $sql .= ' WHERE photo_id = :photo_id)';
        $users = find_objects_by_sql($sql, array(':photo_id'=>$photo_id), 'User');
      
        if (!empty($users)) {
            return $users[0];
        }
        return null;
    }

    public static function get_user_by_identifier($identifier) {
        $sql = 'SELECT u.id, u.username, u.photo_url, u.email, u.date_added, u.date_modified ';
        $sql .= 'FROM user u JOIN identifier i ON u.id = i.user_id';
        $sql .= ' WHERE identifier = :identifier';
        $users = find_objects_by_sql($sql, array(':identifier'=>$identifier), 'User');

        if (!empty($users)) {
            return $users[0];
        }
        return null;
    }

    public static function get_users_similar($user_id, $num) {
        // TODO
        return array();
    }

    public function save($user) {
        $now = time();
        $date = date("Y-m-d H:i:s", $now);
        $user->set_date_added($date);
        $user->set_date_modified($date);

        $user_id = create_object($user, 'user', UserDao::get_columns());        

        return $user_id;
    }

    public static function update($user) {
        $now = time();
        $date = date("Y-m-d H:i:s", $now);
        $user_id->set_date_modified($date);

        $user_id = update_object($user, 'user', UserDao::get_columns());
    }
    
    private static function get_columns() {
        return array('id', 'username', 'email', 'photo_url',
                     'date_added', 'date_modified');
    }
}
?>
