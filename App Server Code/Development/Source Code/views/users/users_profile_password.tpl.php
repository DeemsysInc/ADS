<style type="text/css">
#form_content ul li, #form_content ul li input{
	margin-bottom:10px;
}
#form_content ul li input{
	width:25%;
	margin-top:6px;
}
</style>
<section class="grid_12">
			<div class="block-border">
			<form id="frm_users_password" name="frm_users_password" method="post" action="" class="block-content form">
				<h1>Edit your Profile</h1>
						<div id="form_content">
							<input type="hidden" id="u_id" name="u_id" value="<?php echo $uid; ?>"/>
							<div id="frm_error" style="display:none;"><p class="message error no-margin" style="">&nbsp;</p></div>
							
							<ul>
								<li>
									<span>User Name:</span><br />
									<input type="text" id="u_uname" name="u_uname" value="<?php echo isset($outArray[0]['u_uname']) ? $outArray[0]['u_uname'] : ''; ?>" disabled /><span style="font-style: italic;"> (You cannot edit Username)</span>
								</li>
								<li>
									<span>Password:</span><br />
									<input type="password" id="u_password" name="u_password" value=""/><span style="font-style: italic;"> (Keep blank if you want existing password)</span>
								</li>
								<li>
									<span>Confirm Password:</span><br />
									<input type="password" id="u_password_confirm" name="u_password_confirm" value=""/>
								</li>
								
							</ul>
							<div class="clear"></div>
							<fieldset class="grey-bg no-margin">
							<p style="float:right;">
								<button type="button" onclick="saveUserPassword();">Change</button>
								<a href="<?php echo $config['LIVE_URL'].'users/profile' ?>"><button type="button" style="margin-left:40px;">Cancel</button></a>
							</p>
						</fieldset>
						</div>	
			</form>
			</div>
		</section>