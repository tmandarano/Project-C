<?php
require_once('baseController.php');
require_once('localhost/dao/user_dao.php');

class SessionsController extends BaseController {       
    public function show($vars) {
        $session_id = $vars[':id'];
        $sessions = UserDAO::getSessions($sessions_id);
        RestUtils::sendResponse(200, json_encode($sessions), 'application/json');
    }
	
    public function create() {
    	$data = RestUtils::processRequest();
    	$vars = $data->getRequestVars();

        $users = UserDAO::getUserByStandardAuth($vars['username'], $vars['password']);

        if(!is_null($users) && count($users) > 0) {
            $user = current($users);

            session_start();
            $_SESSION['username'] = $user->getUsername();
            $_SESSION['email'] = $user->getEmail();

            RestUtils::sendResponse(200, json_encode($user), 'application/json');
        }
        else {
            RestUtils::sendResponse(401, 'User is unauthorized', 'text/html');
        }
    }
}

?>
