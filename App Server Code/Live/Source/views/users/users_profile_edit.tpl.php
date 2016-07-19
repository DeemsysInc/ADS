<style type="text/css">
#form_content ul li, #form_content ul li input{
	margin-bottom:10px;
}
#form_content ul li input{
	width:70%;
	margin-top:6px;
}
#form_content ul li select{
	width:73%;
	margin-top:6px;
}
</style>
<section class="grid_12">
			<div class="block-border">
			<form id="frm_users_profile" name="frm_users_profile" method="post" action="" class="block-content form">
				<h1>Edit your Profile</h1>
						<div id="form_content">
							<input type="hidden" id="u_id" name="u_id" value="<?php echo $uid; ?>"/>
							<p><span style="color:red;">*</span> fields are mandatory</p>
							<div id="frm_error" style="display:none;"><p class="message error no-margin" style="">&nbsp;</p></div>
							
							<ul>
								<li class="colx2-left">
									<span><span style="color:red;">*</span>First Name:</span><br />
									<input type="text" id="u_first_name" name="u_first_name" value="<?php echo isset($outArray[0]['u_first_name']) ? $outArray[0]['u_first_name'] : ''; ?>"/>
								</li>
								<li class="colx2-left">
									<span><span style="color:red;">*</span>Last Name:</span><br />
									<input type="text" id="u_last_name" name="u_last_name" value="<?php echo isset($outArray[0]['u_last_name']) ? $outArray[0]['u_last_name'] : ''; ?>"/>
								</li>
								<li class="colx2-left">
									<span><span style="color:red;">*</span>Address 1:</span><br />
									<input type="text" id="u_address_1" name="u_address_1" value="<?php echo isset($outArray[0]['u_address_1']) ? $outArray[0]['u_address_1'] : ''; ?>"/>
								</li>
								<li class="colx2-left">
									<span>Address 2:</span><br />
									<input type="text" id="u_address_2" name="u_address_2" value="<?php echo isset($outArray[0]['u_address_2']) ? $outArray[0]['u_address_2'] : ''; ?>"/>
								</li>
								<li class="colx2-left">
									<span><span style="color:red;">*</span>City:</span><br />
									<input type="text" id="u_city" name="u_city" value="<?php echo isset($outArray[0]['u_city']) ? $outArray[0]['u_city'] : ''; ?>"/>
								</li>
								<li class="colx2-left">
									<span><span style="color:red;">*</span>State:</span><br />
									<input type="text" id="u_state" name="u_state" value="<?php echo isset($outArray[0]['u_state']) ? $outArray[0]['u_state'] : ''; ?>"/>
								</li>
								<li class="colx2-left">
									<span><span style="color:red;">*</span>Zip:</span><br />
									<input type="text" id="u_zip" name="u_zip" value="<?php echo isset($outArray[0]['u_zip']) ? $outArray[0]['u_zip'] : ''; ?>"/>
								</li>
								<li class="colx2-left">
									<span><span style="color:red;">*</span>Country:</span><br />
									<?php /*
									<select name="u_country" id="u_country" class="full-width">
										<option value="US">United States</option>
										<option value="UK">United Kingdom</option>
										<option value="IN">India</option>
									</select>
									*/ ?>
									<input type="text" id="u_country" name="u_country" value="<?php echo isset($outArray[0]['u_country']) ? $outArray[0]['u_country'] : ''; ?>"/>
								</li>
								<li class="colx2-left">
									<span><span style="color:red;">*</span>Email Address:</span><br />
									<input type="text" id="u_email" name="u_email" value="<?php echo isset($outArray[0]['u_email']) ? $outArray[0]['u_email'] : ''; ?>" onblur="checkEmail()"/>
								</li>
								<li class="colx2-left">
									<span>Contact Phone:</span><br />
									<input type="text" id="phone" name="phone" value="<?php echo isset($outArray[0]['phone']) ? $outArray[0]['phone'] : ''; ?>"/>
								</li>
								
							</ul>
							<div class="clear"></div>
							<fieldset class="grey-bg no-margin">
							<p style="float:right;">
								<button type="button" onclick="saveUserProfile();">Save Profile</button>
								<a href="<?php echo $config['LIVE_URL'].'users/profile' ?>"><button type="button" style="margin-left:40px;">Cancel</button></a>
							</p>
						</fieldset>
						</div>	
			</form>
			</div>
		</section>