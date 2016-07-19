  <?php global $config;?>
  <footer id="footer">

			<div class="container">
				<div class="row">
					<div class="col-md-3">
						<h4>SEE MORE</h4>
						<br>
						<p><a href="#">About</a></p>
						<p><a href="<?php echo $config['LIVE_URL'];?>blog">Blog</a></p>
						<p><a href="<?php echo $config['LIVE_URL'];?>contact">Contact</a></p>
						<p><a href="http://arapps.seemoreinteractive.com">Login</a></p>
					    <p><a href="<?php echo $config['LIVE_URL'];?>home/terms">Terms Of Use</a></p>
					    <p><a href="<?php echo $config['LIVE_URL'];?>home/privacy">Privacy Policy</a></p>
					    <p><a href="<?php echo $config['LIVE_URL'];?>press">Press</a></p>


						<br>
						<h4>DOWNLOAD</h4>
						<br>
					    <p>SeeMore Interactive App</p>
					    <p>&nbsp;&nbsp;<a href="https://itunes.apple.com/us/app/seemore-interactive/id591304180?mt=8" target="_blank">Apple</a></p>
					    <p>&nbsp;&nbsp;<a href=" https://play.google.com/store/apps/details?id=com.seemoreinteractive.seemoreinteractive" target="_blank">Android</a></p>
					   <!-- <p>Call-to-Action Package</p>-->
					</div>
					<div class="col-md-3">
						<h4>CONTACT</h4>
						<br>
						<address>
							SeeMore Interactive Inc.<br />
							3000 E. Main St.<br />
							Suit B101<br />
							Columbus, OH 43209
						</address>
						<p>&nbsp;</p>
						<p>info@seemoreinteractive.com</p>
					</div>
					<div class="col-md-3">
					<ul class="socialicons color">
						<li><a href="https://www.facebook.com/seemoreinteractive" class="facebook" target="_blank">facebook</a></li>
						<li><a href="https://twitter.com/SeeMoreInt" class="twitter" target="_blank">twitter</a></li>
						<li><a href="http://www.linkedin.com/company/seemore-interactive-inc-" class="linkedin" target="_blank">linkedin</a></li>
						<li><a href="https://plus.google.com/+Seemoreinteractive" class="gpluslight" target="_blank">gpluslight</a></li>
						<li><a href="http://pinterest.com/seemoreint/" class="pinterest" target="_blank">pinterest</a></li>
						
					</ul>
						 <br><br>
						<!-- <p><img src="<?php echo $config['LIVE_URL'];?>views/images/subscribe.png" alt="Subscribe"></p> -->
					</div>
				</div>
				
			</div>
				
		</footer><!-- /#Footer -->
	</div>
	  
	    
	<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo $config['LIVE_URL'];?>views/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo $config['LIVE_URL'];?>views/js/parallax.js"></script>
	<script>
	$('ul#insidepagenav > li > a[href*=#]:not([href=#])').click(function() {
if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') 
    || location.hostname == this.hostname) {

  var target = $(this.hash);
  target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
  if (target.length) {
    $('html,body').animate({
      scrollTop: target.offset().top
    }, 1000);
    return false;
  }
}
});  
	 $('li').each(function(){
    if(window.location.href.indexOf($(this).find('a:first').attr('href'))>-1)
    {
    $(this).addClass('active').siblings().removeClass('active');
    }
    $(window).load(function() {
    	$('.flexslider').flexslider({
		    animation: "slide",slideshowSpeed: 4000,pauseOnHover: true
		  });
	  //alert($(window).width());
	  // if($(window).width() >= 800) {
	  // 	$('.flexslider').flexslider({
		 //    animation: "slide",
		 //    pauseOnHover: true,
		 //    animationLoop: false,
		 //    itemWidth: 210,
		 //    itemMargin: 5
		 //  });

	  // } else {
	  //   $('.flexslider').flexslider({
		 //    animation: "slide",slideshowSpeed: 2000,pauseOnHover: true,
		 //  });
	  // }

	});
});
	</script>
	
    



	 <!-End image gallery lightbox   --> 
        
           <!-Start htm5 video lightbox   -->

        <script type="text/javascript" src="<?php echo $config['LIVE_URL'];?>plugins/html5lightbox/html5lightbox.js"></script>

        <!-End htm5 video lightbox   --> 

        <script src="<?php echo $config['LIVE_URL'];?>views/js/common.js"></script>
         <script src="<?php echo $config['LIVE_URL'];?>plugins/flexslider/jquery.flexslider-min.js"></script>
<script type="text/javascript" src="<?php echo $config['LIVE_URL'];?>views/js/respond.min.js"></script>
<script>
		jQuery(document).ready(function($){
	//portfolio - show link
	$('.fdw-background').hover(
		function () {
			$(this).animate({opacity:'1'});
		},
		function () {
			$(this).animate({opacity:'0'});
		}
	);	
});
		</script>
    <!-- Quantcast Tag -->
<script type="text/javascript">
var _qevents = _qevents || [];

(function() {
var elem = document.createElement('script');
elem.src = (document.location.protocol == "https:" ? "https://secure" : "http://edge") + ".quantserve.com/quant.js";
elem.async = true;
elem.type = "text/javascript";
var scpt = document.getElementsByTagName('script')[0];
scpt.parentNode.insertBefore(elem, scpt);
})();

_qevents.push({
qacct:"p-cbWVcc38Ehx4q"
});
</script>

<noscript>
<div style="display:none;">
<img src="//pixel.quantserve.com/pixel/p-cbWVcc38Ehx4q.gif" border="0" height="1" width="1" alt="Quantcast"/>
</div>
</noscript>
<!-- End Quantcast tag -->


</body>
</html>