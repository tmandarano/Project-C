<?php
require_once('src/utils/config.php');
require_once('src/utils/helpers.php');

Config::configure();

/**
 * @internal
 * @param array $post_data This is an associative array that contains the fields to be posted to the Janrain service.
 * @param string $method This is the Janrain method to call. 
 *
 * @return array
 *
 * This function takes the data to be posted along with the method and submits a request to Janrain using curl. 
 * This is a generic method meant to handle any post as we currently use the API.
 */
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

/**
 * @internal
 * @param string $identifier This is a string which represents the unique social identifier for the individual to be mapped in Janrain's system against a LiveGather user.
 * @param string $primaryKey This is a string which represents the ID of the user we are mapping as defined in the LiveGather system.
 * @param string $rpxApiKey The key given to LiveGather for interacting with Janrain. 
 *
 * @return boolean
 *
 * This function takes the an identifier and an ID for a user in the system and maps them in Janrain's system. 
 * The purpose of this is to identify that a user in the system with an existing account also has mappings to other 
 * social networks as identified by the identifier. When this is complete Janrain will be able to recognize a user 
 * in the LiveGather system as having multiple identifiers, thus allowing a user to use any mapped identifier to login to 
 * LiveGather. 
 */
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

/**
 * @internal
 * @param string $identifier This is a string which represents the unique social identifier for the individual to be unmapped in Janrain's system using a LiveGather user.
 * @param string $primaryKey This is a string which represents the ID of the user we are unmapping as defined in the LiveGather system.
 * @param string $rpxApiKey The key given to LiveGather for interacting with Janrain. 
 *
 * @return boolean
 *
 * This function takes the an identifier and an ID for a user in the system and unmaps them in Janrain's system. 
 */
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
