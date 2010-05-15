<?php
require_once('src/dao/user_dao.php');

function getJSONInput() {
    return json_decode(file_get_contents("php://input"), true);
}

function checkAuth() {
    if(empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW'])) {
        header('WWW-Authenticate: Basic realm="example.com"');
        header('HTTP/1.0 401 Unauthorized');
        die();
    } else {
        $users = UserDAO::getUserByStandardAuth($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
        if(is_null($users) || count($users) <= 0) {
            return error_default_handler(401);           
        } else {
            return current($users);
        }
    }
}
?>