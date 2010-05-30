<?php
require_once('src/models/user.php');
require_once('src/dao/user_dao.php');
require_once('src/utils/helpers.php'); 

function users_get() {
    check_system_auth();

    $users = UserDAO::get_users();
    foreach ($users as $user) {
        $user->set_password('');
    }
    return json($users);
}

function users_get_by_id() {
    check_system_auth();

    $user_id = filter_var(params('id'));
    $users = UserDAO::get_user_by_id($user_id);
    $users->set_password('');
    return json($users);
}

function users_get_by_photo_id() {
    check_system_auth();

    $photo_id = filter_var(params('id'));
    $user = UserDAO::get_user_by_photo_id($photo_id);
    return json($user);
}
	
function users_create() {
    check_system_auth();

    $data = $_POST;
    
    $user = new User();
    
    // TODO check to make sure username not taken
    $user->set_username($data['username']);
    $user->set_email($data['email']);
    $user->set_password(md5($data['password']));
    $user->set_date_of_birth($data['date_of_birth']);
    $user->set_location($data['location']);
    
    $returned_id = UserDAO::save($user);
    $user->set_id($returned_id);
    
    return json($user);
}

function users_get_photo_by_id() {
    header('Content-Type: image/png');
    header('Location: /img/50x50.jpg'); exit;
}
?>
