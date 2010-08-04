<?php

/**
 * @internal
 * @property integer $id The id of the photo.
 * @property integer $user_id The id of the user in the LiveGather system to whom this photo belongs.
 * @property string $url The URI path to the photo.
 * @property float $latitude The latitude where the photo was taken.
 * @property float $longitude The longitude where the photo was taken.
 * @property point $geolocation The geolocation of the photo.
 * @property string $caption The caption given to the photo.
 * @property string $status The status of the photo as defined by the business layer. "ACTIVE" and "INACTIVE" are the two values in use.
 * @property array $tags An array that contains the tags applicable to this photo.
 * @property time $date_added The date the objected was added to the system.
 * @property time $date_modified The date the object was last modified in the system.
 */
class Photo {
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_INACTIVE = 'INACTIVE';

    public $id;
    public $user_id;
    public $url;
    public $name;
    public $latitude;
    public $longitude;
    public $geolocation;
    public $caption;
    public $status;
    public $tags;
    public $data_added;
    public $date_modified;
    
    public function get_id() {
        return $this->id;
    }

    public function set_id($id) {
        $this->id = $id;
    }

    public function get_user_id() {
        return $this->user_id;
    }

    public function set_user_id($user_id) {
        $this->user_id = $user_id;
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

    public function set_status($status) {
        $this->status = $status;
    }

    public function get_status() {
      return $this->status;
    }

    public function get_tags() {
        return $this->tags;
    }
    
    public function set_tags($tags) {
        $this->tags = $tags;
    }

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
