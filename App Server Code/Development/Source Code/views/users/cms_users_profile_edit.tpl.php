<div id="control-bar" class="grey-bg clearfix" style="opacity: 1;"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="history.go(-1);return false;"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-180.png" width="16" height="16" > Back</button>
		</div>
		
		<?php /*?><div class="float-right"> 
			<button type="button" class="blue" onclick="location.href='<?php echo $config['LIVE_URL']; ?>cmsusers/add/'">Add CMS User</button> 
		</div><?php */?>
			
	</div>
</div>
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
			<form id="frm_cms_users_profile_edit" name="frm_cms_users_profile_edit" method="post" action="" class="block-content form">
				<h1>Edit your Profile</h1>
						<div id="form_content">
							<input type="hidden" name="cms_u_id" id="cms_u_id" value="<?php echo isset($outArray[0]['u_id']) ? $outArray[0]['u_id'] : 0; ?>"  />
							
							<div id="frm_error" style="display:none;"><p class="message error no-margin" style="">&nbsp;</p></div>
							
							<ul>
								<li class="colx2-left">
									<span><strong>User Name:</strong></span><br />
									<input type="text" id="cms_u_uname" name="cms_u_uname" value="<?php echo isset($outArray[0]['u_uname']) ? $outArray[0]['u_uname'] : ''; ?>" disabled="disabled"/><br />
                                     <span style="font-style: italic;"> (You cannot edit Username)</span>
								</li>
                                	<li class="colx2-left">
									<span><strong>Password:</strong></span><br />
									<input type="password" id="cms_u_password" name="cms_u_password" value=""/><br />
                                    <span style="font-style: italic;"> (Keep blank if you want existing password)</span>
								</li>	
                                <li class="colx2-left">
									<span><strong>Email Address:</strong></span><br />
									<input type="text" id="cms_u_email" name="cms_u_email" value="<?php echo isset($outArray[0]['u_email']) ? $outArray[0]['u_email'] : ''; ?>" />
								</li>
                                <li class="colx2-left">
									<span><strong>First Name:</strong></span><br />
									<input type="text" id="cms_u_fname" name="cms_u_fname" value="<?php echo isset($outArray[0]['u_first_name']) ? $outArray[0]['u_first_name'] : ''; ?>"/>
								</li>
								<li class="colx2-left">
									<span><strong>Last Name:</strong></span><br />
									<input type="text" id="cms_u_lname" name="cms_u_lname" value="<?php echo isset($outArray[0]['u_last_name']) ? $outArray[0]['u_last_name'] : ''; ?>"/>
								</li>
								<li class="colx2-left">
									<span><strong>Address 1:</strong></span><br />
									<input type="text" id="cms_u_address_1" name="cms_u_address_1" value="<?php echo isset($outArray[0]['u_address_1']) ? $outArray[0]['u_address_1'] : ''; ?>"/>
								</li>
								<li class="colx2-left">
									<span><strong>Address 2:</strong></span><br />
									<input type="text" id="cms_u_address_2" name="cms_u_address_2" value="<?php echo isset($outArray[0]['u_address_2']) ? $outArray[0]['u_address_2'] : ''; ?>"/>
								</li>
								<li class="colx2-left">
									<span><strong>City:</strong></span><br />
									<input type="text" id="cms_u_city" name="cms_u_city" value="<?php echo isset($outArray[0]['u_city']) ? $outArray[0]['u_city'] : ''; ?>"/>
								</li>
								<li class="colx2-left">
									<span><strong>State:</strong></span><br />
									<input type="text" id="cms_u_state" name="cms_u_state" value="<?php echo isset($outArray[0]['u_state']) ? $outArray[0]['u_state'] : ''; ?>"/>
								</li>
								<li class="colx2-left">
									<span><strong>Zip:</strong></span><br />
									<input type="text" id="cms_u_zip" name="cms_u_zip" value="<?php echo isset($outArray[0]['u_zip']) ? $outArray[0]['u_zip'] : ''; ?>"/>
								</li>
								<li class="colx2-left">
									<span><strong>Country:</strong></span><br />
									<?php /*
									<select name="u_country" id="u_country" class="full-width">
										<option value="US">United States</option>
										<option value="UK">United Kingdom</option>
										<option value="IN">India</option>
									</select>
									*/ ?>
									<input type="text" id="cms_u_country" name="cms_u_country" value="<?php echo isset($outArray[0]['u_country']) ? $outArray[0]['u_country'] : ''; ?>"/>
								</li>
								
								<li class="colx2-left">
									<span><strong>Contact Phone:</strong></span><br />
									<input type="text" id="cms_u_phone" name="cms_u_phone" value="<?php echo isset($outArray[0]['phone']) ? $outArray[0]['phone'] : ''; ?>" onkeyup="validateUSNumber(this.value,event);" onkeypress="return checkIt(event)" maxlength="12"/>
								</li>
                                <li class="colx2-left">
									<span><strong>Group:</strong></span><br />
									<select name="cms_u_group" id="cms_u_group">
                                       <option value="">Please select</option>
                                       <?php for($i=0;$i<count($outArrCmsUserGropus);$i++){ ?>
                                       <?php if($outArrCmsUserGropus[$i]['group_id']==$outArray[0]['u_group_id']){?>
                                         <option value="<?php echo $outArrCmsUserGropus[$i]['group_id'];?>" selected="selected"><?php echo $outArrCmsUserGropus[$i]['group_name'];?></option>
                                         <?php }else{?>
                                          <option value="<?php echo $outArrCmsUserGropus[$i]['group_id'];?>"><?php echo $outArrCmsUserGropus[$i]['group_name'];?></option>
                                         <?php }?>
                                         
                                       <?php }?>
                                       
                                    </select>
								</li>
                                <li class="colx2-left">
									<span><strong>Active:</strong></span>
                                     <?php if(isset($outArray[0]['is_active']) && $outArray[0]['is_active']=='1'){?>
									<input type="radio" id="active" name="active" value="1" checked="checked" style="width:6%;">Yes
                                    <input type="radio" id="active" name="active" value="0" style="width:6%;" >No
									<?php }else{?>
                                    <input type="radio" id="active" name="active" value="0" style="width:6%;" checked="checked">No
                                    <input type="radio" id="active" name="active" value="1" style="width:6%;">Yes
                                    <?php }?>								                              
                                </li>
								
							</ul>
							<div class="clear"></div>
							<fieldset class="grey-bg no-margin">
							<p style="float:right;">
								<button type="button" onclick="updateCmsUserProfile();">Update Profile</button>
								<a href="<?php echo $config['LIVE_URL'].'cmsusers/' ?>"><button type="button" style="margin-left:2px;">Cancel</button></a>
							</p>
						</fieldset>
						</div>	
			</form>
			</div>
		</section>