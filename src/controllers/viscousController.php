<?php
require_once('baseController.php');

/* Viscous represents the semi-dynamic, semi-static nature of these pages. */
class ViscousController extends baseController {       
  /* Home pages */
  public function home() {
    if ($user) { // TODO This needs to be auth
    //if (true) {
      $streamPhotos = array(451, 452, 453);
      $socialStream = array(
        array('user' => array('name'=>'Tony Mandarano'),
              'action' => 'commented on',
              'actionee' => array('name' => 'John Last'),
              'photo' => array('id' => 451)),
        array('user' => array('name'=>'Tony Mandarano'),
              'action' => 'commented on',
              'actionee' => array('name' => 'John Last'),
              'photo' => array('id' => 451),
              'descriptor' => 'nice'),
        array('user' => array('name'=>'John Tamaguchi'),
              'action' => 'tagged',
              'actionee' => array('name' => 'Jessica Parker'),
              'photo' => array('id' => 451),
              'descriptor' => 'cute')
      );
      $suggestedPhotos = array(
        array('id'=>451), array('id'=>452), array('id'=>453),
        array('id'=>452), array('id'=>453), array('id'=>451),
        array('id'=>452), array('id'=>453), array('id'=>451), array('id'=>452)
      );
      $suggestedPeople = array(
        array('id'=>1), array('id'=>2), array('id'=>3),
        array('id'=>1), array('id'=>2), array('id'=>3),
        array('id'=>2), array('id'=>3), array('id'=>1), array('id'=>2)
      );
      $this->assign('title', '');
      $this->assign('class', 'livestreams');
      $this->assign('stream', $streamPhotos);
      $this->assign('social', $socialStream);
      $this->assign('suggestedPhotos', $suggestedPhotos);
      $this->assign('suggestedPeople', $suggestedPeople);
      RestUtils::sendResponse(200, $this->fetch('live_streams.tpl'));
    } else {
      $this->assign('title', '');
      $this->assign('class', 'home out');
      RestUtils::sendResponse(200, $this->fetch('home.tpl'));
    }
  }
      
  /* About pages */
  public function about_contact() {
    $this->assign('title', 'Contact | About');
    $this->assign('class', 'about contact');
    RestUtils::sendResponse(200, $this->fetch('about_contact.tpl'));
  }
      
  public function about_faq() {
    $this->assign('title', 'FAQ | About');
    $this->assign('class', 'about faq');
    RestUtils::sendResponse(200, $this->fetch('about_faq.tpl'));
  }

  /* Explore pages */
  public function explore_map() {
    $this->assign('title', 'Map | Explore');
    $this->assign('class', 'explore map');
    RestUtils::sendResponse(200, $this->fetch('explore_map.tpl'));
  }

  public function explore_photos() {
    $popCities = array('San Diego', 'Seattle', 'New York', 'Los Angeles', 'Miami');
    $trending = array('party', 'baseball', 'seahawks', 'car', 'funny', 'happy');
    $suggestedPhotos = array(
      array('id'=>451), array('id'=>452), array('id'=>453),
      array('id'=>452), array('id'=>453), array('id'=>451),
      array('id'=>452), array('id'=>453), array('id'=>452), array('id'=>453)
    );
    $suggestedPeople = array(
      array('id'=>1), array('id'=>2), array('id'=>3),
      array('id'=>1), array('id'=>2), array('id'=>3),
      array('id'=>2), array('id'=>3), array('id'=>2), array('id'=>3)
    );
    $this->assign('popCities', $popCities);
    $this->assign('trending', $trending);
    $this->assign('suggestedPhotos', $suggestedPhotos);
    $this->assign('suggestedPeople', $suggestedPeople);
    $this->assign('title', 'Photos | Explore');
    $this->assign('class', 'explore photos');
    RestUtils::sendResponse(200, $this->fetch('explore_photos.tpl'));
  }

