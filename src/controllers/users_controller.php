<?php
require_once('src/models/user.php');
require_once('src/dao/user_dao.php');
require_once('src/dao/identifier_dao.php');
require_once('src/models/identifier.php');
require_once('src/utils/helpers.php'); 
require_once('src/utils/janrain.php');


function users_get() {
    check_system_auth();

    $status = check_status_param();

    $users = UserDAO::get_users($status);

    return json($users);
}

function users_get_by_id() {
    check_system_auth();

    $status = check_status_param();

    $user_id = filter_var(params('id'));
    $user = UserDAO::get_user_by_id($user_id, $status);
    return json($user);
}

function users_get_by_identifier() {
    check_system_auth();

    $identifier = filter_var(params('id'));
    $user = UserDAO::get_user_by_identifier($identifier);

    return json($user);
}

function users_get_by_photo_id() {
    check_system_auth();

    $photo_id = filter_var(params('id'));
    $user = UserDAO::get_user_by_photo_id($photo_id);
    return json($user);
}
	
function users_create() {
    check_system_auth();

    // TODO Make this handle JSON as well
    $data = $_POST;

    // TODO Handle if they already have an account, kick it out so they login
    $user = new User();
    
    // TODO check to make sure username not taken
    $user->set_username($data['preferredUsername']);
    $user->set_email($data['email']);

    $returned_id = UserDAO::save($user);
    $user->set_id($returned_id);

    $identifier_string = $data['identifier'];
    $provider_name = $data['providerName'];

    janrain_map($identifier_string, $user->get_id(), option('rpxApiKey'));        
    $identifier = new Identifier();
    $identifier->set_user_id($user->get_id());
    $identifier->set_name($provider_name);
    $identifier->set_identifier($identifier_string);
    IdentifierDao::save($identifier);

    // Sign the new user in.
    __sessions_create($user);

    return json($user);
}


function users_get_photo_by_id() {
    header('Content-Type: image/png');
    header('Location: /img/50x50.jpg'); exit;
}


function users_add_identifier() {
    if(isset($_POST['token'])) { 
        $token = $_POST['token'];
        $post_data = array('token' => $_POST['token'], 'apiKey' => option('rpxApiKey'),
                           'format' => 'json');     

        $response = janrain_post($post_data, 'auth_info');
        
        $user_id = filter_var(params('id'));
        $user = UserDao::get_user_by_id($user_id);

        map_user_to_janrain($response, $user_id, option('rpxApiKey'));        
       
        return json($response);
    }
}
?>
