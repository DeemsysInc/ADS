<div id="add_users">
	<div id="main">
	<div class="full_w">
			<h2 style="margin-left:15px;">Modify Users</h2>
			<div class="entry">
				<div class="sep"></div>
			</div>
			
			<form action="" method="post">
				<div class="element">
					<label for="fname">First Name <span class="red">(required)</span></label>
					<input id="fname" name="fname" class="text" value="<?php echo $outArray[0]['u_first_name']; ?>" /> <span class="n_error" id="err_fname" style="display:none;">&nbsp;</span>
				</div>
				<div class="element">
					<label for="lname">Last Name <span class="red">(required)</span></label>
					<input id="lname" name="lname" class="text" value="<?php echo $outArray[0]['u_last_name']; ?>" /> <span class="n_error" id="err_lname" style="display:none;">&nbsp;</span>
				</div>
				<div class="element">
					<label for="uname">User name <span>You cannot modify username</span></label>
					<input id="uname" readonly="true" name="uname" class="text" value="<?php echo $outArray[0]['u_uname']; ?>" /> <span class="n_error" id="err_uname" style="display:none;">&nbsp;</span>
				</div>
				<div class="element">
					<label for="passw">Password <span>(keep blank to use existing password)</span></label>
					<input type="password" id="passw" name="passw" class="text" value="" /> <span class="n_error" id="err_passw" style="display:none;">&nbsp;</span>
				</div>
				<div class="element">
					<label for="passw_confirm">Verify Password <span>Re-enter password to verify</span></label>
					<input type="password" id="passw_confirm" name="passw_confirm" class="text" value="" /> <span class="n_error" id="err_passw_confirm" style="display:none;">&nbsp;</span>
				</div>
				<div class="element">
					<label for="email">Email Address <span class="red">(required)</span></label>
					<input id="email" name="email" class="text" value="<?php echo $outArray[0]['email_address']; ?>" /> <span class="n_error" id="err_email" style="display:none;">&nbsp;</span>
				</div>
				<div class="element">
					<label for="phone">Phone</label>
					<input id="phone" name="phone" class="text" onkeyup="validateUSPhone(this,event);" value="<?php echo $outArray[0]['phone']; ?>" /> <span class="n_error" id="err_phone" style="display:none;">&nbsp;</span>
				</div>
				<div class="entry">
					<a href="javascript:void(0);" class="add button" onclick="return editSaveUser('<?php echo $outArray[0]['u_id']; ?>');" />Update</a><a href="<?php echo $config['LIVE_URL']?>users/" class="cancel button" />Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>
