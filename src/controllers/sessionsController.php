<?php
require_once('src/dao/user_dao.php');
require_once('src/utils/logging.php');
require_once('src/utils/helpers.php');

function sessions_create() {
    $data = getJSONInput();
    $password = $data['password'];

    if(! preg_match('/^[a-f0-9]{32}$/', $password)) {
        $password = md5($password);
    }

    $users = UserDAO::getUserByStandardAuth($data['username'], $password);

    if(!is_null($users) && count($users) > 0) {
        $user = $users[0];
        session_start();
        $_SESSION['username'] = $user->getUsername();
        $_SESSION['email'] = $user->getEmail();

        return html(json($user));
    }
    else {
        return error_default_handler(401);
    }
}

?>
