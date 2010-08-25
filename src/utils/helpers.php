<?php
require_once('src/dao/user_dao.php');
require_once('logging.php');


/**
 * @internal
 * @return array
 *
 * This function grabs the JSON input from the server variables.
 */
function get_json_input() {
    return json_decode(file_get_contents("php://input"), true);
}


/**
 * @internal
 * @return array
 *
 * This function attempts to grab JSON input from the server variables.
 * If this is unsuccessful it then tries the $_POST variable.
 */
function get_json_or_post_input($params) {
    $data = get_json_input();

    $post = NULL;
    if(! $data) {
        $post = $_POST;
    }

    if($post) {
        foreach($params as $param) {
            $data[$var] = $post[$param];
        }
    }

    return $data;
}


/**
 * @internal
 *
 * This function simply request that the client authenticate with basic auth and then dies.
 * The result of this is to force the client to try again, this time sending the username and password.
 * Unless this happens the client doesn't even know basic auth is required and thus doesn't send these 
 * headers.
 */
function ask_basic_auth() {
    header('WWW-Authenticate: Basic realm="LiveGather"');
    header('HTTP/1.0 401 Unauthorized');
    die();
}

/**
 * @internal
 * @return boolean
 *
 * This function checks to make sure the system authentication (identifying the client as a valid client) is passed along 
 * and is valid.
 */
function check_system_auth() {
    $username = $_SERVER['PHP_AUTH_USER'];
    $password = $_SERVER['PHP_AUTH_PW'];
    if (empty($username) || empty($password)) {
        ask_basic_auth();
    } else {
        if (($username == option('system_username') &&
             $password == option('system_password'))) {
                return 1;
        } else {
            ask_basic_auth();
        }
    }
}


/**
 * @internal
 * @return object
 *
 * This function gets one master copy of the session user if it exists.
 */
function get_session_user() {
    return (empty($_SESSION['user'])) ? null : unserialize($_SESSION['user']);
}

/**
 * @internal
 * @return object
 *
 * This function gets one master copy of the session user if it exists.
 */
function get_user_by_session_or_identifier($identifier=null) { 
    $user = get_session_user();
    
    if(!$user && $identifier != null) {
        $user = UserDao::get_user_by_identifier($identifier);
    }

    if(!$user) {
        $user = null;
    } else {
        $_SESSION['user'] = serialize($user);
    }    

    return $user;
}

/**
 * @internal
 * @return integer
 *
 * This function converts a parameter to an int.
 */
function var_to_i($v) {
    return intval(filter_var($v, FILTER_VALIDATE_INT));
}

/**
 * @internal
 * @return boolean
 *
 * This function checks to see if the request is SSL or not.
 */
function is_request_https() {
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
        return true;
    }
}

/**
 * @internal
 * @return string
 *
 * This function returns the string representing what the protocol in use by the current REQUEST is.
 */
function get_protocol_string() {
    if(is_request_https()) {
        return 'https';
    } else {
        return 'http';
    }
}

/**
 * @internal
 * @return string
 *
 * This function checks the status parameter and returns the default if it's empty.
 */
function check_status_param() {
    $status = filter_var(params('status'));
    if(empty($status)) {
        $status = User::STATUS_ACTIVE;
    }
    return $status;
}
?>
