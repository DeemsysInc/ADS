<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo $config['LIVE_URL'];?>mobileapps/web/views/css/jquery.datetimepicker.css"/>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places"></script>
    
<style type="text/css">

.custom-date-style {
	background-color: red !important;
}
  html, body, #map-canvas {
		  width: 100%;
		height: 400px;
        margin: 0px;
        padding: 0px
      }
	  
</style>
</head>
<body>
	<div class="container">

<!-- 
<div class="jumbotron">
  <h1>Survey Questions</h1> 
  <p>Resize this responsive page!</p>
</div>
 -->
<div class="row">
  <div class="col-md-12">
  <div align="right"> <?php
$user_ip = getenv('REMOTE_ADDR');
$geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));
$city = $geo["geoplugin_city"];
$region = $geo["geoplugin_regionName"];
$country = $geo["geoplugin_countryName"];
$latitude = $geo["geoplugin_latitude"];
$longitude = $geo["geoplugin_longitude"];

?>

</div>
  
    <p><div id="error-msg" style="color:red;font-size:20px"></div></p>
	<div id="doctor-form">
		<div id="map-canvas"></div>
  </div>
  <br>
	  
	  <div id="demo">
	  <h2>Cigna Seminars</h2>
	  <!--<div align="right"><button type="submit" id="backtoform" class="btn btn-primary">Back</button> <button type="submit" id="schedule" class="btn btn-primary">Schedule</button></div>-->
	  <div id="date-form" align="right" style="display:none;">
			<button type="submit" id="enroll" class="btn btn-primary">Enroll</button>
	  </div>
	  
	  <br>
	    <div class="table-responsive">  
		  <table class="table table-bordered">
				<thead>
				  <tr>
				    <th>&nbsp;</th>
					<th>Name</th>
					<th>Date</th>
					<th>Time</th>
				 </tr>
				</thead>
				<tbody>
				  <tr>
				    <td><div class="radio"><label><input type="radio" name="optradio" id="optradio"></label></div></td>
					<td>Seminar 1</td>
					<td>May 1, 2015</td>
					<td>10:30am to 11:30am</td>
					
				  </tr>
				  <tr>
				    <td><div class="radio"><label><input type="radio" name="optradio" id="optradio"></label></div></td>
					<td>Seminar 2</td>
					<td>May 13, 2015</td>
					<td>10:30am to 11:30am</td>
				  </tr>
				  <tr>
				    <td><div class="radio"><label><input type="radio" name="optradio" id="optradio"></label></div></td>
					<td>Seminar 3</td>
					<td>May 15, 2015</td>
					<td>10:30am to 11:30am</td>
				  </tr>
				  <tr>
				    <td><div class="radio"><label><input type="radio" name="optradio" id="optradio"></label></div></td>
					<td>Seminar 4</td>
					<td>May 16, 2015</td>
					<td>10:30am to 11:30am</td>
				  </tr>
				  <tr>
				    <td><div class="radio"><label><input type="radio" name="optradio" id="optradio"></label></div></td>
					<td>Seminar 5</td>
					<td>May 18, 2015</td>
					<td>10:30am to 11:30am</td>
				  </tr>
				</tbody>
			</table>
			
	    </div>
		
	  </div>
	   <div id="success" class="collapse out"><strong>Your seminar is confirmed.</strong></div>
	   
	   
  </div>
  
