
<script>
     // google.load("swfobject", "2.1");
    </script>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="jwplayer.js"></script>




    <div id="mediaplayer"></div>

  <script type='text/javascript'>
	 		function loadMediaPlyer(){
			
				var h = 200;
				var w = 300;
				jwplayer('mediaplayer').setup({
				 file: 'files/seemore-vid.mp4',
				 height: h,
				 width: w,
				 plugins: { sharing: { link: false } },
				 "modes" : [
					 {"type": "html5"},
					 {"type": "flash", "src": "player.swf"}
				 ]
				});
			
			jwplayer().getPlugin("dock").hide();
			jwplayer().getPlugin("display").hide();
			}
		
	 </script>
  
<script type="text/javascript">loadMediaPlyer();</script><?php
