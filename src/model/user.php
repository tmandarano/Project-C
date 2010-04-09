<?php
class User
{
	public $id;
	public $first_name;
	public $last_name;
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

    public function getFirstName()
    {
        return $this->first_name;    	
    }
    
    public function setFirstName($first_name)
	{
	    $this->first_name = $first_name;
	}
	
    public function getLastName()
    {
        return $this->last_name;      
    }
    
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
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