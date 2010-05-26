<?php
/* Viscous represents the semi-dynamic, semi-static nature of these pages. */
require_once('base_controller.php');
require_once('src/utils/helpers.php');

function home() {
    check_system_auth();

    $template = new BaseController();
    $user = (empty($_SESSION['user'])) ? null : unserialize($_SESSION['user']);
    if ($user) {
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
      $template->assign(array('title' => '', 'class' => 'livestreams', 'user' =>
                             array('id' => $user->get_id(), 'name' => $user->get_username())));
      $template->assign(array('stream' => $streamPhotos,
                              'social' => $socialStream,
                              'suggestedPhotos' => $suggestedPhotos,
                              'suggestedPeople' => $suggestedPeople));
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
//    $template = new BaseController();
//    $template->assign('title', 'People | Explore');
//    $template->assign('class', 'explore people');
//    return html($template->fetch('explore_people.tpl'));
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
    $user_id = intval(filter_var(params('id'), FILTER_VALIDATE_INT));
    $user = UserDAO::get_user_by_id($user_id);
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
    $recent_photos = PhotoDAO::get_recent_photos_by_user($user_id, 4);
    $mostRecent = array_shift($recent_photos);
    $recentPhotos = array();
    foreach ($recent_photos as $photo) {
        $recentPhotos[] = $recent_photos['id'];
    }

    $template->assign(array(
      'user' => $user,
      'similarPeople' => $similarPeople,
      'tags' => $tags,
      'mostRecent' => $mostRecent,
      'recentPhotos' => $recentPhotos,
      'title' => $user['name'],
      'class' => 'profile'));
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
    $photo_id = intval(filter_var(params('id'), FILTER_VALIDATE_INT));
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
    $template->assign(array(
      'user' => $user,
      'photo' => $photo,
      'nearbyPhotos' => $nearbyPhotos,
      'similarPhotos' => $similarPhotos,
      'prevPhotoId' => $prevPhotoId,
      'nextPhotoId' => $nextPhotoId));
    $template->assign('title', 'Photo');
    $template->assign('class', 'photos view');
    return html($template->fetch('photos_view.tpl'));
}

?>
