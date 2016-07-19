<script type='text/javascript' src='jwplayer.js'></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

<div id='mediaspace'>This text will be replaced</div>



<script type="text/javascript">
/*    jwplayer("mediaspace").setup({
        flashplayer: "player.swf",
        playlist: [
            {  file: "http://wiki.westside-barbell.com/broadcast/videos/low/VTS_01_1.flv", image: "/uploads/video.jpg" },
            { file: "http://wiki.westside-barbell.com/broadcast/videos/tv9.flv", image: "/uploads/bbb.jpg" },
            {  file: "http://wiki.westside-barbell.com/broadcast/videos/low/VTS_01_1.flv", image: "/uploads/video.jpg" },
            {  file: "http://wiki.westside-barbell.com/broadcast/videos/tv9.flv", image: "/uploads/bbb.jpg" }
        ],
        repeat:"list",
        autoplay:true,
        height: 270,
        width: 720
    });*/


</script>




<script type="text/javascript">
  jwplayer('mediaspace').setup({
    'flashplayer': 'player.swf',
    
    'width': '650',
    'height': '240',
    'playlistfile': 'play.xml',
    'playlist.position': 'right',
    'playlist.size': '250',
    'controlbar': 'over'
  });
</script>
