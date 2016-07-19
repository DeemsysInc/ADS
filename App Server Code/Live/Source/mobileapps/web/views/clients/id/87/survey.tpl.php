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
    <p><div id="error-msg" style="color:red;font-size:20px"></div></p>
    <h2>What causes the most stress in your life?</h2>
    <p>
		<div class="col-sm-12">
			<label class="radio-inline"> <input type="radio" name="optradio" id="optradio" value="summer"> Not enough time </label>
			<label class="radio-inline"> <input type="radio" name="optradio" id="optradio" value="Procrastination"> Procrastination </label>
			<label class="radio-inline"> <input type="radio" name="optradio" id="optradio" value="School"> School</label>
			<label class="radio-inline"> <input type="radio" name="optradio" id="optradio" value="Work"> Work</label>
			<label class="radio-inline"> <input type="radio" name="optradio" id="optradio" value="Clutter"> Clutter</label>
			<label class="radio-inline"> <input type="radio" name="optradio" id="optradio" value="Dwelling on the past"> Dwelling on the past</label>
			<label class="radio-inline"> <input type="radio" name="optradio" id="optradio" value="Family"> Family</label>
			<label class="radio-inline"> <input type="radio" name="optradio" id="optradio" value="Finances"> Finances</label>
			<label class="radio-inline"> <input type="radio" name="optradio" id="optradio" value="Friends"> Friends</label>
			
		</div>
		<br><br><br><br>
		<div align="center" class="col-sm-12">
		<button type="button" class="btn btn-primary" id="click">Submit</button>
		</div>
		<div id="demo" class="collapse out"><img id="img" src="<?php echo $config['LIVE_URL']; ?>mobileapps/web/views/images/survey_results.png"/></div>
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
		 $("#error-msg").text("Please select option");
		}
		else
		{
			$( "#error-msg" ).hide();
			$( "#demo" ).show();
			
		}
			
	  
	});
	</script>
	
	
</body>
</html>