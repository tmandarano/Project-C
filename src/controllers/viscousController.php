<?php
require_once('baseController.php');

/* Viscous represents the semi-dynamic, semi-static nature of these pages. */
class ViscousController extends baseController {       
  /* Home pages */
  public function home() {
    if ($user/* or true*/) { // TODO This needs to be auth
      $this->assign('title', '');
      $this->assign('class', 'home stream');
      RestUtils::sendResponse(200, $this->fetch('signedin.tpl'));
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
    $mostRecent = 451;
    $recentPhotos = array(452, 453, 451);

    $this->assign('user', $user);
    $this->assign('similarPeople', $similarPeople);
    $this->assign('tags', $tags);
    $this->assign('mostRecent', $mostRecent);
    $this->assign('recentPhotos', $recentPhotos);
    $this->assign('title', $user['name']);
    $this->assign('class', 'users profile'); // TODO change to match route
    RestUtils::sendResponse(200, $this->fetch('users_profile.tpl'));
  }

  public function settings() {
    $this->assign('title', 'Settings');
    $this->assign('class', 'users settings'); // TODO change to match route
    RestUtils::sendResponse(200, $this->fetch('settings.tpl'));
  }

  /* Photos pages */
  public function photo() {
    header('Location: /img/270x270.jpg');
    exit;
  }

  public function photos_view() {
    // TODO get photo data and related photo data.
    $this->assign('title', 'Photo');
    $this->assign('class', 'photos view');
    RestUtils::sendResponse(200, $this->fetch('photos_view.tpl'));
  }
}
?>
