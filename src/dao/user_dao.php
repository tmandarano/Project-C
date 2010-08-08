<?php
require_once('src/utils/db.php');
require_once('src/models/user.php');
require_once('src/utils/logging.php');

class UserDAO {
    public static function get_users($status=User::STATUS_ACTIVE) {
        $sql = 'SELECT * FROM user WHERE status = :status';

        $users = find_objects_by_sql($sql, array(':status'=>$status), 'User');
        
        return $users;
    }

    public static function get_user_by_id($id, $status=User::STATUS_ACTIVE) {
        $sql = 'SELECT * FROM user WHERE id = :id AND status = :status';

        $users = find_objects_by_sql($sql, array(':id'=>$id, ':status'=>$status), 'User');

        if (!empty($users)) {
            return $users[0];
        }
        return null;
    }

    public static function get_user_by_username($username) {
        $sql = 'SELECT * FROM user WHERE LOWER(username) = LOWER(:username) AND status = :status';

        $users = find_objects_by_sql($sql, array(':username'=>$username, ':status'=>User::STATUS_ACTIVE), 'User');

        if (!empty($users)) {
            return $users[0];
        }
        return null;
    }

    public static function get_user_by_photo_id($photo_id) {
        $sql = 'SELECT * FROM user JOIN user_photos ON user.id = user_photos.user_id ';
        $sql .= 'AND photo_id = :photo_id AND status = :status LIMIT 1';
        $users = find_objects_by_sql($sql, array(':photo_id'=>$photo_id,
                                                 ':status' => User::STATUS_ACTIVE), 'User');
      
        if (!empty($users)) {
            return $users[0];
        }
        return null;
    }

    public static function get_user_by_identifier($identifier) {
        $sql = 'SELECT u.id, u.username, u.photo_url, u.email, u.status, u.date_added, u.date_modified ';
        $sql .= 'FROM user u, identifier i WHERE ';
        $sql .= 'i.identifier = :identifier AND u.id = i.user_id';
        $users = find_objects_by_sql($sql, array(':identifier'=>$identifier), 'User');

        if (!empty($users)) {
            return $users[0];
        }
        return null;
    }

    public static function get_users_similar($user_id, $num) {
        // TODO determine what similar means
        return array();
    }

    public function save($user) {
        $now = time();
        $date = date("Y-m-d H:i:s", $now);
        $user->set_date_added($date);
        $user->set_date_modified($date);

        $status = $user->get_status();
        if(empty($status)) {
            $user->set_status(User::STATUS_ACTIVE);
        }

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
        return array('id', 'username', 'email', 'photo_url', 'status',
                     'date_added', 'date_modified');
    }
}
?>
