<?php
require_once('src/dao/user_dao.php');
require_once('logging.php');

function get_json_input() {
    return json_decode(file_get_contents("php://input"), true);
}

function ask_basic_auth() {
    header('WWW-Authenticate: Basic realm="LiveGather"');
    header('HTTP/1.0 401 Unauthorized');
    die();
}

function check_auth() {
    $id = $_SERVER['PHP_AUTH_USER'];
    if (empty($id)) {
        ask_basic_auth();
    } else {
        $user = UserDAO::get_user_by_id($id);
        if (empty($user)) {
            ask_basic_auth();
        } else {
            return $user;
        }
    }
}

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

// Get one master copy of the session user if it exists.
function get_session_user() {
    return (empty($_SESSION['user'])) ? null : unserialize($_SESSION['user']);
}

function get_user_by_session_or_id($id=null) { 
    $user = get_session_user();
    
    if(!$user) {
        $user = UserDao::get_user_by_id($id);

        if(!$user) {
            $user = null;
        } else {
            $_SESSION['user'] = serialize($user);
        }
    }    

    return $user;
}

function var_to_i($v) {
    return intval(filter_var($v, FILTER_VALIDATE_INT));
}
?>
