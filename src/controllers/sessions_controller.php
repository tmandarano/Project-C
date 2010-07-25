<?php
require_once('src/dao/user_dao.php');
require_once('src/utils/logging.php');
require_once('src/utils/helpers.php');
require_once('src/controllers/users_controller.php');

function __sessions_create($user) {
    session_start();
    $_SESSION['user'] = serialize($user);
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
