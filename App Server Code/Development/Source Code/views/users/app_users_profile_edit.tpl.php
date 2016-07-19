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
			<form id="frm_app_users_profile" name="frm_app_users_profile" method="post" action="" class="block-content form">
				<h1>Edit Profile</h1>
						<div id="form_content">
							<input type="hidden" id="au_id" name="au_id" value="<?php echo $auid; ?>"/>
							<p><span style="color:red;">*</span> fields are mandatory</p>
							<div id="frm_error" style="display:none;"><p class="message error no-margin" style="">&nbsp;</p></div>
							
							<ul>
								<li class="colx2-left">
									<span><span style="color:red;">*</span>First Name:</span><br />
									<input type="text" id="au_first_name" name="au_first_name" value="<?php echo isset($outArray[0]['first_name']) ? $outArray[0]['first_name'] : ''; ?>"/>
								</li>
								<li class="colx2-left">
									<span><span style="color:red;">*</span>Last Name:</span><br />
									<input type="text" id="au_last_name" name="au_last_name" value="<?php echo isset($outArray[0]['last_name']) ? $outArray[0]['last_name'] : ''; ?>"/>
								</li>
								<li class="colx2-left">
									<span><span style="color:red;">*</span>User Name:</span><span style="font-style: italic;margin: 159px;"> (You cannot edit Username)</span><br />
									<input type="text" id="au_username" name="au_username" value="<?php echo isset($outArray[0]['username']) ? $outArray[0]['username'] : ''; ?>" disabled="disabled"/>
								</li>
								<li class="colx2-left">
									<span>Password</span><br />
									<input type="text" id="au_password" name="au_password" value="<?php echo isset($outArray[0]['password']) ? $outArray[0]['password'] : ''; ?>"/>
								</li>
								<li class="colx2-left">
									<span><span style="color:red;">*</span>Group Name:</span><br />
									<select id="au_grp_id" name="au_grp_id">
                                     <?php for($i=0;$i<count($outArrayGroups);$i++){
										 if($outArrayGroups[$i]['id']==$outArray[0]['groupid']){?>
                                     <option selected="selected" value="<?php echo $outArrayGroups[$i]['id'];?>"><?php echo $outArrayGroups[$i]['name'];?></option>                                     
                                      <?php }else {?>
                                      <option value="<?php echo $outArrayGroups[$i]['id'];?>"><?php echo $outArrayGroups[$i]['name'];?></option>                                     <?php }}?>
									 
									 
                                    </select>
								</li>
								
							</ul>
							<div class="clear"></div>
							<fieldset class="grey-bg no-margin">
							<p style="float:right;">
								<button type="button" onclick="saveAppUserProfile();">Save Profile</button>
								<a href="<?php echo $config['LIVE_URL'].'users/profile' ?>"><button type="button" style="margin-left:40px;">Cancel</button></a>
							</p>
						</fieldset>
						</div>	
			</form>
			</div>
		</section>