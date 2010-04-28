<?php
class Tag
{
	public $id;
	public $tag;
    public $dataAdded;
    public $dateModified;
	
	public function getId()
	{
		return $this->id;
	}
	
    public function setId($id)
    {
        $this->id = $id;
    }
	
    public function getTag()
    {
        return $this->tag;
    }
    
    public function setTag($tag)
	{
	    $this->tag = $tag;
	}

    public function getDateAdded()
    {
        return $this->dateAdded;
    }
    
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;
    }

    public function getDateModified()
    {
        return $this->dateModified;
    }
    
    public function setDateModified($dateModified)
    {
        $this->dateModified = $dateModified;
    }
}
?>