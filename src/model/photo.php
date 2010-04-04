<?php
class Photo
{
	public $id;
	public $url;
	public $name;
	public $location;
	public $comments;
	public $tags;
	public $data_added;
	public $date_modified;
	
	public function getId()
	{
		return $this->id;
	}
	
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getUrl()
    {
        return $this->url;    	
    }
    
    public function setUrl($url)
	{
	    $this->url = $url;
	}
	
    public function getName()
    {
        return $this->name;      
    }
    
    public function setName($name)
    {
        $this->name = $name;
    }
	
    public function setLocation($location)
	{
		$this->location = $location;
	}

    public function getLocation()
    {
        return $this->location;
    }

    public function getTags()
    {
        return $this->tags;
    }
    
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    public function getComments()
    {
        return $this->comments;
    }
    
    public function setComments($comments)
    {
        $this->comments = $comments;
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