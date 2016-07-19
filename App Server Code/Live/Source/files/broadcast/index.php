<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>YouTube Embedded JavaScript Player API</title>
<!-- Use the Google AJAX Libraries API:
      http://code.google.com/apis/ajaxlibs/ -->
<script src="//www.google.com/jsapi"></script>
<script>
      google.load("swfobject", "2.1");
    </script>
<link rel="stylesheet" type="text/css" media="screen" href="styles/screen.css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="jwplayer.js"></script>
<script type="text/javascript" src='detect_timezone.js'></script>
<input type="hidden" id="txt_ser_time" name="txt_ser_time" value='' />
<style type="text/css">
	*{  
	   margin:0;  
	   padding:0;  
	   /*background-color:#000000;*/
	}  
	  
		body{  
		   text-align:center; /*For IE6 Shenanigans*/  
		}  
		  
		#web_wrapper{  
		   width:960px;  
		   margin:0 auto;  
		   text-align:left;  
		} 
/*
    body {
      font-family: verdana, helvetica;
      background-color: white;
    }

    #timedisplay {
      border: solid 1px red;
      width: 50px;*/
    }
	
    </style>



	<script type="text/javascript">
		var seekFlag = false;
        var countSeek = 0;
		var timeToSeek = false;
		var triggerFlag = false;
		/*var localTimeZone = '';
		var tz = jstz.determine_timezone();
		if (typeof (tz) === 'undefined') {
			
		}else{
			localTimeZone = tz.offset();
			alert(localTimeZone);
		}*/
		/*function getServerTime(){
			var currenttime = '<?php //print date("F d, Y H:i:s", time())?>';
			//alert(currenttime);
		
			var montharray=new Array("January","February","March","April","May","June","July","August","September","October","November","December");
			var outserverdate=new Date(currenttime);
		
			return outserverdate;
		}*/
