<?php
require_once('src/models/user.php');
require_once('src/dao/user_dao.php');
require_once('src/dao/identifier_dao.php');
require_once('src/models/identifier.php');
require_once('src/utils/helpers.php'); 
require_once('src/utils/janrain.php');


function users_get() {
    check_system_auth();

    $users = UserDAO::get_users();

    return json($users);
}

function users_get_by_id() {
    check_system_auth();

    $user_id = filter_var(params('id'));
    $user = UserDAO::get_user_by_id($user_id);
    return json($user);
}

function users_get_by_identifier() {
    check_system_auth();

    $identifier = filter_var(params('identifier'));
    $user = UserDAO::get_user_by_id($identifier);

    return json($user);
}

function users_get_by_photo_id() {
    check_system_auth();

    $photo_id = filter_var(params('id'));
    $user = UserDAO::get_user_by_photo_id($photo_id);
    return json($user);
}
	
function users_create($profile) {
    check_system_auth();

    // TODO Handle if they already have an account, kick it out so they login
    $user = new User();
    
    // TODO check to make sure username not taken
    $user->set_username($profile['preferredUsername']);

    if(isset($profile['email'])) {
        $user->set_email($profile['email']);
    }
    
    if(isset($profile['photo'])) {
        $user->set_photo_url($profile['photo']);
    }

    // TODO store display name

    $returned_id = UserDAO::save($user);
    $user->set_id($returned_id);

    janrain_map($profile, $user->get_id(), option('rpxApiKey'));        
    $identifier = new Identifier();
    $identifier->set_user_id($user->get_id());
    $identifier->set_name($profile['providerName']);
    $identifier->set_identifier($profile['identifier']);
    IdentifierDao::save($identifier);
    debug(serialize($user));
    return json($user);
}

// Remove this once we no longer need it
function users_create_temp($profile) {
    $user = new User();
    
    // TODO check to make sure username not taken
    $user->set_username($profile['preferredUsername']);

    if(isset($profile['email'])) {
        $user->set_email($profile['email']);
    }

    if(isset($profile['photo'])) {
        $user->set_photo_url($profile['photo']);
    }

    $returned_id = UserDAO::save($user);
    $user->set_id($returned_id);

    janrain_map($profile, $user->get_id(), option('rpxApiKey'));        
    $identifier = new Identifier();
    $identifier->set_user_id($user->get_id());
    $identifier->set_name($profile['providerName']);
    $identifier->set_identifier($profile['identifier']);
    IdentifierDao::save($identifier);
    
    return $user;
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
