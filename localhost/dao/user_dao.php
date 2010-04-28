<?php
require_once('src/model/user.php');
require_once('src/utils/connection_factory.php');

class UserDAO
{
    public static function getUsers($id = null)
    {
        $conn = ConnectionFactory::getFactory()->getConnection();
    
        $sql = "SELECT * FROM user";
        if($id)
        {
            $sql .= " WHERE id = ". $id;
        }
    
        $rs = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        return PhotoDAO::recordsToPhotos($rs);
    }

    public function save($user)
    {
        $conn = ConnectionFactory::getFactory()->getConnection();
        
        $sql = "INSERT INTO user (fullname, email, password, date_of_birth, location, date_added, date_modified) ";
        $sql .= "VALUES (:fullname, :email, :password, :date_of_birth, :location, NOW(), NOW())";
        $stmt = $conn->prepare($sql);

        $birthday = date("Y-m-d H:i:s", strtotime($user->getDateOfBirth())); 
        
        $stmt->bindParam(":fullname", $user->getFullname(), PDO::PARAM_STR);
        $stmt->bindParam(":email", $user->getEmail(), PDO::PARAM_STR);
        $stmt->bindParam(":password", $user->getPassword(), PDO::PARAM_STR);
        $stmt->bindParam(":date_of_birth", $birthday, PDO::PARAM_STR);
        $stmt->bindParam(":location", $user->getLocation(), PDO::PARAM_STR);
        $stmt->execute();
        $user_id = $conn->lastInsertId();
        $user->setId($user_id); 
        
        return $user_id;
    }
	
    public function update($user)
    {
        $conn = ConnectionFactory::getFactory()->getConnection();
    
        $sql = "UPDATE user set first_name = :, date_modified = NOW() where id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":url", $photo->getUrl(), PDO::PARAM_STR);
        $stmt->bindParam(":id", $photo->getId(), PDO::PARAM_INT);
    
        if($stmt->execute())
            debug('success');
        else {
            debug($conn->errorCode());
            debug('failure');
        }
    }

    private static function recordsToUsers($records) 
    {
        $users = array();
        foreach($records as $record) {
            $user = new User();
            $user->setId($record['id']);
            $user->setFullname($record['fullname']);
            $user->setEmail($record['email']);
            $user->setPassword($record['password']);
            $user->setDateOfBirth($record['date_of_birth']);
            $user->setLocation($record['location']);
            $user->setDateAdded($record['date_added']);
            $user->setDateModified($record['date_modified']);
            
            $users[$record['id']] = $user;
        }
        return $users;
    }
}
?>
