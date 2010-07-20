<?php
require_once('src/models/user.php');
require_once('src/dao/user_dao.php');
require_once('src/utils/config.php');
require_once('src/utils/helpers.php');

Config::configure();

function janrain_post($post_data, $method) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, 'https://rpxnow.com/api/v2/'.$method);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $raw_json = curl_exec($curl);
    curl_close($curl);
  
    $response = json_decode($raw_json, true);

    if ($response['stat'] == 'ok') {
        $profile = $response['profile'];
        return $profile;
    } else {
        /* an error occurred */
        // gracefully handle the error.  Hook this into your native error 
        // handling system.
        debug('ERROR in RPX', $response['err']['msg']);
        halt(500);
    }            
}          




function create_local_user($profile) {
    $user = new User();
    if (isset($profile['displayName'])) { 
        $user->set_username($profile['displayName']);
    }
    if (isset($profile['email'])) { 
        $user->set_email($profile['email']);
    }
    if (isset($profile['photo'])) {     
        $user->set_photo_url($profile['photo']);
    }

    $user_id = UserDao::save($user);
    $user->set_id($user_id);

    return $user;
}


function janrain_map($identifier, $primaryKey, $rpxApiKey) {
    $post_data = array('apiKey' => $rpxApiKey, 
                       'identifier' => $identifier,
                       'primaryKey' => $primaryKey,
                       'overwrite'  => true,
                       'format' => 'json'); 
  
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, 'https://rpxnow.com/api/v2/map');
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $raw_json = curl_exec($curl);
    curl_close($curl);
  
    $auth_info = json_decode($raw_json, true);
    
    if ($auth_info['stat'] == 'ok') {
        return true;
    } else {
        /* an error occurred */
        // gracefully handle the error.  Hook this into your native error 
        // handling system.
        debug('ERROR in RPX', $auth_info['err']['msg']);
        halt(500);
    }
}


function janrain_unmap($profile, $primaryKey, $rpxApiKey) {
    $post_data = array('apiKey' => $rpxApiKey, 
                       'identifier' => $profile['identifier'],
                       'primaryKey' => $primaryKey,
                       'format' => 'json'); 
  
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, 'https://rpxnow.com/api/v2/unmap');
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $raw_json = curl_exec($curl);
    curl_close($curl);
  
    $auth_info = json_decode($raw_json, true);
    
    if ($auth_info['stat'] == 'ok') {
        return true;
    } else {
        /* an error occurred */
        // gracefully handle the error.  Hook this into your native error 
        // handling system.
        debug('ERROR in RPX', $auth_info['err']['msg']);
        halt(500);
    }
}
?>