</div>
  <div id="request-form" style="display:none;" class="form-horizontal">
  
    <p>We must obtain and verify information that identifies each person who opens an account. Please fill in the following fields (those marked * are required fields).</p>
    <div class="form-group">
      <label class="control-label col-sm-2" for="fname">First Name :</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="fname" placeholder="Enter your first name">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="lname">Last Name:</label>
      <div class="col-sm-10">          
        <input type="text" class="form-control" id="lname" placeholder="Enter your last name">
      </div>
    </div>
	<div class="form-group">
      <label class="control-label col-sm-2" for="bdate">Birth Date:</label>
      <div class="col-sm-10">          
        <input type="text" class="form-control" id="bdate"  placeholder="mm/dd/yyyy">
		
      </div>
    </div>
	<div class="form-group">
      <label class="control-label col-sm-2" for="email">Email:</label>
      <div class="col-sm-10">          
        <input type="text" class="form-control" id="email" placeholder="Enter your email">
      </div>
    </div>
	
	<div class="form-group">
      <label class="control-label col-sm-2" for="email">Address:</label>
      <div class="col-sm-10">  
        <textarea class="form-control" rows="5" id="email"></textarea>
      </div>
    </div>
	<div class="form-group">
      <label class="control-label col-sm-2" for="email">Phone No:</label>
      <div class="col-sm-10">          
        <input type="number" class="form-control" id="phone" placeholder="Enter your phone number">
      </div>
    </div>
   
    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" id="submit" class="btn btn-primary">Submit</button>
      </div>
    </div>
 
  </div>
  
</div>


 
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="<?php echo $config['LIVE_URL'];?>mobileapps/web/views/js/jquery.datetimepicker.js"></script>

<script>
$('#datetimepicker').datetimepicker({
dayOfWeekStart : 1,
lang:'en',
//disabledDates:['1986/01/08','1986/01/09','1986/01/10'],
//startDate:	'1986/01/05',
formatDate:'m/d/Y'
});


	$( ".radio" ).click(function() {
	   // $( "#doctor-form").hide();
	   //alert("sdfsdf");
	    
		$( "#date-form" ).slideDown( "slow" );
	});
	$( "#enroll" ).click(function() {
		if($('input[name=optradio]:checked').length<=0)
		{
		    //not selected
			$( "#error-msg" ).show();	
			$("#error-msg").text("Please select option");
		}
		else
		{
			//selected
			
			$( "#doctor-form").hide();
			$( "#demo" ).hide();
			$( "#error-msg" ).hide();
			$("#request-form").show();
			
		}
			
	  
	});
	$( "#submit" ).click(function() {
		//selected
			
			$( "#doctor-form").hide();
			$( "#demo" ).hide();
			$( "#error-msg" ).hide();
			$("#request-form").hide();
			$( "#success" ).show();
			
		
			
	  
	});
	
	
	
	$( "#backtoform" ).click(function() {
		$( "#doctor-form").show();
		$("#radius").val('');
		$( "#demo" ).hide();
		$( "#error-msg" ).hide();
		$( "#success" ).hide();
	});
</script>
 <script>
	
	var lat=<?php echo floatval($latitude);?>;
    var lon=<?php echo floatval($longitude);?>;
	//alert(lat+'==='+lon);
	var map;
	var infowindow;

	function initialize() {
	
	var pyrmont = new google.maps.LatLng(lat,lon);

	  map = new google.maps.Map(document.getElementById('map-canvas'), {
		center: pyrmont,
		zoom: 15
	  });

	  var request = {
		location: pyrmont,
		radius: 500,
		types: ['hospital','health','doctor']
	  };
	  infowindow = new google.maps.InfoWindow();
	  var service = new google.maps.places.PlacesService(map);
	  service.nearbySearch(request, callback);
	}

	function callback(results, status) {
		
	  if (status == google.maps.places.PlacesServiceStatus.OK) {
		for (var i = 0; i < results.length; i++) {
		  
			createMarker(results[i]);
		}
	  }
	}

	function createMarker(place) {
	  var placeLoc = place.geometry.location;
	  var image="http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
	  var markerA = new google.maps.Marker({
		map: map,
		icon:image,
		position: new google.maps.LatLng(lat,lon),
		customInfo: "Marker A"
	  });

	  google.maps.event.addListener(markerA, 'click', function() {
		infowindow.setContent("<?php echo $city.','.$region;?>");
		infowindow.open(map, this);
	  });
	  
	  var marker = new google.maps.Marker({
		map: map,
		position: place.geometry.location
	  });

	  google.maps.event.addListener(marker, 'click', function() {
		infowindow.setContent(place.name);
		infowindow.open(map, this);
	  });
	}

	google.maps.event.addDomListener(window, 'load', initialize);

    </script>
</html>
