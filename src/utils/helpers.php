<?php
require_once('src/dao/user_dao.php');
require_once('logging.php');

function get_json_input() {
    return json_decode(file_get_contents("php://input"), true);
}

function check_auth() {
    if(empty($_SERVER['PHP_AUTH_USER'])) {
        header('WWW-Authenticate: Basic realm="example.com"');
        header('HTTP/1.0 401 Unauthorized');
        die();
    } else {
        $user = UserDAO::get_user_by_identifier($_SERVER['PHP_AUTH_USER']);
        if(is_null($user)) {
            header('WWW-Authenticate: Basic realm="example.com"');
            header('HTTP/1.0 401 Unauthorized');
            die();
        } else {
            return $user;
        }
    }
}

function check_system_auth() {
    if(empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW'])) {
        header('WWW-Authenticate: Basic realm="example.com"');
        header('HTTP/1.0 401 Unauthorized');
        die();
    } else {
        $username = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];

        $user = UserDAO::get_user_by_identifier($_SERVER['PHP_AUTH_USER']);
        if(is_null($user) || count($user) <= 0) {
            if(($username == option('system_username') && $password == option('system_password')) ||
               (md5($username) == md5(option('system_username')) && md5($password) == md5(option('system_password')))) {
                return 1;
            } else {
                header('WWW-Authenticate: Basic realm="example.com"');
                header('HTTP/1.0 401 Unauthorized');
                die();
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