  public function explore_people() {
    $this->assign('title', 'People | Explore');
    $this->assign('class', 'explore people');
    RestUtils::sendResponse(200, $this->fetch('explore_people.tpl'));
  }

  /* Share pages */
  public function share_index() {
    $this->assign('title', 'Share');
    $this->assign('class', 'share index');
    RestUtils::sendResponse(200, $this->fetch('share_index.tpl'));
  }

  public function share_upload() {
    $this->assign('title', 'Upload | Share');
    $this->assign('class', 'share upload');
    RestUtils::sendResponse(200, $this->fetch('share_upload.tpl'));
  }

  public function share_mobile() {
    $this->assign('title', 'Mobile | Share');
    $this->assign('class', 'share mobile');
    RestUtils::sendResponse(200, $this->fetch('share_mobile.tpl'));
  }

  public function share_webcam() {
    $this->assign('title', 'Webcam | Share');
    $this->assign('class', 'share webcam');
    RestUtils::sendResponse(200, $this->fetch('share_webcam.tpl'));
  }

  /* Users pages */
  public function signup() {
    $this->assign('title', 'Sign up');
    $this->assign('class', 'users add'); // TODO change to match route
    RestUtils::sendResponse(200, $this->fetch('signup.tpl'));
  }

  public function find_people() {
    RestUtils::sendResponse(503, "I don't know how to find unknown people yet.");
  }

  public function profile() {
    // TODO get the proper user's profile data
    $user = array('name' => 'Tony Mandarano',
      'location' => 'Seattle, WA',
      'occupation' => 'Student',
      'bio' => 'I love life!',
    );
    $similarPeople = array(
      array('id' => 1, 'name' => 'Other person 1'),
      array('id' => 1, 'name' => 'Other person 2'),
      array('id' => 1, 'name' => 'Other person 3'),
      array('id' => 1, 'name' => 'Other person 4'),
      array('id' => 1, 'name' => 'Other person 5'),
      array('id' => 1, 'name' => 'Other person 6'),
      array('id' => 1, 'name' => 'Other person 7'),
      array('id' => 1, 'name' => 'Other person 8')
    );
    $tags = array('party' => 10, 'cars' => 7, 'college' => 13, 'wedding' => 6,
      'concert' => 8, 'fishing' => 6);
    $mostRecent = array('id' => 451, 'caption' => 'Just getting to the party! Hope to have some fun watching the Seahawks', 'datetime' => '30 seconds ago', 'location' => 'Mercer Island, WA', 'lat' => 47.571, 'lng' => -122.221, 'tags' => array(array('id' => 1, 'tag' => 'party'), array('id' => 2, 'tag' => 'seahawks')), 'comments' => array());
    $recentPhotos = array(452, 453, 451);

    $this->assign('user', $user);
    $this->assign('similarPeople', $similarPeople);
    $this->assign('tags', $tags);
    $this->assign('mostRecent', $mostRecent);
    $this->assign('recentPhotos', $recentPhotos);
    $this->assign('title', $user['name']);
    $this->assign('class', 'profile');
    RestUtils::sendResponse(200, $this->fetch('users_profile.tpl'));
  }

  public function settings() {
    $this->assign('title', 'Settings');
    $this->assign('class', 'users settings'); // TODO change to match route
    RestUtils::sendResponse(200, $this->fetch('settings.tpl'));
  }

  /* Photos pages */
  public function photo($vars) {
    switch ($vars[':size']) {
    case 0: header('Location: /img/30x30.jpg'); exit;
    case 1: header('Location: /img/50x50.jpg'); exit;
    case 2: header('Location: /img/50x50.jpg'); exit;
    case 3: header('Location: /img/270x270.jpg'); exit;
    case 'o': header('Location: /img/270x270.jpg'); exit;
    }
  }

  public function photos_view() {
    // TODO get photo data and related photo data.
    $this->assign('title', 'Photo');
    $this->assign('class', 'photos view');
    RestUtils::sendResponse(200, $this->fetch('photos_view.tpl'));
  }
}
?>