/*		
		function padlength(what){
			var output=(what.toString().length==1)? "0"+what : what;
			return output;
		}
		
		function displaytime(){
			var serverdate = getServerTime();
			serverdate.setSeconds(serverdate.getSeconds()+1);
			var datestring=montharray[serverdate.getMonth()]+" "+padlength(serverdate.getDate())+", "+serverdate.getFullYear();
			var timestring=padlength(serverdate.getHours())+":"+padlength(serverdate.getMinutes())+":"+padlength(serverdate.getSeconds());
			document.getElementById("servertime1").innerHTML=datestring+" "+timestring;
		}
		*/
		function getOffset(){
			var d=new Date()
			var gmtHours = -d.getTimezoneOffset()/60;
			return gmtHours;
		}
		function calcTime(offset,pTime) {
			//alert(pTime);
			// create Date object for current location
			if (pTime==0){
				d = new Date('May 21, 2013 8:11:00 EST');
			}else{
				//alert(pTime);
				d = new Date(pTime);
			}
			utc = d.getTime() + (d.getTimezoneOffset() * 60000);
			nd = new Date(utc + (3600000*offset));
			//alert(offset+' check here '+d.getTime()+' p '+nd);
			return nd.toLocaleString();
		}
		
	
		function validateTime(){
			var st = $("#txt_ser_time").val();
			//alert(st);
			var valSchDate = calcTime(getOffset(),0);
			var currenttime = calcTime(getOffset(),st);
			//alert('t'+currenttime);
			var diffSecs = days_between(currenttime,valSchDate);
			document.getElementById('currentTime').innerHTML = currenttime;
			document.getElementById('scheduledTime').innerHTML = valSchDate;
			return diffSecs;
		}
		
		
		function seekto(seekTime){
		//alert('seek'+seekTime);
			seekFlag = true;
			var pos = jwplayer().getPosition();
			//alert(pos+' '+seekTime);
				if (pos > 0){
					//alert(pos+' '+seekTime);
					 	jwplayer().seek(seekTime);
						//exit();
				}
			//setTimeout('seekto('+seekTime+')', 100);
		}
		
		function days_between(date1, date2) {

			//Set the two dates
			var millennium =new Date(date2); //Month is 0-11 in JavaScript
			today=new Date(date1);
			//Get 1 day in milliseconds
			var second = 1000, minute = 60 * second, hour = 60 * minute, day = 24 * hour;
			var getdiff = today.getTime()-millennium.getTime();
			//alert(getdiff);
			//Calculate difference btw the two dates, and convert to days
			var days = Math.floor(getdiff / day);
			getdiff -= days * day;
			var hours = Math.floor(getdiff / hour);
			getdiff -= hours * hour;
			var minutes = Math.floor(getdiff / minute);
			getdiff -= minutes * minute;
			var seconds = Math.floor(getdiff / second);
			
			var totalSeconds = (days*24*60*60)+(hours*60*60)+((minutes)*60) + seconds;
			//var sendDate = days + " day" + (days != 1 ? "s" : "") + ", " + hours + " hour" + (hours != 1 ? "s" : "") + ", " + minutes + " minute" + (minutes != 1 ? "s" : "") + ", " + seconds + " second" + (seconds != 1 ? "s" : "");
			//alert(totalSeconds);
			return totalSeconds;
			
			//return getdiff;
		
		}
		/*function getProperDate(parseDate){
			if (parseDate==0){
				var d = new Date();
			}else{
				var d = new Date(parseDate);
			}
		  	var curr_date = d.getDate();
			  var curr_month = d.getMonth() + 1; //months are zero based
			  var curr_year = d.getFullYear();
			  var get_hours=d.getHours();
			  var get_minuts=d.getMinutes();
			  var get_seconds=d.getSeconds();
			  //var get_milli_seconds=d.getMilliseconds();
			  var storeDate = (curr_month + "-" + curr_date + "-" + curr_year+ " " + get_hours + ":" + get_minuts+ ":" + get_seconds);
			  return storeDate;
		}
		
		function getHoldingDiv(){
			//$("#media_overlay").mask("Loading...");
			$("#holdingDiv").show();

		}*/
		  function setText(text) {
			//document.getElementById("status").innerHTML = text;
			$('#mediaplayer').replaceWith(text);
		  }
		  
		function checkToContinue(){
			var getElapsedTime = validateTime();
			$('#ms_currentTime').html(getElapsedTime);
			
			if (getElapsedTime<-15){
				$('#mediaplayer').hide();
				$('#ms_currentTime').html('');
				$('#holdingDiv').html("Please wait till we run the training session");
			}else if ((getElapsedTime>=-15) && (getElapsedTime<0)){
				$('#mediaplayer').show();
				$('#holdingDiv').html("The session is about to start.");
			}else if ((getElapsedTime>0) && (getElapsedTime<16)){
				$('#ms_currentTime').html('');
				$('#holdingDiv').html('');
				if (triggerFlag==false){
					$('#holdingDiv').hide();
					$('#web_content').hide();
					loadMediaPlyer();
					jwplayer().play();
					triggerFlag = true;
				}
			}else if ((getElapsedTime>=16) && (getElapsedTime<343)){
				$('#ms_currentTime').html('');
				if (triggerFlag==false){
					$('#holdingDiv').html("Oops! We just closed the room. <br /><br />We will upload the same training session in our archives folder. You can watch after 7 days.<br /><br />Thank you.");
					triggerFlag = true;
				}
			}else if (getElapsedTime>=343){
				//$('#ms_currentTime').html('');
				$('#holdingDiv').hide();
				$('#mediaplayer').replaceWith("<h2 style='text-align:left;'>We finished our Training session. Please look your archives or subscribe for next training session. <br /><br />Thank you for visiting</h2>");
			}else{
				
		 	}
		  	tCheck = setTimeout("checkToContinue()",1000);
		}

		var currenttime = '<?php print date("F d, Y H:i:s", time())?>'; //PHP method of getting server date
		currenttime = currenttime+' EST';

		///////////Stop editting here/////////////////////////////////
		
		var montharray=new Array("January","February","March","April","May","June","July","August","September","October","November","December");
		var serverdate=new Date(currenttime);
		
		/*function padlength(what){
			var output=(what.toString().length==1)? "0"+what : what
			return output
		}*/
		
		function displaytime(){
			serverdate.setSeconds(serverdate.getSeconds()+1);
			//var datestring=montharray[serverdate.getMonth()]+" "+padlength(serverdate.getDate())+", "+serverdate.getFullYear();
			//var timestring=padlength(serverdate.getHours())+":"+padlength(serverdate.getMinutes())+":"+padlength(serverdate.getSeconds());
			//alert(serverdate);
			$("#txt_ser_time").val(serverdate);
			document.getElementById("txt_ser_time").value = serverdate;
		}
		
		window.onload=function(){
			setInterval("displaytime()", 1000);
		}
		
    </script>
