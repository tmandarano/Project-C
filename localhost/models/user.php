<?php
class User
{
	public $id;
	public $fullname;
	public $email;
	public $password;
    public $date_of_birth;
    public $location;
	public $date_added;
	public $date_modified;
	
	public function getId()
	{
		return $this->id;
	}
	
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getFullname()
    {
        return $this->fullname;    	
    }
    
    public function setFullname($fullname)
	{
	    $this->fullname = $fullname;
	}
	
    public function setEmail($email)
	{
		$this->email = $email;
	}

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }
    
    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getDateOfBirth()
    {
        return $this->date_of_birth;
    }
    
    public function setDateOfBirth($date_of_birth)
    {
        $this->date_of_birth = $date_of_birth;
    }

    public function getLocation()
    {
        return $this->location;
    }
    
    public function setLocation($location)
    {
        $this->location = $location;
    }

    public function getDateAdded()
    {
        return $this->date_added;
    }
    
    public function setDateAdded($dateAdded)
    {
        $this->date_added = $date_added;
    }

    public function getDateModified()
    {
        return $this->date_modified;
    }
    
    public function setDateModified($dateModified)
    {
        $this->date_modified = $dateModified;
    }
}
?>