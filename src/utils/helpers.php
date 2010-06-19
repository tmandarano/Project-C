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
    $username = $_SERVER['PHP_AUTH_USER'];
    if (empty($username)) {
        ask_basic_auth();
    } else {
        $user = UserDAO::get_user_by_identifier($username);
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
        $user = UserDAO::get_user_by_identifier($username);
        if (empty($user)) {
            if (($username == option('system_username') &&
                 $password == option('system_password')) ||
                (md5($username) == md5(option('system_username')) &&
                 md5($password) == md5(option('system_password')))
               ) {
                return 1;
            } else {
                ask_basic_auth();
            }
        } else {
            return $user;
        }
    }
}

function var_to_i($v) {
    return intval(filter_var($v, FILTER_VALIDATE_INT));
}
?>
