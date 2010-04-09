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
        
        $sql = "INSERT INTO user (first_name, last_name, email, password, date_of_birth, location, date_added, date_modified) ";
        $sql .= "VALUES (:first_name, :last_name, :email, :password, :date_of_birth, :location, NOW(), NOW())";
        $stmt = $conn->prepare($sql);
        debug(serialize($user));
        $stmt->bindParam(":first_name", $user->getFirstName(), PDO::PARAM_STR);
        $stmt->bindParam(":last_name", $user->getLastName(), PDO::PARAM_STR);
        $stmt->bindParam(":email", $user->getEmail(), PDO::PARAM_STR);
        $stmt->bindParam(":password", $user->getPassword(), PDO::PARAM_STR);
        $stmt->bindParam(":date_of_birth", $user->getDateOfBirth(), PDO::PARAM_STR);
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
            $user->setFirstName($record['first_name']);
            $user->setLastName($record['last_name']);
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
