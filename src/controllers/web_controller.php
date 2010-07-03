<?php
require_once('lib/smarty/libs/Smarty.class.php');
require_once('src/utils/helpers.php');
require_once('src/dao/photo_dao.php');
require_once('src/dao/user_dao.php');

/* Template extends Smarty to get the Smarty templating with special 
 * settings. */
class Template extends Smarty {
    function __construct() {
        $this->Smarty();
        $this->template_dir = 'src/views/';
        $this->compile_dir = 'special/smarty_pants/compiled';
        $this->config_dir = 'special/smarty_pants/config';
        $this->cache_dir = 'special/smarty_pants/cache';
        $this->plugins_dir[] = 'special/smarty_pants/plugins';
    }

    function fetch($template) {
        $this->assign('content', $template);
        return parent::fetch('base.tpl');
    }

    function untemplated_fetch($template) {
        return parent::fetch($template);
    }
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

function eye () {
    $template = new Template();
    $template->assign(array('title'=>'', 'class'=>'eye'));
    $template->assign(array('user' => get_session_user_info(get_session_user())));
    return html($template->fetch('eye.tpl'));
}

function home() {
    check_system_auth();
    $user = get_session_user();

    $template = new Template();

    if ($user) {
        $streamPhotos = implode(', ', array(101, 102, 103));
        $socialStream = array(
          array('user' => 1, 'action' => 'commented on', 'actionee' => 2,
                'photo' => 1),
          array('user' => 1, 'action' => 'commented on', 'actionee' => 3,
                'photo' => 451, 'descriptor' => 'nice'),
          array('user' => 1, 'action' => 'tagged', 'actionee' => 2,
                'photo' => 451, 'descriptor' => 'cute')
        );
        $trending = array('party', 'baseball', 'seahawks', 'car', 'funny', 'lunch');
        $suggestedPhotos = implode(', ', array(451, 451, 451, 451, 451, 452, 452, 452, 452, 452));
        $suggestedPeople = implode(', ', array(1, 1, 1, 1, 1, 1, 1, 1, 1, 1));
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

/* About pages */
function about_contact() {
    $user = get_session_user();

    $template = new Template();
    $template->assign(array('title'=>'Contact | About', 'class'=>'about contact'));
    $template->assign(array('user' => get_session_user_info($user))); 
    return html($template->fetch('about_contact.tpl'));
}
    
function about_faq() {
    $user = get_session_user();
    $template = new Template();
    $template->assign(array('title'=>'FAQ | About', 'class'=>'about faq'));
    $template->assign(array('user' => get_session_user_info($user))); 
    return html($template->fetch('about_faq.tpl'));
}

/* Explore pages */
function explore_map() {
    $user = get_session_user();
    $template = new Template();
    $template->assign(array('title'=>'Map | Explore', 'class'=>'explore map'));
    $template->assign(array('user' => get_session_user_info($user))); 
    return html($template->fetch('explore_map.tpl'));
}

function explore_photos() {
    $user = get_session_user();
    $template = new Template();
    $popCities = array('San Diego', 'Seattle', 'New York', 'Los Angeles', 'Miami');
    $trending = array('party', 'baseball', 'seahawks', 'car', 'funny', 'happy');
    $suggestedPhotos = array(451, 452, 453, 452, 453, 452, 451, 452, 453, 452);
    $suggestedPeople = array(99, 99, 99, 99, 99, 99, 99, 99, 99, 99);
    $template->assign(array(
        'popCities'=>$popCities,
        'trending'=>$trending,
        'suggestedPhotos'=>$suggestedPhotos,
        'suggestedPeople'=>$suggestedPeople));
    $template->assign(array('title'=>'Photos | Explore', 'class'=>'explore photos'));
    $template->assign(array('user' => get_session_user_info($user))); 
    return html($template->fetch('explore_photos.tpl'));
}

//function explore_people() {
//    $template = new Template();
//    $template->assign('title', 'People | Explore');
//    $template->assign('class', 'explore people');
//    return html($template->fetch('explore_people.tpl'));
//}

/* Share pages */
function share_upload() {
    check_system_auth();
    $template = new Template();
    $template->assign(array('title'=>'Upload | Share', 'class'=>'share upload'));
    $template->assign(array('user' => get_session_user_info(get_session_user())));
    return html($template->fetch('share_upload.tpl'));
}

function share_mobile() {
    $user = get_session_user();
    $template = new Template();
    $template->assign(array('title'=>'Download App | Mobile | Share', 'class'=>'getapp'));
    $template->assign(array('user' => get_session_user_info($user))); 
    return html($template->fetch('getapp.tpl'));
}
function getapp() {
    $user = get_session_user();
    $template = new Template();
    $template->assign(array('title'=>'Download App | Mobile | Share', 'class'=>'getapp'));
    $template->assign(array('user' => get_session_user_info($user))); 
    return html($template->fetch('getapp.tpl'));
}

function share_webcam() {
    check_system_auth();
    $user = get_session_user();

    $template = new Template();
    $template->assign(array('title'=>'Webcam | Share', 'class'=>'share webcam'));
    $template->assign(array('user' => get_session_user_info($user)));
    return html($template->fetch('share_webcam.tpl'));
}

function profile() {
    $user_id = var_to_i(params('id'));
    $profile_user = UserDAO::get_user_by_id($user_id);
    $similarPeople = UserDAO::get_users_similar($user_id, 10);

    $recentPhotos = PhotoDAO::get_photos_by_user_id($user_id, 4);
    $template = new Template();
    $template->assign(array(
      'profile_user' => json_encode($profile_user),
      'similarPeople' => json_encode($similarPeople),
      'tags' => json_encode(TagDAO::get_tags_by_user_id($user_id)),
      'recentPhotos' => json_encode($recentPhotos),
      'title' => $profile_user ? $profile_user->get_username() : '',
      'class' => 'profile'));
    $template->assign(array('user' => get_session_user_info(get_session_user())));
    return html($template->fetch('profile.tpl'));
}

function settings() {
    check_system_auth();
    $user = get_session_user();

    if (!$user) {
        halt(401);
    }

    $template = new Template();
    $template->assign(array('title'=>'Settings', 'class'=>'users settings'));
    $template->assign(array('user' => get_session_user_info($user)));
    return html($template->fetch('settings.tpl'));
}

/* Photos pages */

function photos_view_by_id() {
    $user = get_session_user();
    $template = new Template();
    $photo_id = var_to_i(params('id'));
    $profileuser = array('id' => 1, 'name' => 'jonnyApple', 'location' => 'San Diego, CA',
                  'datetime' => '33 minutes ago');
    $photo = array('id' => 453, 'location' => 'San Diego, CA',
      'datetime' => '33 minutes ago',
      'caption' => 'Chillin out and watching some TV',
      'tags' => array('party' => 10, 'cars' => 7, 'college' => 13, 'wedding' => 6,
                      'concert' => 8, 'fishing' => 6));
    $nearbyPhotos = array(1, 2, 3, 1, 2, 3, 1, 2, 3, 1);
    $similarPhotos = array(451, 452, 453, 451, 452, 453, 451, 452, 453, 451);
    $prevPhotoId = 453;
    $nextPhotoId = 453;
    $template->assign(array(
      'profileuser' => $profileuser,
      'photo' => $photo,
      'nearbyPhotos' => $nearbyPhotos,
      'similarPhotos' => $similarPhotos,
      'prevPhotoId' => $prevPhotoId,
      'nextPhotoId' => $nextPhotoId));
    $template->assign(array('title'=>'Photo', 'class'=>'photos view'));
    $template->assign(array('user' => get_session_user_info($user)));
    return html($template->fetch('photos_view.tpl'));
}

function privacy() {
    $template = new Template();
    $template->assign(array('title'=>'Privacy', 'class'=>'privacy'));
    $template->assign(array('user' => get_session_user_info(get_session_user())));
    return html($template->fetch('privacy.tpl'));
}

function tos() {
    $template = new Template();
    $template->assign(array('title'=>'Terms of Service', 'class'=>'tos'));
    $template->assign(array('user' => get_session_user_info(get_session_user())));
    return html($template->fetch('tos.tpl'));
}

function team() {
    $template = new Template();
    $template->assign(array('title'=>'LiveGather Team', 'class'=>'team'));
    $template->assign(array('user' => get_session_user_info(get_session_user())));
    return html($template->fetch('team.tpl'));
}

?>
