<?php
require_once('base_controller.php');
require_once('src/utils/helpers.php');

// Get one master copy of the session user if it exists.
function get_session_user() {
    return (empty($_SESSION['user'])) ? null : unserialize($_SESSION['user']);
}
function get_session_user_info($user) {
    if ($user) {
        return array(
            'id' => $user->get_id(),
            'name' => $user->get_username()
        );
    }
    return array();
}

function home() {
    check_system_auth();
    $user = get_session_user();

    $template = new BaseController();

    if ($user) {
        $streamPhotos = array(451, 452, 453);
        $socialStream = array(
          array('user' => 1, 'action' => 'commented on', 'actionee' => 2,
                'photo' => 1),
          array('user' => 1, 'action' => 'commented on', 'actionee' => 3,
                'photo' => 451, 'descriptor' => 'nice'),
          array('user' => 1, 'action' => 'tagged', 'actionee' => 2,
                'photo' => 451, 'descriptor' => 'cute')
        );
        $trending = array('party', 'baseball', 'seahawks', 'car', 'funny', 'lunch');
        $suggestedPhotos = array(451, 451, 451, 451, 451,
                                 452, 452, 452, 452, 452);
        $suggestedPeople = array(1, 1, 1, 1, 1,
                                 1, 1, 1, 1, 1);
        $template->assign(array('title' => '', 'class' => 'livestreams'));
        $template->assign(array(
            'user' => get_session_user_info($user), 
            'stream' => $streamPhotos,
            'social' => $socialStream,
            'trending' => $trending,
            'suggestedPhotos' => $suggestedPhotos,
            'suggestedPeople' => $suggestedPeople));
        return html($template->fetch('live_streams.tpl'));
    } else {
        $template->assign(array('title'=>'', 'class'=>'home out'));
        return html($template->fetch('home.tpl'));
    }
}

function getapp() {
    $template = new BaseController();
    $template->assign(array('title'=>'Download App', 'class'=>'getapp'));
    $template->assign(array('user' => $user_info));
    return html($template->fetch('getapp.tpl'));
}

/* About pages */
function about_contact() {
    $template = new BaseController();
    $template->assign(array('title'=>'Contact | About', 'class'=>'about contact'));
    return html($template->fetch('about_contact.tpl'));
}
    
function about_faq() {
    $template = new BaseController();
    $template->assign(array('title'=>'FAQ | About', 'class'=>'about faq'));
    return html($template->fetch('about_faq.tpl'));
}

/* Explore pages */
function explore_map() {
    $template = new BaseController();
    $template->assign(array('title'=>'Map | Explore', 'class'=>'explore map'));
    return html($template->fetch('explore_map.tpl'));
}

function explore_photos() {
    $template = new BaseController();
    $popCities = array('San Diego', 'Seattle', 'New York', 'Los Angeles', 'Miami');
    $trending = array('party', 'baseball', 'seahawks', 'car', 'funny', 'happy');
    $suggestedPhotos = array(451, 452, 453, 452, 453,
                             452, 451, 452, 453, 452);
    $suggestedPeople = array(99, 99, 99, 99, 99,
                             99, 99, 99, 99, 99);
    $template->assign(array(
        'popCities'=>$popCities,
        'trending'=>$trending,
        'suggestedPhotos'=>$suggestedPhotos,
        'suggestedPeople'=>$suggestedPeople));
    $template->assign(array('title'=>'Photos | Explore', 'class'=>'explore photos'));
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
    check_system_auth();

    $template = new BaseController();
    $template->assign(array('title'=>'Upload | Share', 'class'=>'share upload'));
    return html($template->fetch('share_upload.tpl'));
}

function share_mobile() {
    $template = new BaseController();
    $template->assign(array('title'=>'Mobile | Share', 'class'=>'share mobile'));
    return html($template->fetch('share_mobile.tpl'));
}

function share_webcam() {
    check_system_auth();

    $template = new BaseController();
    $template->assign(array('title'=>'Webcam | Share', 'class'=>'share webcam'));
    return html($template->fetch('share_webcam.tpl'));
}

function profile() {
    $template = new BaseController();
    $user_id = intval(filter_var(params('id'), FILTER_VALIDATE_INT));
    $user = UserDAO::get_user_by_id($user_id);
    $similarPeople = array(1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
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
      'title' => $user->get_username(),
      'class' => 'profile'));
    return html($template->fetch('users_profile.tpl'));
}

function settings() {
    check_system_auth();

    $template = new BaseController();
    $template->assign(array('title'=>'Settings', 'class'=>'users settings'));
    return html($template->fetch('settings.tpl'));
}

/* Photos pages */

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
    $template->assign(array('title'=>'Photo', 'class'=>'photos view'));
    return html($template->fetch('photos_view.tpl'));
}
?>
