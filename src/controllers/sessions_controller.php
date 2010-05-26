<?php
require_once('src/dao/user_dao.php');
require_once('src/utils/logging.php');
require_once('src/utils/helpers.php');

function sessions_create() {
    $data = $_POST;
    $password = $data['password'];

    if(! preg_match('/^[a-f0-9]{32}$/', $password)) {
        $password = md5($password);
    }

    $user = UserDAO::get_user_by_standard_auth($data['username'], $password);

    if(!is_null($user)) {
        session_start();
        $_SESSION['user'] = serialize($user);

        return json($user);
    }
    else {
        return halt(401, "", "");
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
    redirect_to('/');
}

?>
