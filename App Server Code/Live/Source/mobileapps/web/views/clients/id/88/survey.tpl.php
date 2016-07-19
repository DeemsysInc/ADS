<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo $config['LIVE_URL'];?>mobileapps/web/views/css/jquery.datetimepicker.css"/>
<style type="text/css">

.custom-date-style {
	background-color: red !important;
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
    <p><div id="error-msg" style="color:red;font-size:20px"></div></p>
	<div id="doctor-form">
		<h2>Find doctors</h2>
		<div class="form-horizontal">
			<div class="form-group">
			  <label class="control-label col-sm-2" for="radius">Radius:</label>
			  <div class="col-sm-10">
				<input type="number" class="form-control" id="radius" placeholder="Enter Miles">
			  </div>
			</div>
			
			<div class="form-group">        
			  <div class="col-sm-offset-2 col-sm-10">
				<button type="submit" id="click" class="btn btn-primary">Find</button>
			  </div>
			</div>
	  </div>
  </div>
  <br>
	  
	  <div id="demo" class="collapse out">
	  <h2>Doctors List</h2>
	  <!--<div align="right"><button type="submit" id="backtoform" class="btn btn-primary">Back</button> <button type="submit" id="schedule" class="btn btn-primary">Schedule</button></div>-->
	  
	  <br>
	    <div class="table-responsive">  
		  <table class="table table-bordered">
				<thead>
				  <tr>
				    <th>&nbsp;</th>
					<th>Name</th>
					<!--<th>Address</th>-->
					<th>Provider ID</th>
					<!--<th>Network</th>-->
					<th>Speciality</th>
					<th>Plans</th>
				  </tr>
				</thead>
				<tbody>
				  <tr>
				    <td><div class="radio"><label><input type="radio" name="optradio" id="optradio"></label></div></td>
					<td>Adamsville Pharmacy (205) 674-1400</td>
					<!--<td>3633 Gray Ave Adamsville AL 35005</td>-->
					<td>35511</td>
					<!--<td>Independent</td>-->
					<td>Durable Medical Equipment</td>
					<td>HMO</td>
					
				  </tr>
				  <tr>
				    <td><div class="radio"><label><input type="radio" name="optradio" id="optradio"></label></div></td>
					<td>Rogers, Walter Benton(205) 674-5002</td>
					<!--<td>3931 Veterans Mem Dr Adamsville AL 35005</td>-->
					<td>1104883032</td>
					<!--<td>Independent</td>-->
					<td>Optometry</td>
					<td>HMO</td>
				  </tr>
				  <tr>
				    <td><div class="radio"><label><input type="radio" name="optradio" id="optradio"></label></div></td>
					<td>Alred, Ginger Lee(205) 663-5770</td>
					<!--<td>636 2nd St NE Ste B Alabaster AL 35007</td>-->
					<td>C73004</td>
					<!--<td>Shelby Chilton IPA</td>-->
					<td>Internal Medicine</td>
					<td>HMO</td>
				  </tr>
				  <tr>
				    <td><div class="radio"><label><input type="radio" name="optradio" id="optradio"></label></div></td>
					<td>Johnson, Carol Mitchell(205) 664-7570</td>
					<!--<td>224 1st Street N Ste 200 Alabaster AL 35007</td>-->
					<td>C72420</td>
					<!--<td>Shelby Chilton IPA</td>-->
					<td>Family Practice</td>
					<td>HMO</td>
				  </tr>
				  <tr>
				    <td><div class="radio"><label><input type="radio" name="optradio" id="optradio"></label></div></td>
					<td>Karassi, Malek S(205) 787-2669</td>
					<!--<td>1022 First St N Ste 350 Alabaster AL 35007</td>-->
					<td>G06561</td>
					<!--<td>Independent</td>-->
					<td>Internal Medicine</td>
					<td>HMO</td>
				  </tr>
				</tbody>
			</table>
			
	    </div>
		<div id="date-form" style="display:none;">
			<div class="form-horizontal">
				<div class="form-group">
				  <label class="control-label col-sm-2" for="date">Date:</label>
				  <div class="col-sm-10">
					<input type="text" class="form-control" id="datetimepicker" readonly="true" placeholder="mm/dd/yyyy HH:ii">
					
				  </div>
				</div>
				
				<div class="form-group">        
				  <div class="col-sm-offset-2 col-sm-10">
					<button type="submit" id="schedule" class="btn btn-primary">Confirm</button>
				  </div>
				</div>
			</div>
		</div>
	  </div>
	   <div id="success" class="collapse out"><strong>Your appointment is confirmed.</strong></div>
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
$( "#click" ).click(function() {
		//alert($('#radius').val())
		if($('#radius').val()=='')
		{
		 $("#error-msg").text("Please select option");
		}
		else
		{
			$( "#doctor-form").hide();
			$( "#error-msg" ).hide();
			$( "#demo" ).show();
			
		}
			
	  
	});
	$( ".radio" ).click(function() {
	   // $( "#doctor-form").hide();
	    
		$( "#date-form" ).slideDown( "slow" );
	});
	$( "#schedule" ).click(function() {
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
			$( "#success" ).show();
			
			
		}
			
	  
	});
	$( "#backtoform" ).click(function() {
		$( "#doctor-form").show();
		$("#radius").val('');
		$( "#demo" ).hide();
		$( "#error-msg" ).hide();
		$( "#success" ).hide();
	});
</script>
</html>
