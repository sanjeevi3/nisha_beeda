<?php
function getHotelDistance($orgin,$dest){
    $distance_data = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?&origins='.$orgin.'&destinations='.$dest.'&key=[YOUR_API_KEY]');
                $distance_arr = json_decode($distance_data);
                if ($distance_arr->status=='OK') {
                    // Code to run after status OK
                 } else {
                   // Code to run after status INVALID_REQUEST
                 }
}
?>