<?php
/* Viscous represents the semi-dynamic, semi-static nature of these pages. */
require_once('baseController.php');

function home() {
    $template = new BaseController();
    if ($user) { // TODO template needs to be auth
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
      $template->assign('title', '');
      $template->assign('class', 'livestreams');
      $template->assign('stream', $streamPhotos);
      $template->assign('social', $socialStream);
      $template->assign('suggestedPhotos', $suggestedPhotos);
      $template->assign('suggestedPeople', $suggestedPeople);
      return html($template->fetch('live_streams.tpl'));
    } else {
      $template->assign('title', '');
      $template->assign('class', 'home out');
      return html($template->fetch('home.tpl'));
    }
}

function getapp() {
    $template = new BaseController();
    $template->assign('title', 'Download App');
    $template->assign('class', 'getapp');
    return html($template->fetch('getapp.tpl'));
}

/* About pages */
function about_contact() {
    $template = new BaseController();
    $template->assign('title', 'Contact | About');
    $template->assign('class', 'about contact');
    return html($template->fetch('about_contact.tpl'));
}
    
function about_faq() {
    $template = new BaseController();
    $template->assign('title', 'FAQ | About');
    $template->assign('class', 'about faq');
    return html($template->fetch('about_faq.tpl'));
}

/* Explore pages */
function explore_map() {
    $template = new BaseController();
    $template->assign('title', 'Map | Explore');
    $template->assign('class', 'explore map');
    return html($template->fetch('explore_map.tpl'));
}

function explore_photos() {
    $template = new BaseController();
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
    $template->assign('popCities', $popCities);
    $template->assign('trending', $trending);
    $template->assign('suggestedPhotos', $suggestedPhotos);
    $template->assign('suggestedPeople', $suggestedPeople);
    $template->assign('title', 'Photos | Explore');
    $template->assign('class', 'explore photos');
    return html($template->fetch('explore_photos.tpl'));
}

//function explore_people() {
//  $template = new BaseController();
//  $template->assign('title', 'People | Explore');
//  $template->assign('class', 'explore people');
//  return html($template->fetch('explore_people.tpl'));
//}

/* Share pages */
function share_upload() {
    $template = new BaseController();
    $template->assign('title', 'Upload | Share');
    $template->assign('class', 'share upload');
    return html($template->fetch('share_upload.tpl'));
}

function share_mobile() {
    $template = new BaseController();
    $template->assign('title', 'Mobile | Share');
    $template->assign('class', 'share mobile');
    return html($template->fetch('share_mobile.tpl'));
}

function share_webcam() {
    $template = new BaseController();
    $template->assign('title', 'Webcam | Share');
    $template->assign('class', 'share webcam');
    return html($template->fetch('share_webcam.tpl'));
}

function profile() {
    $template = new BaseController();
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

    $template->assign('user', $user);
    $template->assign('similarPeople', $similarPeople);
    $template->assign('tags', $tags);
    $template->assign('mostRecent', $mostRecent);
    $template->assign('recentPhotos', $recentPhotos);
    $template->assign('title', $user['name']);
    $template->assign('class', 'profile');
    return html($template->fetch('users_profile.tpl'));
}

function settings() {
    $template = new BaseController();
    $template->assign('title', 'Settings');
    $template->assign('class', 'users settings'); // TODO change to match route
    return html($template->fetch('settings.tpl'));
}

/* Photos pages */
function photo_by_size() {
    switch (params('size')) {
    case 0: header('Location: /img/30x30.jpg'); exit;
    case 1: header('Location: /img/50x50.jpg'); exit;
    case 2: header('Location: /img/50x50.jpg'); exit;
    case 3: header('Location: /img/270x270.jpg'); exit;
    case 'o': header('Location: /img/270x270.jpg'); exit;
    }
}
function photo() {
    params('size', 'o');
    return photo_by_size();
}

function photos_view_by_id() {
    $template = new BaseController();
    // TODO get photo data and related photo data.
    $user = array('id' => 1, 'name' => 'jonnyApple', 'location' => 'San Diego, CA',
                  'datetime' => '33 minutes ago');
    $photo = array('id' => 453, 'location' => 'San Diego, CA',
      'datetime' => '33 minutes ago',
      'caption' => 'Chillin out and watching some TV',
      'tags' => array('party' => 10, 'cars' => 7, 'college' => 13, 'wedding' => 6,
                      'concert' => 8, 'fishing' => 6));
    $nearbyPhotos = array(
      array('id'=>1), array('id'=>2), array('id'=>3),
      array('id'=>1), array('id'=>2), array('id'=>3),
      array('id'=>2), array('id'=>3), array('id'=>2), array('id'=>3)
    );
    $similarPhotos = array(
      array('id'=>451), array('id'=>452), array('id'=>453),
      array('id'=>452), array('id'=>453), array('id'=>451),
      array('id'=>452), array('id'=>453), array('id'=>452), array('id'=>453)
    );
    $prevPhotoId = 453;
    $nextPhotoId = 453;
    $template->assign('user', $user);
    $template->assign('photo', $photo);
    $template->assign('nearbyPhotos', $nearbyPhotos);
    $template->assign('similarPhotos', $similarPhotos);
    $template->assign('prevPhotoId', $prevPhotoId);
    $template->assign('nextPhotoId', $nextPhotoId);
    $template->assign('title', 'Photo');
    $template->assign('class', 'photos view');
    return html($template->fetch('photos_view.tpl'));
}

?>
