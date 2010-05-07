<?php
require_once('baseController.php');
require_once('localhost/dao/user_dao.php');

class SessionsController extends BaseController
{       
    public function show($vars)
    {
        $session_id = $vars[':id'];
        $sessions = UserDAO::getSessions($sessions_id);
        RestUtils::sendResponse(200, json_encode($sessions), 'application/json');
    }
	
    public function create()
    {
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            debug('zzzzzzzzzzzz');
            RestUtils::sendResponse(401, '', 'text/html');
        } else {
            debug("Hello {$_SERVER['PHP_AUTH_USER']}.");
            debug("You entered {$_SERVER['PHP_AUTH_PW']} as your password.");
        }

    	$data = RestUtils::processRequest();
    	$vars = $data->getRequestVars();
        
        $users = UserDAO::getUserByStandardAuth($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);

        if(len($users) > 0)
        {
            $user = $users[0];
        }

        session_start();
        $_SESSION['fullname'] = $user->getFullname();
        $_SESSION['email'] = $user->getEmail();

        RestUtils::sendResponse(200, json_encode($user), 'application/json');
    }
}

?>
