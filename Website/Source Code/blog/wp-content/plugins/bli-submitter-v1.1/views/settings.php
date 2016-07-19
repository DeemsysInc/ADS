<div class="wrap" id="settings-page">
	<div id="icon-options-general" class="icon32"><br></div><h2>Backlink Settings</h2>
	<br /><br />

	<div>
		<?php
		if(!empty($data['infoMessage'])) {?>
			<div class="alert alert-info"><?php echo $data['infoMessage'];?></div>
		<?php }
		if(!empty($data['successMessage'])) {?>
			<div class="alert alert-success"><?php echo $data['successMessage'];?></div>
		<?php }
		if(!empty($data['warningMessage'])) {?>
			<div class="alert alert-warning"><?php echo $data['warningMessage'];?></div>
		<?php }
		if(!empty($data['dangerMessage'])) {?>
			<div class="alert alert-danger"><?php echo $data['dangerMessage'];?></div>
		<?php }
		?>
		<?php if(!$data['settingsObj']->isApiKeyValid) { ?>
			<div class="alert alert-danger"><?php echo $data['settingsObj']->errorMessage;?></div>
		<?php } ?>
	</div>

	<form class="form-inline" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
		<div class="large-area">
			<label>API key</label> 
			<input type="text" name="settings[api_key]" value="<?php if(isset($data['settingsObj']->apiKey)) echo $data['settingsObj']->apiKey;?>" />
			<span class="error">Need a key?</span> &nbsp;<a href="http://backlinksindexer.com/members-dashboard/api/ ">Click here</a>
		</div>

		<div class="form-large">
			<div class="heading">
				<th colspan="2">Settings</th>
			</div>
			<div class="form-body">
				<div>
					<label>Send all new posts to <big>backlinksindexer.com</big> automatically: </label>
					<input type="checkbox" name="settings[auto]" <?php if(isset($data['settingsObj']->sendNew) && $data['settingsObj']->sendNew) echo 'checked=checked';?> />
				</div>

				<div>
					<label>Send random post to backlinksindexer.com: </label>
					<input type="checkbox" name="settings[random]" <?php if(isset($data['settingsObj']->sendRandom) && $data['settingsObj']->sendRandom) echo 'checked=checked';?> />
				</div>
				<div>
					<label>Send Random post newer than: </label>
					<input id="backlinkDate" type="text" name="settings[random_newer_than]" value="<?php echo date('d-m-Y',$data['settingsObj']->randomNewerThan); ?>" />
				</div>
				<div>
					<label>Count of Random posts to fetch: </label>
					<input type="text" name="settings[random_count]" value="<?php echo $data['settingsObj']->randomCount; ?>" />
				</div>
				<div>
					<label>Never submit the same post twice (Duplicates): </label>
					<input type="checkbox" name="settings[duplicate]" <?php if(isset($data['settingsObj']->duplicate) && $data['settingsObj']->duplicate == 0) echo 'checked=checked';?> />
				</div>
				<div><label class="or">OR</label></div>
				<div> 
					<label class="large">Prevent the same post from being submitted to BLI unless
					<select name="settings[duplicate_older_than]">
							<?php for ($i=0; $i < 13; $i++) { ?>
								<option value="<?php echo $i;?>" <?php if(isset($data['settingsObj']->duplicateOlderThan) && $data['settingsObj']->duplicateOlderThan == $i) echo 'selected=selected';?>><?php echo $i;?></option>
							<?php } ?>
					</select>
					months have passed</label>
				</div>
				<div class="clearall"><input type="submit" class="button button-primary button-large right-btn" value="Update" /></div>
			</div>
		</div>
	</form>
</div>
<script type="text/javascript">

jQuery(document).ready(function() {
    jQuery('#backlinkDate').datepicker({
        dateFormat : 'dd-mm-yy',
        defaultDate: new Date(jQuery.datepicker.parseDate('@',<?php echo $data['settingsObj']->randomNewerThan * 1000; ?>))
    });
});

</script>