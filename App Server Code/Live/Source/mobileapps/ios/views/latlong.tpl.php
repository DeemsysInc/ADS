<?php 

    $zipcode="92604";
$url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$zipcode."&sensor=false";
$details=file_get_contents($url);
$result = json_decode($details,true);

$lat=$result['results'][0]['geometry']['location']['lat'];

$lng=$result['results'][0]['geometry']['location']['lng'];

echo "Latitude :" .$lat;
echo '<br>';
echo "Longitude :" .$lng;
?>

