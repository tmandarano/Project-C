<?php
require_once('src/dao/user_dao.php');
require_once('src/utils/logging.php');
require_once('src/utils/helpers.php');
require_once('src/controllers/users_controller.php');


function sessions_janrain_create() {
    if(isset($_POST['token'])) { 
        $token = $_POST['token'];
        $post_data = array('token' => $_POST['token'], 'apiKey' => option('rpxApiKey'),
                           'format' => 'json');     

        $profile = janrain_post($post_data, 'auth_info');
        
        $user = null;
        $user = UserDao::get_user_by_identifier($profile['identifier']);

        if($user) {
            session_start();
            $_SESSION['user'] = serialize($user);
        } else {
            // Here we would normally spit back a message saying that session 
            // creation failed. Then we can use this on the client to try something else.
            // Send the "Bad Request" 400 response to let the client know that something was
            // was wrong with the user
            debug("Can't find user with identifier: ".$profile['identifier']);
            //return halt(400);
        }
        

        // Since we don't have the intermediate popup yet, let's pretend this 
        // got sent back to the user and that they agreed and then call the 
        // /users/create REST API. In this case we fake it by creating a 
        // temporary call to an identical method in the same class
        if(!$user) {
            $user = users_create_temp($profile);
            $_SESSION['user'] = serialize($user);
        }

        // Redirect for now. In reality we don't redirect in a REST api. We 
        // send back a json response and depending on the status that response 
        // is used to redirect.
        redirect_to('http://'.$_SERVER['HTTP_HOST']);
        
        // Send this back so then the user can be prompted if they'd like to 
        // create the user if the primaryKey is empty. The client will use 
        // $response to create a post for the /users/create call
        //return json($response);
    }
}


function sessions_delete() {
    // Unset all session variables.
    $_SESSION = array();

    // Delete the session cookie.
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    session_destroy();
}
?>
