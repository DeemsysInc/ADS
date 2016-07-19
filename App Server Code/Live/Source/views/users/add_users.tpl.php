<div id="add_users">
	<div id="main">
	<div class="full_w">
			<h2 style="margin-left:15px;">Add Users</h2>
			<div class="entry">
				<div class="sep"></div>
			</div>
			
			<form action="" method="post">
				<div class="element">
					<label for="fname">First Name <span class="red">(required)</span></label>
					<input id="fname" name="fname" class="text" value="" /> <span class="n_error" id="err_fname" style="display:none;">&nbsp;</span>
				</div>
				<div class="element">
					<label for="lname">Last Name <span class="red">(required)</span></label>
					<input id="lname" name="lname" class="text" value="" /> <span class="n_error" id="err_lname" style="display:none;">&nbsp;</span>
				</div>
				<div class="element">
					<label for="uname">User name <span class="red">(required)</span></label>
					<input id="uname" name="uname" class="text" value="" /> <span class="n_error" id="err_uname" style="display:none;">&nbsp;</span>
				</div>
				<div class="element">
					<label for="passw">Password <span class="red">(required)</span></label>
					<input type="password" id="passw" name="passw" class="text" value="" /> <span class="n_error" id="err_passw" style="display:none;">&nbsp;</span>
				</div>
				<div class="element">
					<label for="passw_confirm">Verify Password <span class="red">(required)</span></label>
					<input type="password" id="passw_confirm" name="passw_confirm" class="text" value="" /> <span class="n_error" id="err_passw_confirm" style="display:none;">&nbsp;</span>
				</div>
				<div class="element">
					<label for="email">Email Address <span class="red">(required)</span></label>
					<input id="email" name="email" class="text" value="" /> <span class="n_error" id="err_email" style="display:none;">&nbsp;</span>
				</div>
				<div class="element">
					<label for="phone">Phone</label>
					<input id="phone" name="phone" class="text" onkeyup="validateUSPhone(this,event);" value="" /> <span class="n_error" id="err_phone" style="display:none;">&nbsp;</span>
				</div>
				<div class="entry">
						<a href="javascript:void(0);" class="add button" onclick="return addUsers();" />Create</a><a href="<?php echo $config['LIVE_URL']?>users/" class="cancel button" />Cancel</a>
					</div>
			</form>
		</div>
	</div>
</div>
