<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title></title>
    <style>
      /* html, body, #map-canvas {
		  width: 100%;
		height: 400px;
        margin: 0px;
        padding: 0px
      } */
#map_wrapper {
    height: 400px;
}

#map_canvas {
    width: 100%;
    height: 100%;
}
    </style>
		<script type="text/javascript" src="http://www.thatsnotcamping.com/views/js/jquery-1.6.1.min.js"></script>
<script>
getLocation();
//var x = document.getElementById("latlong");

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else { 
        x.innerHTML = "Geolocation is not supported by this browser.";
    }
}

function showPosition(position) {
	var latlng = position.coords.latitude + 
    "," + position.coords.longitude;
	
	alert(latlng);

	var geocodingAPI = "http://maps.googleapis.com/maps/api/geocode/json?latlng="+latlng+"&sensor=true";

	$.getJSON(geocodingAPI, function (json) {

		// Set the variables from the results array
		var address = json.results[0].formatted_address;
		console.log('Address : ', address);
		
		var latitude = json.results[0].geometry.location.lat;
		console.log('Latitude : ', latitude);
		
		var longitude = json.results[0].geometry.location.lng;
		console.log('Longitude : ', longitude);

		// Set the table td text
		$('#address').text(address);
		$('#latitude').text(latitude);
		$('#longitude').text(longitude);
	});
	
	
	
	
}




</script>

		</head>
  <body>
  
 
 
  <?php
/* $user_ip = getenv('REMOTE_ADDR');
$geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));
$city = $geo["geoplugin_city"];
$region = $geo["geoplugin_regionName"];
$country = $geo["geoplugin_countryName"];
$latitude = $geo["geoplugin_latitude"];
$longitude = $geo["geoplugin_longitude"];

 */
?>
 <?php 
 
 $currentLat=$latitude;
 $currentLng=$longitude;
 $currentAddr=$city.",".$region.",".$country;
 $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.$currentLat.','.$currentLng.'&sensor=true
';
$content = file_get_contents($url);
$json = json_decode($content, true);

//print_r($json['results']);
$arrData=array();
for($i=0;$i<count($json['results']);$i++)
{
   $formattedAddress=$json['results'][$i]['formatted_address'];
   $lat=$json['results'][$i]['geometry']['location']['lat'];
   $lng=$json['results'][$i]['geometry']['location']['lng'];
   
   $distance = distance($currentLat, $currentLng, $lat, $lng, "M") . " Miles<br>";
    if ($distance < 10) {
       $arrData[$i]['address']=$formattedAddress;
	   $arrData[$i]['lat']=$lat;
       $arrData[$i]['lng']=$lng;
	   
   
    }
   
}
 
  function distance($lat1, $lon1, $lat2, $lon2, $unit) {

  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);

  if ($unit == "K") {
    return ($miles * 1.609344);
  }else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
        return $miles;
      }
}
$mainIcon="http://maps.google.com/mapfiles/ms/icons/red-dot.png";
$Icon="http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
$markers='';
$markers.= '["'.$currentAddr.'", '.$currentLat.', '.$currentLng.',"'.$mainIcon.'"],';
$addresstext='';
$addresstext.= '["'.$currentAddr.'"],';
for($i=0;$i<count($arrData);$i++){ 
$markers.= '["'.$arrData[$i]['address'].'", '.$arrData[$i]['lat'].', '.$arrData[$i]['lng'].',"'.$Icon.'"],';
$addresstext.= '["'.$arrData[$i]['address'].'"],';
}
//echo $markers;
//echo $addresstext;

?>
<div id="map_wrapper">
    <div id="map_canvas" class="mapping"></div>
</div>
<!-- <div class="google-maps"><div id="map-canvas"></div></div> -->
</body>
<script>
 jQuery(function($) {
    // Asynchronously Load the map API 
    var script = document.createElement('script');
    script.src = "http://maps.googleapis.com/maps/api/js?sensor=false&callback=initialize";
    document.body.appendChild(script);
});
function initialize() {
    var map;
    var bounds = new google.maps.LatLngBounds();
    var mapOptions = {
        mapTypeId: 'roadmap',
		zoom: 4
    };
                    
    // Display a map on the page
    map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
    map.setTilt(45);
        
    // Multiple Markers
	var markers=[<?php echo $markers;?>];
	
   /*  var markers = [
        ['London Eye, London', 51.503454,-0.119562],
        ['Palace of Westminster, London', 51.499633,-0.124755]
    ]; 
	 */
                        
    // Info Window Content
    var infoWindowContent = [<?php echo $addresstext;?>];
        
    // Display multiple markers on a map
    var infoWindow = new google.maps.InfoWindow(), marker, i;
    
    // Loop through our array of markers & place each one on the map  
    for( i = 0; i < markers.length; i++ ) {

		 
		var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
			bounds.extend(position);
			marker = new google.maps.Marker({
				position: position,
				map: map,
				title: markers[i][0],
				icon: markers[i][3]
			});
		// Allow each marker to have an info window    
			google.maps.event.addListener(marker, 'click', (function(marker, i) {
				return function() {
					infoWindow.setContent(infoWindowContent[i][0]);
					infoWindow.open(map, marker);
				}
			})(marker, i));
        // Automatically center the map fitting all markers on the screen
        map.fitBounds(bounds);
    }

    // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
    var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
        this.setZoom(12);
        google.maps.event.removeListener(boundsListener);
    });
    
}
    
</script>
</html>