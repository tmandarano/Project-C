<?php
require_once('src/dao/user_dao.php');
require_once('src/utils/logging.php');
require_once('src/utils/helpers.php');
require_once('src/controllers/users_controller.php');

function __sessions_create($user) {
    session_start();
    $_SESSION['user'] = serialize($user);
}

function sessions_janrain_create() {
    // This function runs as a result of an incoming call from Janrain.

    if (isset($_POST['token'])) { 
        $token = $_POST['token'];
        $post_data = array('token' => $_POST['token'], 'apiKey' => option('rpxApiKey'),
                           'format' => 'json');     

        $profile = janrain_post($post_data, 'auth_info');
        
        $user = UserDao::get_user_by_identifier($profile['identifier']);

        if ($user) {
            if(! ($user->get_status() == 'ACTIVE')) {
                debug("This user with this identifier is inactive. Identifier: " . $profile['identifier']);
                halt(400);
            }

            __sessions_create($user);
        } else {
            // We have no user with the given Janrain token. 
            // Unfortunately we can't be RESTful here (see redirect below).
            debug("Can't find user with identifier: ".$profile['identifier']);

            // We want to have a dialog that asks the user to create an 
            // account. This needs email, username, and display name.
            // We have most of these from Janrain.
            // Put them in SESSION so that the redirected page can setup 
            // the signup dialog properly.
            $_SESSION['signup'] = array(
                                        'email' => isset($profile['verifiedEmail']) ? 
                                        $profile['verifiedEmail'] : '',
                                        'username' => isset($profile['preferredUsername']) ? 
                                        $profile['preferredUsername'] : '',
                                        'display' => isset($profile['displayName']) ? 
                                        $profile['displayName'] : '',
                                        'identifier' => isset($profile['identifier']) ?
                                        $profile['identifier'] : '',
                                        'providerName' => isset($profile['providerName']) ?
                                        $profile['providerName'] : ''
            );
        }

        // Redirect. This is not RESTful but we have relinquished control of 
        // the datastream to Janrain and their API doesn't go beyond calling 
        // us back once. We need to handle it from here.
        redirect_to('http://'.$_SERVER['HTTP_HOST'].'/#signup');
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
