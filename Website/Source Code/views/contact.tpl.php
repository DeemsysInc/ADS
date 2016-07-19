<section id="contact_banner">
			<div class="container">
				<div class="row">
					   <div class="contact_banner_caption">
			          	<div class="slide-text-info">
			          		
			          		<span class="span5">We'd love to <br> hear from you.</span>
							
			          	</div>
			          </div>
				</div>
				
			</div>
		</section>
		<section id="contact">
			<div class="container">
				<div class="row" style="width:81%;margin: auto;">
					
					<h1>Contact Us</h1>
					<br><br>
					<div class="col-md-12">
						<div id="pre">
					     <fieldset>

					        <div id="error" style="color:red;"></div>
		                    <input type="text" id="firstname" name="firstname" placeholder="First name *" required="">
		                    <input type="text" id="lastname" name="lastname" placeholder="Last name *" required="">
		                    <input type="text" id="company" name="company" placeholder="Company *" required="">
		                    <input type="text" id="position" name="position" placeholder="Position">
		                    <input type="email" id="email" name="email" placeholder="Email address *" required="">
		                    <input type="tel" id="phone" name="phone" placeholder="Phone number *" required="">
		                    <p>Reason for contacting us:</p>
		                    <div>
								<span><input type="radio" name="reason" value="inquiry">General Inquiry</span>
								<span><input type="radio" name="reason" value="demo">Try Our Demo</span>
							</div>
		                    <textarea id="addinfo" name="addinfo" placeholder="Additional information"></textarea>

		                    <button class="btn" onclick="return saveEnquiry();">Send</button>
						</fieldset>
						</div>
						 <div id="post" style="padding-left: 0px; display: none; background-image: none !important;">
						    <p>Thanks for your note! Someone at SeeMore Interactive will contact you soon.</p>
						    <a href="<?php echo $config['LIVE_URL'];?>contact" class="btn">Return to form</a>
						</div>

					</div>
			        
			       
				</div>
				
			</div>
		</section>