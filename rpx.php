<?php
require_once('src/models/user.php');
require_once('src/dao/user_dao.php');
require_once('src/utils/config.php');
require_once('src/utils/helpers.php');

Config::configure();

$rpxApiKey = '6af1713bce4897a0067343c5da898e1dccb6862d';  

if(isset($_POST['token'])) { 

    /* STEP 1: Extract token POST parameter */
    $token = $_POST['token'];
  
    /* STEP 2: Use the token to make the auth_info API call */
    $post_data = array(
        'token' => $_POST['token'], 'apiKey' => $rpxApiKey,
        'format' => 'json'); 
  
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, 'https://rpxnow.com/api/v2/auth_info');
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $raw_json = curl_exec($curl);
    curl_close($curl);
  
  
    /* STEP 3: Parse the JSON auth_info response */
    $auth_info = json_decode($raw_json, true);

    if ($auth_info['stat'] == 'ok') {
    
        /* STEP 3 Continued: Extract the 'identifier' from the response */
        $profile = $auth_info['profile'];
        $identifier = $profile['identifier'];
    
        if (isset($profile['photo']))  {
            $photo_url = $profile['photo'];
        }
    
        if (isset($profile['displayName']))  {
            $name = $profile['displayName'];
        }
    
        if (isset($profile['email']))  {
            $email = $profile['email'];
        }
    
        $user = UserDAO::get_user_by_identifier($identifier);
    
        if (is_null($user)) {
            // This should happen in another step where we forward the user to a 
            // page where they are asked to create an account on our system.
            $user = new User();
            $user->set_username($name);
            $user->set_email($email);
            $user->set_identifier($identifier);
            $user->set_photo_url($photo_url);
    
            $user_id = UserDao::save($user);
            $user->set_id($user_id);
        }

        session_name(option('session'));
        if (session_start()) {
            $_SESSION['user'] = serialize($user);
        } else {
            debug('Session could not be started');
        }
    } else {
        /* an error occurred */
        // gracefully handle the error.  Hook this into your native error 
        // handling system.
        debug('ERROR in RPX', $auth_info['err']['msg']);
        halt(500);
    }
}
redirect_to('/');
?>
