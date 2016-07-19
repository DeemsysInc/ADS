<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <style>
  #img {
display:block;
height: auto;
max-width: 100%;
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
   <p>
	   <div id="form-for-survey">
		<div class="col-sm-12">
		 <p><div id="error-msg" style="color:red;font-size:20px"></div></p>
			<label class="radio"> <input type="radio" name="optradio" id="optradio" value="summer"> I want the "Healthy Lifestyle" Newsletter </label>
			<label class="radio"> <input type="radio" name="optradio" id="optradio" value="Procrastination">  I want the "Healthy Lifestyle" Newsletter & 2015 Thrive Calendar  </label>
		
      <div class="form-horizontal">
	  <h4>Please Fill Out Information For Shipping</h4>
			<div class="form-group">
			  <label class="control-label col-sm-1" for="radius">First Name:</label>
			  <div class="col-sm-10">
				<input type="fname" class="form-control" id="fname" placeholder="Enter Name">
			  </div>
			</div>
			<div class="form-group">
			  <label class="control-label col-sm-1" for="radius">Last Name:</label>
			  <div class="col-sm-10">
				<input type="lname" class="form-control" id="lname" placeholder="Enter Last Name">
			  </div>
			</div>
			<div class="form-group">
			  <label class="control-label col-sm-1" for="radius">Email:</label>
			  <div class="col-sm-10">
				<input type="email" class="form-control" id="email" placeholder="Enter Email">
			  </div>
			</div>
			<div class="form-group">
			  <label class="control-label col-sm-1" for="radius">Address:</label>
			  <div class="col-sm-10">
				<textarea class="form-control" rows="5" id="address"></textarea>
			  </div>
			</div>

			
			
	  </div>
		
		</div>
		
		
	  
		<br><br><br><br>
		<div align="center" class="col-sm-12">
		<button type="button" class="btn btn-primary" id="click">Submit</button>
		</div>
		</div>
		<div id="demo" class="collapse out"><h1>Thank you for your feedback.</h1></div>
	</p>
  </div>
  
</div>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	<script>
	$( "#click" ).click(function() {
		if($('input[name=optradio]:checked').length<=0)
		{
			$( "#error-msg" ).show();	
			
		 $("#error-msg").text("Please select option");
		
		}
		else
		{
			$( "#error-msg" ).hide();
				$( "#form-for-survey" ).hide();
			    $( "#demo" ).show();
		}

			
	  
	});
	</script>
	
	
</body>
</html>