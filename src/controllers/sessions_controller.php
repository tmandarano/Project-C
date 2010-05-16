<?php
require_once('src/dao/user_dao.php');
require_once('src/utils/logging.php');
require_once('src/utils/helpers.php');

function sessions_create() {
    $data = get_json_input();
    $password = $data['password'];

    if(! preg_match('/^[a-f0-9]{32}$/', $password)) {
        $password = md5($password);
    }

    $users = UserDAO::get_user_by_standard_auth($data['username'], $password);

    if(!is_null($users) && count($users) > 0) {
        $user = $users[0];
        session_start();
        $_SESSION['username'] = $user->get_username();
        $_SESSION['email'] = $user->get_email();

        return html(json($user));
    }
    else {
        return error_default_handler(401, "", "", "");
    }
}

?>
