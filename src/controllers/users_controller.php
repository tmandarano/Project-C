<?php
require_once('src/models/user.php');
require_once('src/dao/user_dao.php');
require_once('src/utils/helpers.php'); 

function users_get() {
    check_system_auth();

    $users = UserDAO::get_users();
    return html(json($users));
}

function users_get_by_id() {
    check_system_auth();

    $user_id = filter_var(params('id'));
    $users = UserDAO::get_user_by_id($user_id);
    return html(json($users));
}

function users_get_by_photo_id() {
    check_system_auth();

    $photo_id = filter_var(params('id'));
    $user = UserDAO::get_user_by_photo_id($photo_id);
    return html(json($user));
}
	
function users_create() {
    check_system_auth();

    $data = get_json_input();
    
    $user = new User();
    
    $user->set_username($data['username']);
    $user->set_email($data['email']);
    $user->set_password(md5($data['password']));
    $user->set_date_of_birth($data['date_of_birth']);
    $user->set_location($data['location']);
    
    $returned_id = UserDAO::save($user);
    $user->set_id($returned_id);
    
    return html(json($user));
}
?>
