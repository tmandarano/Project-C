<?php
require_once('src/models/user.php');
require_once('src/dao/user_dao.php');
require_once('src/utils/helpers.php'); 

function users_get() {
    $users = UserDAO::getUsers();
    return html(json($users));
}

function users_get_by_id() {
    $user_id = filter_var(params('id'));
    $users = UserDAO::getUsersById($user_id);
    return html(json($users));
}
	
function users_create() {
    $data = getJSONInput();
    
    $user = new User();
    
    $user->setUsername($data['username']);
    $user->setEmail($data['email']);
    $user->setPassword(md5($data['password']));
    $user->setDateOfBirth($data['date_of_birth']);
    $user->setLocation($data['location']);
    
    $returned_id = UserDAO::save($user);
    $user->setId($returned_id);
    
    return html(json($user));
}
?>
