<?php
class Photo {
    public $user_id;
	public $id;
	public $url;
	public $name;
	public $latitude;
	public $longitude;
    public $geolocation;
    public $caption;
    //	public $comments;
	public $tags;
	public $data_added;
	public $date_modified;
	
	public function get_user_id() {
		return $this->user_id;
	}
	
    public function set_user_id($user_id) {
        $this->user_id = $user_id;
    }

	public function get_id() {
		return $this->id;
	}
	
    public function set_id($id) {
        $this->id = $id;
    }

    public function get_url() {
        return $this->url;    	
    }
    
    public function set_url($url) {
	    $this->url = $url;
	}
	
    public function get_name() {
        return $this->name;      
    }
    
    public function set_name($name) {
        $this->name = $name;
    }
	
    public function set_latitude($latitude) {
		$this->latitude = $latitude;
	}

    public function get_latitude() {
        return $this->latitude;
    }

    public function set_longitude($longitude) {
		$this->longitude = $longitude;
	}

    public function get_longitude() {
        return $this->longitude;
    }

    public function set_geolocation($geolocation) {
		$this->geolocation = $geolocation;
	}

    public function get_geolocation() {
        return $this->geolocation;
    }

    public function set_caption($caption) {
        $this->caption = $caption;
    }

    public function get_caption() {
      return $this->caption;
    }

    public function get_tags() {
        return $this->tags;
    }
    
    public function set_tags($tags) {
        $this->tags = $tags;
    }
    /*
    public function get_comments() {
        return $this->comments;
    }
    
    public function set_comments($comments) {
        $this->comments = $comments;
    }
    */
    public function get_date_added() {
        return $this->date_added;
    }
    
    public function set_date_added($date_added) {
        $this->date_added = $date_added;
    }

    public function get_date_modified() {
        return $this->date_modified;
    }
    
    public function set_date_modified($date_modified) {
        $this->date_modified = $date_modified;
    }
}
?>
