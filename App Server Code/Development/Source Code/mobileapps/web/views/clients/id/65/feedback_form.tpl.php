<!doctype html>
<html lang="us">
<head>
	<meta charset="utf-8">
	<title>Survey</title>
	<link href="<?php echo $config['LIVE_URL']; ?>mobileapps/web/views/js/jquery/jquery-ui.css" rel="stylesheet">
 <link href="<?php echo $config['LIVE_URL']; ?>mobileapps/web/views/clients/id/<?php echo $id; ?>/MoreThan640Media.css" rel="stylesheet" type="text/css"/>
 
</head>

<body>

<img src="<?php echo $config['LIVE_URL']; ?>/files/clients/<?php echo $id; ?>/logo/foodtruck_logo.png" alt="food truck"  />
<p ><h3 class="heading">Survey</h3></p>
<p style="margin-left:15px;">Complete the brief survey for your chance to win exciting prizes.</p>
<form name="form" id="form" action="<?php echo $config['LIVE_URL']."mobileapps/web/feedback_client_form/".$id."/".$userId."/action"; ?>" method="post">
<ol>
<li><p>Have you been to the Columbus Food Truck Fest prior to this year?</p>
<input type="hidden" value="radio" name="type_1" id="type_1"/>
<input type="hidden" value="Have you been to the Columbus Food Truck Fest prior to this year?" name="question_1" id="question_1"/></p>

<p>
<div id="radioset1">
	<input type="radio" id="answer_11" name="answer_1"  value="Yes"><label for="answer_11">Yes</label>
	<input type="radio" id="answer_12" name="answer_1"  value="No"><label for="answer_12">No</label>
</div>
</p></li>
<li>
<p>Have you eaten from  Mobile Food Vendor Before? </p>
<input type="hidden" value="Have you eaten from  Mobile Food Vendor Before?" name="question_2" id="question_2"/>
<input type="hidden" value="radio" name="type_2" id="type_2"/></p>
<p>
<div id="radioset2">
<input type="radio" name="answer_2" id="answer_21" value="Yes" /><label for="answer_21"> Yes</label>
<input type="radio" name="answer_2"  id="answer_22" value="No" /><label for="answer_22"> No </label> 
</div>

</p>
</li>

<li><p>Do you enjoy the live Music of the Columbus Food Truck Fest? </p>
<input type="hidden" value="Do you enjoy the live Music of the Columbus Food Truck Fest?" name="question_3" />
<input type="hidden" value="radio" name="type_3" id="type_3"/></p>
<p>
<div id="radioset3">
<input type="radio" name="answer_3" id="answer_31" value="Yes" /><label for="answer_31"> Yes</label>
<input type="radio" name="answer_3"  id="answer_32" value="No" /><label for="answer_32"> No </label> 
</div>
</p>
</li>

<li><p>Did you receive a hard copy Map of the festival when you came into the park? </p>
<input type="hidden" value="Did you receive a hard copy Map of the festival when you came into the park?" name="question_4" />
<input type="hidden" value="radio" name="type_4" id="type_4"/></p>
<p>  
<div id="radioset4">
<input type="radio" name="answer_4" id="answer_41" value="Yes" /><label for="answer_41"> Yes</label>
<input type="radio" name="answer_4"  id="answer_42" value="No" /><label for="answer_42"> No </label> 
</div>
</p>
</li>


<li><p>Was our staff courteous and helpful? </p>
<input type="hidden" value="Was our staff courteous and helpful?" name="question_5" />
<input type="hidden" value="radio" name="type_5" id="type_5"/></p>
<p>
<div id="radioset5">
<input type="radio" name="answer_5" id="answer_51" value="Yes" /><label for="answer_51"> Yes</label>
<input type="radio" name="answer_5"  id="answer_52" value="No" /><label for="answer_52"> No </label> 
</div>
</p></li>
 
 
 <li><p> Comments : </p><input type="hidden" value="Comments" name="question_6" id="question_6"/>
 <input type="hidden" value="textarea" name="type_6" id="type_6"/></p>
<p>
<textarea name="answer_6" id="answer_6" rows="5" columns="20" class="textarea" placeholder="Hint:  How you heard about the festival and do you have any recommendations/input for bettering out event in the future." maxlength="140" ></textarea>
</p></li>
   
 
 
</ol>
<!--<p style="margin-left:20px;">Please give your details in the spaces provided below. Thank you</p>


<div  style="margin-left:15px;">Name <span style="color:#E30812">*</span></div>

<div>
<input  type="text"  name="name" id="name"  class="boxes" /></div>

<br>
<div  style="margin-left:15px;">Email <span style="color:#E30812">*</span></div>

<div><input  type="email"   name="email" id="email"  class="boxes"/></div>

-->

<p style="margin-left:15px;">Prizes will be awarded after the festival. Winners will be notified by the email address provided with their SeeMore Interactive registration. No purchase is necessary. Visit <a href="http://www.columbusfoodtruckfest.com" >columbusfoodtruckfest.com</a> for more information.</p>
<span style="color:#F00" name="spanerr" id="spanerr"></span>
<div><input type="hidden" name="client_name" id="client_name" value="<?php echo $client_name;?>" /></div>
<div><input type="submit" value="Save" class="button"  style="background-color:#C0013F"/> <input type="button" value="Clear" class="button" style="background-color:#C0013F" onclick="clearFeedback();"/>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>

</div>
</form>
<script src="<?php echo $config['LIVE_URL']; ?>mobileapps/web/views/js/jquery/external/jquery/jquery.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>mobileapps/web/views/js/jquery/jquery-ui.js"></script>
<script>
$( "#radioset1" ).buttonset();
$( "#radioset2" ).buttonset();
$( "#radioset3" ).buttonset();
$( "#radioset4" ).buttonset();
$( "#radioset5" ).buttonset();
</script>
<script type="text/javascript">
var actionUrl = "<?php echo $config['LIVE_URL']; ?>mobileapps/web/feedback_client_form/65/action/";
var thanksUrl = "<?php echo $config['LIVE_URL']; ?>mobileapps/web/thanks/";

</script>
</body>
</html>
