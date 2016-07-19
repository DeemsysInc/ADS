
<script src="//www.google.com/jsapi"></script>
<script>
     // google.load("swfobject", "2.1");
    </script>
<link rel="stylesheet" type="text/css" media="screen" href="styles/screen.css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="jwplayer.js"></script>


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

	
    </style>



<div align="center">
  <div id="media_overlay" style="width:100%;height:100%;">
  	
  
	<div id="web_wrapper">
    <div id="mediaplayer"></div>
	</div>
  </div>
  <span id="status" ></span><br />
  <script type='text/javascript'>
	 		function loadMediaPlyer(filename){
			
				var h = (screen.height-(screen.height*0.21));
				var w = (screen.width-(screen.width*0.21));
				$("#web_wrapper").css("width",w);
				$("html").css("background-color","#000000");
				jwplayer('mediaplayer').setup({
				 file: filename,
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
				autoplay:'true',
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
		
	 </script>
     <?php 

$arrPath= array();
$url = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';
if (!empty($url)){
	$arrPath = explode('/',$url); 
}
if (empty($arrPath[0]))
{
 // echo "Please specify the filename";
  ?>
<script>
  $("#mediaplayer").append("<h1 align='center'>Please specify the filename</h1>");
</script>
  <?php
}
else
{
	$filepath="files/".$arrPath[0];
	
	?><script type="text/javascript">loadMediaPlyer('<?php echo $filepath;?>');</script><?php
}

?>
</div>

