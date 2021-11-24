<?php
function getHotelPoint($address){
    // Google Geocoding API Key
$apiKey  = 'API KEY GOES HERE';
$address = urlencode( $address );
$url     = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}key={apiKey}";
$resp    = json_decode( file_get_contents( $url ), true );

// Latitude and Longitude (PHP 7 syntax)
$hotelPoint=[];
$hotelPoint["lattitude"]    = $resp['results'][0]['geometry']['location']['lat'] ?? '';
$hotelPoint["longitude"]   = $resp['results'][0]['geometry']['location']['lng'] ?? '';
return $hotelPoint;
}
?>