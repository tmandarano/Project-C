<?php
require_once('baseController.php');
require_once('src/model/user.php');
require_once('src/model/dao/user_dao.php');

class UsersController extends BaseController
{       
    public function show($vars)
    {
        $user_id = $vars[':id'];
        $users = UserDAO::getUsers($user_id);
        RestUtils::sendResponse(200, json_encode($users), 'application/json');
    }
	
    public function create()
    {
    	$data = RestUtils::processRequest();
    	$vars = $data->getRequestVars();
        
        $user = new User();

        $user->setFullname($vars['fullname']);
        $user->setEmail($vars['email']);
        $user->setPassword(md5($vars['password']));
        $user->setDateOfBirth($vars['date_of_birth']);
        
        $returned_id = UserDAO::save($user);
        $user->setId($returned_id);

        RestUtils::sendResponse(200, json_encode($user), 'application/json');
    }
    
    public function edit($user)
    {
    	
    }
    
    public function delete()
    {
    	
    }
}

?>