</head>
<body id="page">


<div align="center"> <span style="text-align:center"><strong>Broadcast Training Video of Louie Simmons</strong></span> <br>
  <div id="media_overlay" style="width:100%;height:100%;">
  	<div id="web_content">
	  <br>
	  <br>
	  <span>Your current time: </span> <span id="currentTime"></span><br />
	  <br />
	  <span>Training Schedule: </span> <span id="scheduledTime" ></span><br />
	  <br />
	  <span id="ms_currentTime"></span><br />
	  <br />
	  <br />
	</div>
    <div id="holdingDiv">Please wait</div>
	<div id="web_wrapper">
    <div id="mediaplayer"></div>
	</div>
  </div>
  <span id="status" ></span><br />
  <script type='text/javascript'>
	 		function loadMediaPlyer(){
				var h = (screen.height-(screen.height*0.21));
				var w = (screen.width-(screen.width*0.21));
				$("#web_wrapper").css("width",w);
				$("html").css("background-color","#000000");
				jwplayer('mediaplayer').setup({
				 file: 'seemore-vid.mp4',
				// image: 'preview.jpg',
				// dock:false,
				// icons:false,
				// display:false,
				// controlbar:'none',
				// linkfromdisplay:false,
				// allowscriptaccess:'always',
				 //'fullscreen': 'true',
				//'stretching': 'exactfit',
				//screencolor:'#000000',
				 height: h,
				 width: w,
				 plugins: { sharing: { link: false } },
				 "modes" : [
					 {"type": "html5"},
					 {"type": "flash", "src": "player.swf"}
				 ]
				});
			jwplayer().onPause(function(){ jwplayer().play();});
			jwplayer().onComplete(function(){ jwplayer().stop();});
			jwplayer().onIdle(function(){ 
				$('#holdingDiv').show();
				$('#holdingDiv').html("<h2 style='text-align:left;'>We finished our Training session. Please look your archives or subscribe for next training session. <br /><br />Thank you for visiting</h2>");
				$('#mediaplayer').remove(); 
				$("html").css("background-color","#FFFFFF");
			});
			jwplayer().getPlugin("controlbar").hide();
			jwplayer().getPlugin("dock").hide();
			jwplayer().getPlugin("display").hide();
			}
		checkToContinue();
	 </script>
</div>

<script type="text/javascript">

// Current Server Time script (SSI or PHP)- By JavaScriptKit.com (http://www.javascriptkit.com)
// For this and over 400+ free scripts, visit JavaScript Kit- http://www.javascriptkit.com/
// This notice must stay intact for use.

//Depending on whether your page supports SSI (.shtml) or PHP (.php), UNCOMMENT the line below your page supports and COMMENT the one it does not:
//Default is that SSI method is uncommented, and PHP is commented:

//var currenttime = '<!--#config timefmt="%B %d, %Y %H:%M:%S"--><!--#echo var="DATE_LOCAL" -->' //SSI method of getting server date



</script>

<?php 
/*$MNTTZ = new DateTimeZone('America/Detroit');
$ESTTZ = new DateTimeZone('America/New_York');

$dt = new DateTime('Jan 31, 2012 18:45:10', $MNTTZ);
var_dump($dt->format(DATE_RFC822), $dt->format('U'));
$dt->setTimezone($ESTTZ);
var_dump($dt->format(DATE_RFC822), $dt->format('U'));

?>

<?php
$intNow = time();
$strCurrentTZ = date_default_timezone_get();
date_default_timezone_set("America/Detroit"); 
echo date("m-d-Y H:i e T", $intNow);
date_default_timezone_set($strCurrentTZ); 
echo date("m-d-Y H:i e T", $intNow);*/
?>

</body>
</html>
