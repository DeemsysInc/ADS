<style>
.picholder:hover .overlay{opacity:1;}
.picholder:hover .fancypics{opacity:0.7;}

.picholder{position:relative;}

.overlay {
  bottom: 0;left: 0; top: 0; right: 0;
  width: 100%;
  margin: auto;
  position: absolute;
  /*background-color:#3f3f3f;*/
  border-radius: 50%;
  opacity:0;
}

.text_box{
  color:white;
  weight:bold;
  font-size:1em;
  padding:71px;
  /*padding-bottom:50%;*/
  text-align:center;
}
</style>
<section id="slider">
			<div class="flexslider">
  <ul class="slides">
    <li>
      <img src="<?php echo $config['LIVE_URL'];?>views/images/banner.png" />
     <div class="caption" style="position: absolute;">REVEAL YOUR WORLD</div>
     <div class="subcaption" style="position: absolute;">
     	<span><a  style="text-decoration:none;" href="<?php echo $config['LIVE_URL'];?>views/videos/seemore-vid.mp4" class="html5lightbox" data-width="480" data-height="320" title="<div style='width:45%;float:left;font-size:12px;'>Click on the icons to download the free app from:</div><div style='width:55%;float:left;'><a href='https://itunes.apple.com/us/app/seemore-interactive/id591304180?mt=8' target='_blank'><img width='120' src='<?php echo $config['LIVE_URL'];?>views/images/appstore_icon.png' alt='apple'></a>&nbsp;&nbsp;<a href='https://play.google.com/store/apps/details?id=com.seemoreinteractive.seemoreinteractive' target='_blank'><img width='120' src='<?php echo $config['LIVE_URL'];?>views/images/android_icon.png' alt='apple'></a></div>">VIEW DEMO <img  style="width: 12%;display: inline;"src="<?php echo $config['LIVE_URL'];?>views/images/viewdemoicon.png" alt="View Demo">
		</a>
	    </span>
	</div>
    </li>
   <!--  <li>
      <img src="<?php echo $config['LIVE_URL'];?>views/images/banner.png" />
      
    </li>
    <li>
      <img src="<?php echo $config['LIVE_URL'];?>views/images/banner.png" />
      
    </li>
    <li>
      <img src="<?php echo $config['LIVE_URL'];?>views/images/banner.png" />
      
    </li> -->
  </ul>
