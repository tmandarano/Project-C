<?php
require_once('src/models/user.php');
require_once('src/dao/user_dao.php');
require_once('src/utils/helpers.php'); 

function users_get() {
    $users = UserDAO::get_users();
    return json($users);
}

function users_get_by_id() {
    $user_id = filter_var(params('id'));
    $users = UserDAO::get_users_by_id($user_id);
    return json($users);
}
	
function users_create() {
    $data = get_json_input();
    
    $user = new User();
    
    $user->set_username($data['username']);
    $user->set_email($data['email']);
    $user->set_password(md5($data['password']));
    $user->set_date_of_birth($data['date_of_birth']);
    $user->set_location($data['location']);
    
    $returned_id = UserDAO::save($user);
    $user->set_id($returned_id);
    
    return json($user);
}

?>
