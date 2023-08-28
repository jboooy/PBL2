<?php
require '../vendor/autoload.php';

function get_artist_info($artist_id){
    $session = new SpotifyWebAPI\Session(
        'KEY',
        'PW'
    );

    $api = new SpotifyWebAPI\SpotifyWebAPI();
    
    $session->requestCredentialsToken();
    $accessToken = $session->getAccessToken();
    $api->setAccessToken($accessToken);

    $res = $api->getArtist($artist_id);

    return $res;
}

function get_track_info($spo_ids){

    $session = new SpotifyWebAPI\Session(
        'KEY',
        'PW'
    );
    
    $api = new SpotifyWebAPI\SpotifyWebAPI();
    
    $session->requestCredentialsToken();
    $accessToken = $session->getAccessToken();
    $api->setAccessToken($accessToken);

    $res = $api->getTracks($spo_ids);

    return $res;
}

function search_track($query){
    $session = new SpotifyWebAPI\Session(
        'KEY',
        'PW'
    );
    
    $api = new SpotifyWebAPI\SpotifyWebAPI();
    
    $session->requestCredentialsToken();
    $accessToken = $session->getAccessToken();
    $api->setAccessToken($accessToken);
    
    $type = array('track','artist');
    $options = array();

    $options += array('market'=>'JP');
    $res = $api->search($query, $type, $options);

    return $res;
}
//$res = get_track_info('4wLQr28uORriOx5rNM8by7');
//echo $res->tracks[0]->album->release_date;
?>