</div>
		</section> <!-- /#Slider -->
		<section id="welcome">
			<div class="container">
				<div class="row service_header">
					<h3 class="service_h3">We're SeeMore Interactive.</h3>
					<h3 class="service_h3">We're the next generation
					<h3 class="service_h3">of mobile commerce.</h3>
					<p>&nbsp;<p>
					<p>We are a mobile commerce platform that utilizes interactive marketing to create unique customer experiences. We make your brand's marketing efforts more engaging to your customers, and provide you with valuable insights and data to make your brand a success. We utilize image recognition, location-based technologies and augmented reality to enable valuable content, such as product recommendations, coupons/offers, interactive video's and more. We are the bridge between physical and digital commerce.</p>
				</div>
				
			</div>
		</section>
		<section id="service-slider">
			<div class="container">
				<div class="flexslider">
			  <ul class="slides">
			    <li>
			      <figure>
			       <center><img src="<?php echo $config['LIVE_URL'];?>views/images/shop_anywhere.png"></a></center>
				  </figure>
			      <figcaption style="color: #ff2600;background: #D2D3D5;text-align: center;font-size: 23px;">Shop Anywhere,<br>Anytime</figcaption>
			     <p style="background: #D2D3D5;">Your customers can create wish lists, retrieve coupons, and buy what they want, when they want it.</p>
			    </li>
			   <li>
			   	<figure>
			     <center><a href="#"><img src="<?php echo $config['LIVE_URL'];?>views/images/retail.png"></a></center>
				</figure> 
			      <figcaption style="color: #ff2600;background: #D2D3D5;text-align: center;font-size: 23px;">Bring Retails to Life</figcaption>
			      <p style="background: #D2D3D5;">Keep shoppers intrigued with helpful, exclusive content that features instant merchandise recommendations, tips, videos and more.</p>
			    
			    </li>
			    <li>
			      <figure>
			        <center><a href="#"><img src="<?php echo $config['LIVE_URL'];?>views/images/do_the_talk.png"></a></center>
				  </figure>
			      <figcaption style="color: #ff2600;background: #D2D3D5;text-align: center;font-size: 23px;">Let the Numbers <br>do the Talking</figcaption>
			      <p style="background: #D2D3D5;">Follow your customer throughout the brand experience, and track their actions for valuable consumer insights.</p>
			    </li>
			   
			  </ul>
			</div>
			</div>
		</section>
		<section id="service">
			<div class="container">
				<div class="row">
					<div class="col-md-3">
						<center><img src="<?php echo $config['LIVE_URL'];?>views/images/shop_anywhere.png"></a></center>
						<h4 class="text-center service_h4">Shop Anywhere,<br>Anytime</h4>
						<p >Your customers can create wish lists, retrieve coupons, and buy what they want, when they want it.</p>
					</div>
					<div class="col-md-3">
						<center><a href="#"><img src="<?php echo $config['LIVE_URL'];?>views/images/retail.png"></a></center>
						<h4 class="text-center service_h4">Bring Retails to Life</h4>
						<p>Keep shoppers intrigued with helpful, exclusive content that features instant merchandise recommendations, tips, videos and more.</p>
					</div>
					<div class="col-md-3">
						<center><a href="#"><img src="<?php echo $config['LIVE_URL'];?>views/images/do_the_talk.png"></a></center>
						<h4 class="text-center service_h4">Let the Numbers <br>do the Talking</h4>
						<p>Follow your customer throughout the brand experience, and track their actions for valuable consumer insights.</p>
					</div>
					
				</div>
			</div>
		</section>
		<section id="how-it-works-slider">
		
			<h3 style="color:#ff2600;text-align:center;">Here's how it works.</h3>
			<br></br>
			<div class="container">
				<div class="flexslider">
			  <ul class="slides">
			    <li>
			      <figure>
			       <center><img src="<?php echo $config['LIVE_URL'];?>views/images/howitworks1.png"></center>
				  </figure>
			      <figcaption style="color: #ff2600;text-align: center;font-size: 23px;">Download SeeMore<br>Interactive</figcaption>
			    </li>
			   <li>
			   	<figure>
			     <center><img src="<?php echo $config['LIVE_URL'];?>views/images/howitworks2.png"></center>
				</figure> 
			      <figcaption style="color: #ff2600;text-align: center;font-size: 23px;">Scan the Image</figcaption>
			     
			    </li>
			    <li>
			      <figure>
			        <center><img src="<?php echo $config['LIVE_URL'];?>views/images/howitworks3.png"></center>
				  </figure>
			      <figcaption style="color: #ff2600;text-align: center;font-size: 23px;">Unlock Exclusive<br>Experience</figcaption>
			    </li>
			   
			  </ul>
			</div>
			</div>
		</section>
		<section id="how-it-works">
			<div class="container">
				<div class="row">
					<h1>Here's how it works.</h1>
					<div class="col-md-3">
						<center><img src="<?php echo $config['LIVE_URL'];?>views/images/howitworks1.png"></center>
						<h4 class="text-center how-it-works_h4">Download SeeMore<br>Interactive</h4>
						
					</div>
					<div class="col-md-3">
						<center><img src="<?php echo $config['LIVE_URL'];?>views/images/howitworks2.png"></center>
						<h4 class="text-center how-it-works_h4">Scan the Image</h4>
						
					</div>
					<div class="col-md-3">
						<center><img src="<?php echo $config['LIVE_URL'];?>views/images/howitworks3.png"></center>
						<h4 class="text-center how-it-works_h4">Unlock Exclusive<br>Experience</h4>
						
					</div>
					
				</div>
			</div>
		</section>
		<section id="industries-slider">
			<h3 style="color:#fff;text-align:center;">These are some of the <br>industries we're perfect for.</h3>
			<br><br>
			<div class="flexslider">
			  <ul class="slides">
			    <li>
			      <figure>
			       <center><img src="<?php echo $config['LIVE_URL'];?>views/images/industries/service.png" /></center>
			      </figure>
			      <figcaption style="color: #fff;background: #414143;text-align: center;font-size: 23px;">Service</figcaption>
			     
			    </li>
			   <li>
			   	<figure>
			      <center><img src="<?php echo $config['LIVE_URL'];?>views/images/industries/retail.png" /></center>
			     </figure> 
			      <figcaption style="color: #fff;background: #414143;text-align: center;font-size: 23px;">Retail</figcaption>
			    </li>
			    <li>
			    	<figure>
			      <center><img src="<?php echo $config['LIVE_URL'];?>views/images/industries/consumer_goods.png" /></center>
			      </figure>
			      <figcaption style="color: #fff;background: #414143;text-align: center;font-size: 23px;">Consumer Goods</figcaption>

			    </li>
			    <li>
			    	<figure>
			      <center><img src="<?php echo $config['LIVE_URL'];?>views/images/industries/banking.png" /></center>
			      </figure>
			      <figcaption style="color: #fff;background: #414143;text-align: center;font-size: 23px;">Banking</figcaption>
			    </li> 
			     <li>
			    	<figure>
			      <center><img src="<?php echo $config['LIVE_URL'];?>views/images/industries/insurance.png" /></center>
			      </figure>
			      <figcaption style="color: #fff;background: #414143;text-align: center;font-size: 23px;">Insurance</figcaption>
			    </li> 
			     <li>
			    	<figure>
			      <center><img src="<?php echo $config['LIVE_URL'];?>views/images/industries/telecom.png" /></center>
			      </figure>
			      <figcaption style="color: #fff;background: #414143;text-align: center;font-size: 23px;">Telecom</figcaption>
			    </li> 
			     <li>
			    	<figure>
			      <center><img src="<?php echo $config['LIVE_URL'];?>views/images/industries/non_profit.png" /></center>
			      </figure>
			      <figcaption style="color: #fff;background: #414143;text-align: center;font-size: 23px;">Non Profit</figcaption>
			    </li> 
			     <li>
			    	<figure>
			      <center><img src="<?php echo $config['LIVE_URL'];?>views/images/industries/cable.png" /></center>
			      </figure>
			      <figcaption style="color: #fff;background: #414143;text-align: center;font-size: 23px;">Cable</figcaption>
			    </li> 
			     <li>
			    	<figure>
			      <center><img src="<?php echo $config['LIVE_URL'];?>views/images/industries/auto.png" /></center>
			      </figure>
			      <figcaption style="color: #fff;background: #414143;text-align: center;font-size: 23px;">Auto</figcaption>
			    </li> 
			    
			  </ul>
			</div>
		</section>
		<section id="industries">
			<div class="container">
				<div class="row" >
					<h1>These are some of the <br>industries we're perfect for.</h1>
					<br>
					<div class="col-md-3">
						<center><img src="<?php echo $config['LIVE_URL'];?>views/images/industries/service.png"></center>
						<h4 class="text-center industries_h4">Service</h4>
						
					</div>
					<div class="col-md-3">
						<center><img src="<?php echo $config['LIVE_URL'];?>views/images/industries/retail.png"></center>
						<h4 class="text-center industries_h4">Retail</h4>
						
					</div>
					<div class="col-md-3">
						<center><img src="<?php echo $config['LIVE_URL'];?>views/images/industries/consumer_goods.png"></center>
						<h4 class="text-center industries_h4">Consumer Goods</h4>
						
					</div>
					<div class="col-md-3">
						<center><img src="<?php echo $config['LIVE_URL'];?>views/images/industries/banking.png"></center>
						<h4 class="text-center industries_h4">Banking</h4>
						
					</div>
					<div class="col-md-3">
						<center><img src="<?php echo $config['LIVE_URL'];?>views/images/industries/insurance.png"></center>
						<h4 class="text-center industries_h4">Insurance</h4>
						
					</div>
					<div class="col-md-3">
						<center><img src="<?php echo $config['LIVE_URL'];?>views/images/industries/telecom.png"></center>
						<h4 class="text-center industries_h4">Telecom</h4>
						
					</div>	
					<div class="col-md-3">
						<center><img src="<?php echo $config['LIVE_URL'];?>views/images/industries/non_profit.png"></center>
						<h4 class="text-center industries_h4">Non Profit</h4>
						
					</div>
					<div class="col-md-3">
						<center><img src="<?php echo $config['LIVE_URL'];?>views/images/industries/cable.png"></center>
						<h4 class="text-center industries_h4">Cable</h4>
						
					</div>
					<div class="col-md-3">
						<center><img src="<?php echo $config['LIVE_URL'];?>views/images/industries/auto.png"></center>
						<h4 class="text-center industries_h4">Auto</h4>
						
					</div>	

					
					
				</div>
				<br><br><br>
				<div align="center">
                        <span class="slogan">Here's why we're perfect for you.</span>
					</div>
			</div>
		</section>
		<section id="what-we-can-slider">
			<div class="container">
				<h3 style="text-align:center;color:#ff2600">Here's what we can do.</h3>
				<br><br>
				<div class="flexslider">
				  <ul class="slides">
				    <li>
				      <figure>
				       <center><img src="<?php echo $config['LIVE_URL'];?>views/images/what_we_can/m_commerce.png"></center>
				      </figure>
				      <figcaption style="color: black;text-align: center;font-size: 23px;">M-Commerce</figcaption>
				      <p style="margin: auto;margin-left: 6px;">Our SaaS, cloud platform enables smart phone and tablet viewers to engage with your product, any time, anywhere, bridging the physical and digital world. If you can see it, we can make it shoppable.</p>
					    
				    </li>
				     <li>
				      <figure>
				       <center><img src="<?php echo $config['LIVE_URL'];?>views/images/what_we_can/dynamic_recom.png"></center>
				      </figure>
				      <figcaption style="color: black;text-align: center;font-size: 23px;">Dynamic Recommendations</figcaption>
				      <p style="margin: auto;margin-left: 6px;">Patented image-recognition platform triggers offers and recommendations from images scanned with a mobile device. Every scan can deliver new and personalized content to make buying easier.</p>
					    
				    </li>
				     <li>
				      <figure>
				       <center><a  style="text-decoration:none;" href="<?php echo $config['LIVE_URL'];?>views/videos/features/Calendar_GPS_Sensor_Integration.mp4" class="html5lightbox" data-width="480" data-height="320" title="Coupons & Offers"><img src="<?php echo $config['LIVE_URL'];?>views/images/what_we_can/coupons_offers.png"></a></center>
				      </figure>
				      <figcaption style="color: black;text-align: center;font-size: 23px;">Coupons & Offers</figcaption>
				      <p style="margin: auto;margin-left: 6px;">Access saved offers from any iOS and Android device, and get reminders before offer expire, and when you're near the store. We can even share personalized offers directly to mobile based on business intelligence acquired from the consumer.</p>
					    
				    </li>
				     <li>
				      <figure>
				       <center><a  style="text-decoration:none;" href="<?php echo $config['LIVE_URL'];?>views/videos/features/mobile_wish_list.mp4" class="html5lightbox" data-width="480" data-height="320" title="Virtual Closet"><img src="<?php echo $config['LIVE_URL'];?>views/images/what_we_can/virtual_closet.png"></a></center>
					  </figure>
				      <figcaption style="color: black;text-align: center;font-size: 23px;">Virtual Closet</figcaption>
				      <p style="margin: auto;margin-left: 6px;">Store your favorite items in a virtual closet and re-engage with whatever you'd like. Save it, buy it later, share with friends, add to wish list, and view new recommendations 24/7.</p>
					    
				    </li>
				     <li>
				      <figure>
				       <center><a  style="text-decoration:none;" href="<?php echo $config['LIVE_URL'];?>views/videos/features/3d.mp4" class="html5lightbox" data-width="480" data-height="320" title="3D Augmented Reality"><img src="<?php echo $config['LIVE_URL'];?>views/images/what_we_can/augreality.png"></a></center>
					  </figure>
				      <figcaption style="color: black;text-align: center;font-size: 23px;">3D Augmented Reality</figcaption>
				      <p style="margin: auto;margin-left: 6px;">Superimpose digital content into the real world. Educate, entertain and convert: 3D let's you explore product features no matter where you are.</p>
					    
				    </li>
				     <li>
				      <figure>
				      <center><a  style="text-decoration:none;" href="<?php echo $config['LIVE_URL'];?>views/videos/features/shopper_vision.mp4" class="html5lightbox" data-width="480" data-height="320" title="ShopperVisionTM"><img src="<?php echo $config['LIVE_URL'];?>views/images/what_we_can/shopper_vision.png"></i></a></center>
						</figure>
				      <figcaption style="color: black;text-align: center;font-size: 23px;">ShopperVision&trade;</figcaption>
				      <p style="margin: auto;margin-left: 6px;">A simple scan brings static content into real world. Try it on, see how it looks, and share it with your social network. Reduce return risk and build a bigger basket.</p>
					    
				    </li>
				     <li>
				      <figure>
				      <center><a  style="text-decoration:none;" href="<?php echo $config['LIVE_URL'];?>views/videos/features/mobile_wish_list.mp4" class="html5lightbox" data-width="480" data-height="320" title="Mobile Wish List"><img src="<?php echo $config['LIVE_URL'];?>views/images/what_we_can/wishlist.png"></a></center>
						</figure>
				      <figcaption style="color: black;text-align: center;font-size: 23px;">Mobile Wish List</figcaption>
				      <p style="margin: auto;margin-left: 6px;">Create unlimited wish lists with items saved in your personal Virtual Closet. Share with your friends, browse new items or purchase any time.</p>
					    
				    </li>
				     <li>
				      <figure>
				      <center><img src="<?php echo $config['LIVE_URL'];?>views/images/what_we_can/business.png"></center>
					  </figure>
				      <figcaption style="color: black;text-align: center;font-size: 23px;">Business Intelligence & Data Mining</figcaption>
				      <p style="margin: auto;margin-left: 6px;">Every activity performed with our technology can be tracked and measured. Let the numbers do the talking and increase conversion with fine-tuned marketing efforts based on open rates, dwell time, item and offer capture and social sharing.</p>
					    
				    </li>
				     <li>
				      <figure>
				       <center><img src="<?php echo $config['LIVE_URL'];?>views/images/what_we_can/calender_gps.png"></center>
						
				      </figure>
				      <figcaption style="color: black;text-align: center;font-size: 23px;">Calendar, GPS & Sensor Integration</figcaption>
				      <p style="margin: auto;margin-left: 6px;">Offers captured via a mobile device recieve calendar notifications, while geo-fence services and beacons alert you of offers based on your indoor and outdoor location proximity.</p>
					    
				    </li>
				     <li>
				      <figure>
				       <center><a  style="text-decoration:none;" href="<?php echo $config['LIVE_URL'];?>views/videos/features/membership_activation.mp4" class="html5lightbox" data-width="480" data-height="320" title="Card & Membership Activation"><img src="<?php echo $config['LIVE_URL'];?>views/images/what_we_can/membership.png"></a></center>
						</figure>
				      <figcaption style="color: black;text-align: center;font-size: 23px;">Card & Membership Activation</figcaption>
				      <p style="margin: auto;margin-left: 6px;">Educate your customer about membership and simplify the activation process. A  scan enables traceable activation forms to save, which are ideal for lead generation. Plus location-based services facilitate redirection to regional partners or call centers to help increase conversion.</p>
					    
				    </li>

				    <li>
				      <figure>
				       <center><img src="<?php echo $config['LIVE_URL'];?>views/images/what_we_can/interactive.png"></center>
						</figure>
				      <figcaption style="color: black;text-align: center;font-size: 23px;">Interactive Video</figcaption>
				      <p style="margin: auto;margin-left: 6px;">Learn more about products and services with a simple scan. Watch engaging, interactive videos that link to more information, recommendations, and opportunities to purchase.</p>
					    
				    </li>
				    <li>
				      <figure>
				       <center><img src="<?php echo $config['LIVE_URL'];?>views/images/what_we_can/m_commerce.png"></center>
						</figure>
				      <figcaption style="color: black;text-align: center;font-size: 23px;">Personalization</figcaption>
				      <p style="margin: auto;margin-left: 6px;">Campaigns can be up and running in hours on secure servers. Integrated database calls enable delivery of personalized messages, offers and recommendations based on user history and location. One image can provide different content with unlimited segmentation opportunities to personalize the experience.</p>
					    
				    </li>
				    <li>
				      <figure>
				       <center><img src="<?php echo $config['LIVE_URL'];?>views/images/what_we_can/brand.png"></center>
						</figure>
				      <figcaption style="color: black;text-align: center;font-size: 23px;">Your Brand is the Hero</figcaption>
				      <p style="margin: auto;margin-left: 6px;">SeeMore integrates your branding and graphic assets into the experience.</p>
					    
				    </li>
				    <li>
				      <figure>
				       <center><img src="<?php echo $config['LIVE_URL'];?>views/images/what_we_can/social_shopper.png"></center>
						</figure>
				      <figcaption style="color: black;text-align: center;font-size: 23px;">Social Shopper</figcaption>
				      <p style="margin: auto;margin-left: 6px;">Scan, unlock and share for valuable feedback from friends and family. All campaigns include Facebook, Twitter, email and SMS sharing.</p>
					    
				    </li>
				    <li>
				      <figure>
				       <center><img src="<?php echo $config['LIVE_URL'];?>views/images/what_we_can/reward.png"></center>
						</figure>
				      <figcaption style="color: black;text-align: center;font-size: 23px;">Reward Programs</figcaption>
				      <p style="margin: auto;margin-left: 6px;">Capture deals or reward points via the mobile device, and get alerted based on calendar notifications and geo-location reminders.</p>
				    </li>
				  </ul>
			    </div>
			</div>
		</section>
		<section id="what-we-can">
			<div class="container">
				<h1>Here's what we can do.</h1>
				<hr>	
				<div class="row">
					<div class="col-md-3">
						<center><img src="<?php echo $config['LIVE_URL'];?>views/images/what_we_can/m_commerce.png"></center>
						<h4 class="text-center what-we-can_h4">M-Commerce</h4>
						<p style="width: 75%;margin: auto;">Our SaaS, cloud platform enables smart phone and tablet viewers to engage with your product, any time, anywhere, bridging the physical and digital world. If you can see it, we can make it shoppable.</p>
					</div>
					<div class="col-md-3">
						<center><img src="<?php echo $config['LIVE_URL'];?>views/images/what_we_can/dynamic_recom.png"></center>
						<h4 class="text-center what-we-can_h4">Dynamic<br>Recommendations</h4>
						<p style="width: 75%;margin: auto;">Patented image-recognition platform triggers offers and recommendations from images scanned with a mobile device. Every scan can deliver new and personalized content to make buying easier.</p>
					</div>
					<div class="col-md-3">
						<center><a  style="text-decoration:none;" href="<?php echo $config['LIVE_URL'];?>views/videos/features/Calendar_GPS_Sensor_Integration.mp4" class="html5lightbox" data-width="480" data-height="320" title="Coupons & Offers"><img src="<?php echo $config['LIVE_URL'];?>views/images/what_we_can/coupons_offers.png"></i></a></center>
						<h4 class="text-center what-we-can_h4">Coupons & Offers</h4>
						<p style="width: 77%;margin: auto;">Access saved offers from any iOS and Android device, and get reminders before offer expire, and when you're near the store. We can even share personalized offers directly to mobile based on business intelligence acquired from the consumer.</p>
					</div>
				</div>
				<hr>
				<div class="row">	
					<div class="col-md-3">
						<center><a  style="text-decoration:none;" href="<?php echo $config['LIVE_URL'];?>views/videos/features/mobile_wish_list.mp4" class="html5lightbox" data-width="480" data-height="320" title="Virtual Closet"><img src="<?php echo $config['LIVE_URL'];?>views/images/what_we_can/virtual_closet.png"></a></center>
						<h4 class="text-center what-we-can_h4">Virtual Closet</h4>
						<p style="width: 75%;margin: auto;">Store your favorite items in a virtual closet and re-engage with whatever you'd like. Save it, buy it later, share with friends, add to wish list, and view new recommendations 24/7.</p>
					</div>
					<div class="col-md-3">
						<center><a  style="text-decoration:none;" href="<?php echo $config['LIVE_URL'];?>views/videos/features/3d.mp4" class="html5lightbox" data-width="480" data-height="320" title="3D Augmented Reality"><img src="<?php echo $config['LIVE_URL'];?>views/images/what_we_can/augreality.png"></a></center>
						<h4 class="text-center what-we-can_h4">3D Augmented Reality</h4>
						<p style="width: 75%;margin: auto;">Superimpose digital content into the real world. Educate, entertain and convert: 3D let's you explore product features no matter where you are.</p>
					</div>
					<div class="col-md-3">
						<center><a  style="text-decoration:none;" href="<?php echo $config['LIVE_URL'];?>views/videos/features/shopper_vision.mp4" class="html5lightbox" data-width="480" data-height="320" title="ShopperVisionTM"><img src="<?php echo $config['LIVE_URL'];?>views/images/what_we_can/shopper_vision.png"></i></a></center>
						<h4 class="text-center what-we-can_h4">ShopperVisionTM</h4>
						<p style="width: 77%;margin: auto;">A simple scan brings static content into real world. Try it on, see how it looks, and share it with your social network. Reduce return risk and build a bigger basket.</p>
					</div>
				</div>
				<hr>
				
				<div class="row">	
					<div class="col-md-3">
						<center><a  style="text-decoration:none;" href="<?php echo $config['LIVE_URL'];?>views/videos/features/mobile_wish_list.mp4" class="html5lightbox" data-width="480" data-height="320" title="Mobile Wish List"><img src="<?php echo $config['LIVE_URL'];?>views/images/what_we_can/wishlist.png"></a></center>
						<h4 class="text-center what-we-can_h4">Mobile Wish List</h4>
						<p style="width: 75%;margin: auto;">Create unlimited wish lists with items saved in your personal Virtual Closet. Share with your friends, browse new items or purchase any time.</p>
					</div>
					<div class="col-md-3">
						<center><img src="<?php echo $config['LIVE_URL'];?>views/images/what_we_can/business.png"></center>
						<h4 class="text-center what-we-can_h4">Business Intelligence & <br> Data Mining</h4>
						<p style="width: 75%;margin: auto;">Every activity performed with our technology can be tracked and measured. Let the numbers do the talking and increase conversion with fine-tuned marketing efforts based on open rates, dwell time, item and offer capture and social sharing.</p>
					</div>
					<div class="col-md-3">
						<center><img src="<?php echo $config['LIVE_URL'];?>views/images/what_we_can/calender_gps.png"></center>
						<h4 class="text-center what-we-can_h4">Calendar, GPS & <br>Sensor Integration</h4>
						<p style="width: 77%;margin: auto;">Offers captured via a mobile device recieve calendar notifications, while geo-fence services and beacons alert you of offers based on your indoor and outdoor location proximity.</p>
					</div>
				</div>
				<hr>
				
				<div class="row">	
					<div class="col-md-3">
						<center><a  style="text-decoration:none;" href="<?php echo $config['LIVE_URL'];?>views/videos/features/membership_activation.mp4" class="html5lightbox" data-width="480" data-height="320" title="Card & Membership Activation"><img src="<?php echo $config['LIVE_URL'];?>views/images/what_we_can/membership.png"></a></center>
						<h4 class="text-center what-we-can_h4">Card & Membership<br> Activation</h4>
						<p style="width: 75%;margin: auto;">Educate your customer about membership and simplify the activation process. A  scan enables traceable activation forms to save, which are ideal for lead generation. Plus location-based services facilitate redirection to regional partners or call centers to help increase conversion.</p>
					</div>
					<div class="col-md-3">
						<center><img src="<?php echo $config['LIVE_URL'];?>views/images/what_we_can/interactive.png"></center>
						<h4 class="text-center what-we-can_h4">Interactive Video</h4>
						<p style="width: 75%;margin: auto;">Learn more about products and services with a simple scan. Watch engaging, interactive videos that link to more information, recommendations, and opportunities to purchase.</p>
					</div>
					<div class="col-md-3">
						<center><img src="<?php echo $config['LIVE_URL'];?>views/images/what_we_can/m_commerce.png"></center>
						<h4 class="text-center what-we-can_h4">Personalization</h4>
						<p style="width: 77%;margin: auto;">Campaigns can be up and running in hours on secure servers. Integrated database calls enable delivery of personalized messages, offers and recommendations based on user history and location. One image can provide different content with unlimited segmentation opportunities to personalize the experience.</p>
					</div>
				</div>
				<hr>
				
				<div class="row">	
					<div class="col-md-3">
						<center><img src="<?php echo $config['LIVE_URL'];?>views/images/what_we_can/brand.png"></center>
						<h4 class="text-center what-we-can_h4">Your Brand is the Hero</h4>
						<p style="width: 75%;margin: auto;">SeeMore integrates your branding and graphic assets into the experience.</p>
					</div>
					<div class="col-md-3">
						<center><img src="<?php echo $config['LIVE_URL'];?>views/images/what_we_can/social_shopper.png"></center>
						<h4 class="text-center what-we-can_h4">Social Shopper</h4>
						<p style="width: 75%;margin: auto;">Scan, unlock and share for valuable feedback from friends and family. All campaigns include Facebook, Twitter, email and SMS sharing.</p>
					</div>
					<div class="col-md-3">
						<center><img src="<?php echo $config['LIVE_URL'];?>views/images/what_we_can/reward.png"></center>
						<h4 class="text-center what-we-can_h4">Reward Programs</h4>
						<p style="width: 77%;margin: auto;">Capture deals or reward points via the mobile device, and get alerted based on calendar notifications and geo-location reminders.</p>
					</div>
				</div>
			</div>
		</section>
		<section id="markers-slider">
		
			<h3 style="color:#ff2600;text-align:center;">See for yourself.</h3>
			<div class="slogan" style="margin-bottom: 10px;margin-top:10px;text-align:center">Download SeeMore Interactive, Click an image, Scan with Mobile</div>
			<br>
			<div class="container">
				<div class="flexslider">
			  <ul class="slides">
			  <li>
				  <div class="picholder">
			        <figure>
			          <center><img class="fancypics" src="<?php echo $config['LIVE_URL'];?>views/images/markers/marker1.png"></center>
					  <div class="overlay"><a style="color:white;" href="JavaScript:html5Lightbox.showLightbox(0, '<?php echo $config['LIVE_URL'];?>views/images/markers/marker1_large.png', '<div style=\'float:left;width:58%;\'><a href=\'https://itunes.apple.com/us/app/seemore-interactive/id591304180?mt=8\' target=\'_blank\'><img width=\'75\' src=\'<?php echo $config['LIVE_URL'];?>views/images/appstore_icon.png\' alt=\'apple\'></a>&nbsp;&nbsp;<a href=\'https://play.google.com/store/apps/details?id=com.seemoreinteractive.seemoreinteractive\' target=\'_blank\'><img width=\'75\' src=\'<?php echo $config['LIVE_URL'];?>views/images/android_icon.png\' alt=\'apple\'></a></div><div style=\'float:left;width:42%;font-size:12px;text-align:center;\'> <span><strong>Scan this image</strong> with our app</span></div>' );"><p class="text_box"><img style="width:35%;margin:auto;" src="<?php echo $config['LIVE_URL'];?>views/images/hovericon.png" alt="hover icon">Click Here</p></a></div>
				    </figure>
				  </div>
			   </li>
			   <li>
			    <div class="picholder">
			       <figure>
				      <center><img class="fancypics" src="<?php echo $config['LIVE_URL'];?>views/images/markers/podi.png"></center>
					  <div class="overlay"><a style="color:white;" href="JavaScript:html5Lightbox.showLightbox(0, '<?php echo $config['LIVE_URL'];?>views/images/markers/podi_large.png', '<div style=\'float:left;width:58%;\'><a href=\'https://itunes.apple.com/us/app/seemore-interactive/id591304180?mt=8\' target=\'_blank\'><img width=\'75\' src=\'<?php echo $config['LIVE_URL'];?>views/images/appstore_icon.png\' alt=\'apple\'></a>&nbsp;&nbsp;<a href=\'https://play.google.com/store/apps/details?id=com.seemoreinteractive.seemoreinteractive\' target=\'_blank\'><img width=\'75\' src=\'<?php echo $config['LIVE_URL'];?>views/images/android_icon.png\' alt=\'apple\'></a></div><div style=\'float:left;width:42%;font-size:12px;text-align:center;\'> <span><strong>Scan this image</strong> with our app</span></div>' );"><p class="text_box"><img style="width:35%;margin:auto;" src="<?php echo $config['LIVE_URL'];?>views/images/hovericon.png" alt="hover icon">Click Here</p></a></div>
			        </figure> 
				</div>	
			    </li>
			    <li>
				<div class="picholder">
			      <figure>
				      <center><img class="fancypics" src="<?php echo $config['LIVE_URL'];?>views/images/markers/marker3.png"></center>
					  <div class="overlay"><a style="color:white;" href="JavaScript:html5Lightbox.showLightbox(0, '<?php echo $config['LIVE_URL'];?>views/images/markers/marker3_large.png', '<div style=\'float:left;width:58%;\'><a href=\'https://itunes.apple.com/us/app/seemore-interactive/id591304180?mt=8\' target=\'_blank\'><img width=\'75\' src=\'<?php echo $config['LIVE_URL'];?>views/images/appstore_icon.png\' alt=\'apple\'></a>&nbsp;&nbsp;<a href=\'https://play.google.com/store/apps/details?id=com.seemoreinteractive.seemoreinteractive\' target=\'_blank\'><img width=\'75\' src=\'<?php echo $config['LIVE_URL'];?>views/images/android_icon.png\' alt=\'apple\'></a></div><div style=\'float:left;width:42%;font-size:12px;text-align:center;\'> <span><strong>Scan this image</strong> with our app</span></div>' );"><p class="text_box"><img style="width:35%;margin:auto;" src="<?php echo $config['LIVE_URL'];?>views/images/hovericon.png" alt="hover icon">Click Here</p></a></div>
			      </figure>
				 </div> 
			    </li>
			    <li>
				 <div class="picholder">
			      <figure>
				      <center><img class="fancypics" src="<?php echo $config['LIVE_URL'];?>views/images/markers/marker5.png"></center>
					  <div class="overlay"><a style="color:white;" href="JavaScript:html5Lightbox.showLightbox(0, '<?php echo $config['LIVE_URL'];?>views/images/markers/marker5_large.png', '<div style=\'float:left;width:58%;\'><a href=\'https://itunes.apple.com/us/app/seemore-interactive/id591304180?mt=8\' target=\'_blank\'><img width=\'75\' src=\'<?php echo $config['LIVE_URL'];?>views/images/appstore_icon.png\' alt=\'apple\'></a>&nbsp;&nbsp;<a href=\'https://play.google.com/store/apps/details?id=com.seemoreinteractive.seemoreinteractive\' target=\'_blank\'><img width=\'75\' src=\'<?php echo $config['LIVE_URL'];?>views/images/android_icon.png\' alt=\'apple\'></a></div><div style=\'float:left;width:42%;font-size:12px;text-align:center;\'> <span><strong>Scan this image</strong> with our app</span></div>' );"><p class="text_box"><img style="width:35%;margin:auto;" src="<?php echo $config['LIVE_URL'];?>views/images/hovericon.png" alt="hover icon">Click Here</p></a></div>
			      </figure>
				 </div> 
			     </li>
			     <li>
				 <div class="picholder">
			      <figure>
				      <center><img class="fancypics" src="<?php echo $config['LIVE_URL'];?>views/images/markers/charmingcharlie.png"></center>
					  <div class="overlay"><a style="color:white;" href="JavaScript:html5Lightbox.showLightbox(0, '<?php echo $config['LIVE_URL'];?>views/images/markers/charmingcharlie_large.png', '<div style=\'float:left;width:58%;\'><a href=\'https://itunes.apple.com/us/app/seemore-interactive/id591304180?mt=8\' target=\'_blank\'><img width=\'75\' src=\'<?php echo $config['LIVE_URL'];?>views/images/appstore_icon.png\' alt=\'apple\'></a>&nbsp;&nbsp;<a href=\'https://play.google.com/store/apps/details?id=com.seemoreinteractive.seemoreinteractive\' target=\'_blank\'><img width=\'75\' src=\'<?php echo $config['LIVE_URL'];?>views/images/android_icon.png\' alt=\'apple\'></a></div><div style=\'float:left;width:42%;font-size:12px;text-align:center;\'> <span><strong>Scan this image</strong> with our app</span></div>' );"><p class="text_box"><img style="width:35%;margin:auto;" src="<?php echo $config['LIVE_URL'];?>views/images/hovericon.png" alt="hover icon">Click Here</p></a></div>
			      
			      </figure>
				 </div> 
			     </li>
			     <li>
				 <div class="picholder">
			      <figure>
			      	<center><img class="fancypics" src="<?php echo $config['LIVE_URL'];?>views/images/markers/wowchart.png"></center>
					<div class="overlay"><a style="color:white;" href="JavaScript:html5Lightbox.showLightbox(0, '<?php echo $config['LIVE_URL'];?>views/images/markers/wowchart_large.png', '<div style=\'float:left;width:58%;\'><a href=\'https://itunes.apple.com/us/app/seemore-interactive/id591304180?mt=8\' target=\'_blank\'><img width=\'75\' src=\'<?php echo $config['LIVE_URL'];?>views/images/appstore_icon.png\' alt=\'apple\'></a>&nbsp;&nbsp;<a href=\'https://play.google.com/store/apps/details?id=com.seemoreinteractive.seemoreinteractive\' target=\'_blank\'><img width=\'75\' src=\'<?php echo $config['LIVE_URL'];?>views/images/android_icon.png\' alt=\'apple\'></a></div><div style=\'float:left;width:42%;font-size:12px;text-align:center;\'> <span><strong>Scan this image</strong> with our app</span></div>' );"><p class="text_box"><img style="width:35%;margin:auto;" src="<?php echo $config['LIVE_URL'];?>views/images/hovericon.png" alt="hover icon">Click Here</p></a></div>
					
			      </figure>
				 </div> 
			     </li>
			   	 <li>
				 <div class="picholder">
			      <figure>
			      	<center><img class="fancypics" src="<?php echo $config['LIVE_URL'];?>views/images/markers/ftf-marker.png"></center>
					<div class="overlay"><a style="color:white;" href="JavaScript:html5Lightbox.showLightbox(0, '<?php echo $config['LIVE_URL'];?>views/images/markers/ftf-marker_large.png', '<div style=\'float:left;width:58%;\'><a href=\'https://itunes.apple.com/us/app/seemore-interactive/id591304180?mt=8\' target=\'_blank\'><img width=\'75\' src=\'<?php echo $config['LIVE_URL'];?>views/images/appstore_icon.png\' alt=\'apple\'></a>&nbsp;&nbsp;<a href=\'https://play.google.com/store/apps/details?id=com.seemoreinteractive.seemoreinteractive\' target=\'_blank\'><img width=\'75\' src=\'<?php echo $config['LIVE_URL'];?>views/images/android_icon.png\' alt=\'apple\'></a></div><div style=\'float:left;width:42%;font-size:12px;text-align:center;\'> <span><strong>Scan this image</strong> with our app</span></div>' );"><p class="text_box"><img style="width:35%;margin:auto;" src="<?php echo $config['LIVE_URL'];?>views/images/hovericon.png" alt="hover icon">Click Here</p></a></div>
					
			      </figure>
				 </div> 
			     </li>	
			  </ul>
			</div>
		   <div class="icons" align="center"><a href="https://itunes.apple.com/us/app/seemore-interactive/id591304180?mt=8" target="_blank"><img src="<?php echo $config['LIVE_URL'];?>views/images/appstore_icon.png" alt="apple"></a>&nbsp;&nbsp;<a href="https://play.google.com/store/apps/details?id=com.seemoreinteractive.seemoreinteractive" target="_blank"><img src="<?php echo $config['LIVE_URL'];?>views/images/android_icon.png" alt="apple"></a></div>

			</div>
		</section>
		
		<section id="markers">
			<div class="container">
				<h1>See for yourself.</h1>
				<div class="slogan" style="margin-bottom: 30px;">Download SeeMore Interactive, Click an image, Scan with Mobile</div>
				<div class="row">
					<div class="col-md-13">
					    <div class="picholder">
						   <center><img class="fancypics" src="<?php echo $config['LIVE_URL'];?>views/images/markers/marker1.png"></center>
						   <div class="overlay"><a  style="color:white;" href="JavaScript:html5Lightbox.showLightbox(0, '<?php echo $config['LIVE_URL'];?>views/images/markers/marker1_large.png', '<div style=\'float:left;width:68%;\'><a href=\'https://itunes.apple.com/us/app/seemore-interactive/id591304180?mt=8\' target=\'_blank\'><img width=\'120\' src=\'<?php echo $config['LIVE_URL'];?>views/images/appstore_icon.png\' alt=\'apple\'></a>&nbsp;&nbsp;<a href=\'https://play.google.com/store/apps/details?id=com.seemoreinteractive.seemoreinteractive\' target=\'_blank\'><img width=\'120\' src=\'<?php echo $config['LIVE_URL'];?>views/images/android_icon.png\' alt=\'apple\'></a></div><div style=\'float:left;width:32%;font-size:12px;text-align:center;\'> <span><strong>Scan this image</strong> with our app</span></div>');"><p class="text_box"><img src="<?php echo $config['LIVE_URL'];?>views/images/hovericon.png" alt="hover icon">Click Here</p></a></div>
						</div>   
					</div>
					<div class="col-md-13">
					    <div class="picholder">
						    <center><img class="fancypics" src="<?php echo $config['LIVE_URL'];?>views/images/markers/podi.png"></a></center>
						    <div class="overlay"><a  style="color:white;" href="JavaScript:html5Lightbox.showLightbox(0, '<?php echo $config['LIVE_URL'];?>views/images/markers/podi_large.png', '<div style=\'float:left;width:70%;\'><a href=\'https://itunes.apple.com/us/app/seemore-interactive/id591304180?mt=8\' target=\'_blank\'><img width=\'120\' src=\'<?php echo $config['LIVE_URL'];?>views/images/appstore_icon.png\' alt=\'apple\'></a>&nbsp;&nbsp;<a href=\'https://play.google.com/store/apps/details?id=com.seemoreinteractive.seemoreinteractive\' target=\'_blank\'><img width=\'120\' src=\'<?php echo $config['LIVE_URL'];?>views/images/android_icon.png\' alt=\'apple\'></a></div><div style=\'float:left;width:29%;font-size:12px;text-align:center;\'> <span><strong>Scan this image</strong> with our app</span></div>');"><p class="text_box"><img src="<?php echo $config['LIVE_URL'];?>views/images/hovericon.png" alt="hover icon">Click Here</p></a></div>
						</div>
					</div>
					<div class="col-md-13">
					    <div class="picholder">
						    <center><img class="fancypics" src="<?php echo $config['LIVE_URL'];?>views/images/markers/marker3.png"></a></center>
						    <div class="overlay"><a  style="color:white;" href="JavaScript:html5Lightbox.showLightbox(0, '<?php echo $config['LIVE_URL'];?>views/images/markers/marker3_large.png', '<div style=\'float:left;width:68%;\'><a href=\'https://itunes.apple.com/us/app/seemore-interactive/id591304180?mt=8\' target=\'_blank\'><img width=\'120\' src=\'<?php echo $config['LIVE_URL'];?>views/images/appstore_icon.png\' alt=\'apple\'></a>&nbsp;&nbsp;<a href=\'https://play.google.com/store/apps/details?id=com.seemoreinteractive.seemoreinteractive\' target=\'_blank\'><img width=\'120\' src=\'<?php echo $config['LIVE_URL'];?>views/images/android_icon.png\' alt=\'apple\'></a></div><div style=\'float:left;width:32%;font-size:12px;text-align:center;\'> <span><strong>Scan this image</strong> with our app</span></div>');"><p class="text_box"><img src="<?php echo $config['LIVE_URL'];?>views/images/hovericon.png" alt="hover icon">Click Here</p></a></div>
						</div>
					</div>
					<div class="col-md-13">
					    <div class="picholder">
						    <center><img class="fancypics" src="<?php echo $config['LIVE_URL'];?>views/images/markers/marker4.png"></a></center>
						    <div class="overlay"><a  style="color:white;" href="JavaScript:html5Lightbox.showLightbox(0, '<?php echo $config['LIVE_URL'];?>views/images/markers/marker4_large.png', '<div style=\'float:left;width:68%;\'><a href=\'https://itunes.apple.com/us/app/seemore-interactive/id591304180?mt=8\' target=\'_blank\'><img width=\'120\' src=\'<?php echo $config['LIVE_URL'];?>views/images/appstore_icon.png\' alt=\'apple\'></a>&nbsp;&nbsp;<a href=\'https://play.google.com/store/apps/details?id=com.seemoreinteractive.seemoreinteractive\' target=\'_blank\'><img width=\'120\' src=\'<?php echo $config['LIVE_URL'];?>views/images/android_icon.png\' alt=\'apple\'></a></div><div style=\'float:left;width:32%;font-size:12px;text-align:center;\'> <span><strong>Scan this image</strong> with our app</span></div>');"><p class="text_box"><img src="<?php echo $config['LIVE_URL'];?>views/images/hovericon.png" alt="hover icon">Click Here</p></a></div>
						</div>
					</div>
					<div class="col-md-13">
					    <div class="picholder">
						    <center><img class="fancypics" src="<?php echo $config['LIVE_URL'];?>views/images/markers/marker5.png"></a></center>
						    <div class="overlay"><a  style="color:white;" href="JavaScript:html5Lightbox.showLightbox(0, '<?php echo $config['LIVE_URL'];?>views/images/markers/marker5_large.png', '<div style=\'float:left;width:68%;\'><a href=\'https://itunes.apple.com/us/app/seemore-interactive/id591304180?mt=8\' target=\'_blank\'><img width=\'120\' src=\'<?php echo $config['LIVE_URL'];?>views/images/appstore_icon.png\' alt=\'apple\'></a>&nbsp;&nbsp;<a href=\'https://play.google.com/store/apps/details?id=com.seemoreinteractive.seemoreinteractive\' target=\'_blank\'><img width=\'120\' src=\'<?php echo $config['LIVE_URL'];?>views/images/android_icon.png\' alt=\'apple\'></a></div><div style=\'float:left;width:32%;font-size:12px;text-align:center;\'> <span><strong>Scan this image</strong> with our app</span></div>');"><p class="text_box"><img src="<?php echo $config['LIVE_URL'];?>views/images/hovericon.png" alt="hover icon">Click Here</p></a></div>
						</div>
					</div>
					
					
				</div>
				<br><br>
				<div class="row">
				    <div class="col-md-12" style="text-align: center;width:100%">
					    <div class="picholder" style="display: inline-block;width: 20%;    ">
						    <center><img class="fancypics" src="<?php echo $config['LIVE_URL'];?>views/images/markers/wowchart.png"></a></center>
						    <div class="overlay"><a  style="color:white;" href="JavaScript:html5Lightbox.showLightbox(0, '<?php echo $config['LIVE_URL'];?>views/images/markers/wowchart_large.png', '<div style=\'float:left;width:65%;\'><a href=\'https://itunes.apple.com/us/app/seemore-interactive/id591304180?mt=8\' target=\'_blank\'><img width=\'120\' src=\'<?php echo $config['LIVE_URL'];?>views/images/appstore_icon.png\' alt=\'apple\'></a>&nbsp;&nbsp;<a href=\'https://play.google.com/store/apps/details?id=com.seemoreinteractive.seemoreinteractive\' target=\'_blank\'><img width=\'120\' src=\'<?php echo $config['LIVE_URL'];?>views/images/android_icon.png\' alt=\'apple\'></a></div><div style=\'float:left;width:23%;font-size:12px;text-align:center;\'> <span><strong>Scan this image</strong> with our app</span></div>');"><p class="text_box"><img src="<?php echo $config['LIVE_URL'];?>views/images/hovericon.png" alt="hover icon"><br>Click Here</p></a></div>
						</div>
						<div class="picholder" style="display: inline-block;width: 20%;">
						    <center><img class="fancypics" src="<?php echo $config['LIVE_URL'];?>views/images/markers/charmingcharlie.png"></a></center>
						    <div class="overlay"><a  style="color:white;" href="JavaScript:html5Lightbox.showLightbox(0, '<?php echo $config['LIVE_URL'];?>views/images/markers/charmingcharlie_large.png', '<div style=\'float:left;width:64%;\'><a href=\'https://itunes.apple.com/us/app/seemore-interactive/id591304180?mt=8\' target=\'_blank\'><img width=\'100\' src=\'<?php echo $config['LIVE_URL'];?>views/images/appstore_icon.png\' alt=\'apple\'></a>&nbsp;&nbsp;<a href=\'https://play.google.com/store/apps/details?id=com.seemoreinteractive.seemoreinteractive\' target=\'_blank\'><img width=\'100\' src=\'<?php echo $config['LIVE_URL'];?>views/images/android_icon.png\' alt=\'apple\'></a></div><div style=\'float:left;width:36%;font-size:12px;text-align:center;\'> <span><strong>Scan this image</strong> with our app</span></div>');"><p class="text_box"><img src="<?php echo $config['LIVE_URL'];?>views/images/hovericon.png" alt="hover icon"><br>Click Here</p></a></div>
						</div>
						<div class="picholder" style="display: inline-block;width: 20%;">
						    <center><img class="fancypics" src="<?php echo $config['LIVE_URL'];?>views/images/markers/ftf-marker.png"></a></center>
						    <div class="overlay"><a  style="color:white;" href="JavaScript:html5Lightbox.showLightbox(0, '<?php echo $config['LIVE_URL'];?>views/images/markers/ftf-marker_large.png', '<div style=\'float:left;width:65%;\'><a href=\'https://itunes.apple.com/us/app/seemore-interactive/id591304180?mt=8\' target=\'_blank\'><img width=\'100\' src=\'<?php echo $config['LIVE_URL'];?>views/images/appstore_icon.png\' alt=\'apple\'></a>&nbsp;&nbsp;<a href=\'https://play.google.com/store/apps/details?id=com.seemoreinteractive.seemoreinteractive\' target=\'_blank\'><img width=\'100\' src=\'<?php echo $config['LIVE_URL'];?>views/images/android_icon.png\' alt=\'apple\'></a></div><div style=\'float:left;width:25%;font-size:12px;text-align:center;\'> <span><strong>Scan this image</strong> with our app</span></div>');"><p class="text_box"><img src="<?php echo $config['LIVE_URL'];?>views/images/hovericon.png" alt="hover icon"><br>Click Here</p></a></div>
						</div>
					</div>
				
				
					
					
					
				</div>	
					

				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
			
				<div class="icons"><a href="https://itunes.apple.com/us/app/seemore-interactive/id591304180?mt=8" target="_blank"><img src="<?php echo $config['LIVE_URL'];?>views/images/appstore_icon.png" alt="apple"></a>&nbsp;&nbsp;<a href="https://play.google.com/store/apps/details?id=com.seemoreinteractive.seemoreinteractive" target="_blank"><img src="<?php echo $config['LIVE_URL'];?>views/images/android_icon.png" alt="apple"></a></div>
			</div>
		</section>
		<section id="clients-slider">
		
			<h3 style="color:#ff2600;text-align:center;">Client's we've worked with.</h3>
			<br><br>
			<div class="container">
				<div class="flexslider">
			  <ul class="slides">
			    <li>
			      <figure>
			       <center><img src="<?php echo $config['LIVE_URL'];?>views/images/clients/Sprint.png" alt="Sprint" width="200"></center>
						
			   </figure>
			   </li>
			   <li>
			   	<figure>
			    <center><img src="<?php echo $config['LIVE_URL'];?>views/images/clients/Miramax.png" alt="Miramax" width="200"></center>
				</figure> 
			    </li>
			    <li>
			      <figure>
			      	<center><img src="<?php echo $config['LIVE_URL'];?>views/images/clients/Wow.png" alt="Wow" width="200"></i></center	
			      </figure>
			    </li>
			    <li>
			      <figure>
			      	<center><img src="<?php echo $config['LIVE_URL'];?>views/images/clients/AllianceData.png" alt="AllianceData" width="200"></center>
						
			      </figure>
			     </li>
			   	<li>
			      <figure>
			      	<center><img src="<?php echo $config['LIVE_URL'];?>views/images/clients/PaydayPERX.png" alt="PaydayPERX" width="200"></center>
							
			      </figure>
			     </li>
			   	<li>
			      <figure>
			      	<center><img src="<?php echo $config['LIVE_URL'];?>views/images/clients/CharmingCharlie.png" alt="CharmingCharlie" width="200"></i></center>
							
			      </figure>
			     </li>
			   	<li>
			      <figure>
			      	<center><img src="<?php echo $config['LIVE_URL'];?>views/images/clients/UltimateSoftware.png" alt="UltimateSoftware" width="200"></center>
							
			      </figure>
			     </li>
			   	<li>
			      <figure>
			      	<center><img src="<?php echo $config['LIVE_URL'];?>views/images/clients/foodtrucklogo.png" alt="foodtrucklogo" width="200"></center>
							
			      </figure>
			     </li>
			   	<li>
			      <figure>
			      	<center><img src="<?php echo $config['LIVE_URL'];?>views/images/clients/SBCAdvertising.png" alt="SBCAdvertising" width="200"></center>
								
			      </figure>
			     </li>
			     <li>
			      <figure>
			      	<center><img src="<?php echo $config['LIVE_URL'];?>views/images/clients/rac.png" alt="Rent-A-Center" width="200"></center>
								
			      </figure>
			    </li>					
			  </ul>
			</div>
			<div align="center">
                    <span class="slogan">Let's make something happen.</span>
				</div>
			</div>
		</section>
		<section id="clients">
			<div class="container">
				<h1>Client's we've worked with.</h1>
					<hr>
				<div class="row">
					
					<div class="col-md-3">
						<center><img src="<?php echo $config['LIVE_URL'];?>views/images/clients/Sprint.png" alt="Sprint" width="200"></center>
						
					</div>
					<div class="col-md-3">
						<center><img src="<?php echo $config['LIVE_URL'];?>views/images/clients/Miramax.png" alt="Miramax" width="200"></center>
						
					</div>
					<div class="col-md-3">
						<center><img src="<?php echo $config['LIVE_URL'];?>views/images/clients/Wow.png" alt="Wow" width="200"></i></center>
						
					</div>
				</div>
				<br>
				<div class="row">	
					<div class="col-md-3">
						<center><img src="<?php echo $config['LIVE_URL'];?>views/images/clients/AllianceData.png" alt="AllianceData" width="200"></center>
						
					</div>
					<div class="col-md-3">
						<center><img src="<?php echo $config['LIVE_URL'];?>views/images/clients/PaydayPERX.png" alt="PaydayPERX" width="200"></center>
						
					</div>
					<div class="col-md-3">
						<center><img src="<?php echo $config['LIVE_URL'];?>views/images/clients/CharmingCharlie.png" alt="CharmingCharlie" width="200"></i></center>
						
					</div>
				</div>
				<br>
				<div class="row">	
					<div class="col-md-3">
						<center><img src="<?php echo $config['LIVE_URL'];?>views/images/clients/UltimateSoftware.png" alt="UltimateSoftware" width="200"></center>
						
					</div>
					<div class="col-md-3">
						<center><img src="<?php echo $config['LIVE_URL'];?>views/images/clients/foodtrucklogo.png" alt="foodtrucklogo" width="200"></center>
						
					</div>
					<div class="col-md-3">
						<center><img src="<?php echo $config['LIVE_URL'];?>views/images/clients/SBCAdvertising.png" alt="SBCAdvertising" width="200"></i></center>
						
					</div>
				</div>
				<br>
				<div class="row">	
					
					<div class="col-md-12">
						<center><img src="<?php echo $config['LIVE_URL'];?>views/images/clients/rac.png" alt="Rent-A-Center" width="200"></center>
						
					</div>
					
				</div>
				<hr>
				<br>
				<br>
				<div align="center">
                    <span class="slogan">Let's make something happen.</span>
				</div>
			</div>
		</section>