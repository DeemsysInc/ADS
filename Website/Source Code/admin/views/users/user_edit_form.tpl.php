
			
			<noscript>
				<div class="alert alert-block span10">
					<h4 class="alert-heading">Warning!</h4>
					<p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a> enabled to use this site.</p>
				</div>
			</noscript>
			
			<div id="content" class="span10">
			<!-- content starts -->
			

			<div>
				<ul class="breadcrumb">
					<li>
						<a href="<?php echo $config['LIVE_ADMIN_URL']?>">Home</a> <span class="divider">/</span>
					</li>
					<li>
						<a href="<?php echo $config['LIVE_ADMIN_URL']?>users">Users</a>
					</li>
					<li>
						<a href="#">Edit</a>
					</li>
				</ul>
			</div>
			
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-edit"></i> Edit User Profile</h2>
						<div class="box-icon">
							<a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a>
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<form class="form-horizontal">
						  <fieldset>
							<div id="success_msg" style="display: none;">
							<div class="alert alert-success" >
							<button type="button" class="close" data-dismiss="alert">X</button>
							<strong>Well done!</strong> You successfully updated.
						       </div>
							</div>
							<div id="error_msg" style="display: none;">
							<div class="alert alert-error">
							<button type="button" class="close" data-dismiss="alert">X</button>
							<strong>Oh snap!</strong> Change a few things up and try submitting again.
						         </div>
							</div>
							<div class="control-group">
								<label class="control-label" for="focusedInput">User Name</label>
								<div class="controls">
								  <input disabled="disabled" class="input-xlarge focused" id="user_name" name="user_name" type="text" value="<?php echo isset($outArray[0]['user_name']) ? $outArray[0]['user_name'] : ''?>">
								<span>You cannot modify username</span>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="focusedInput">Password</label>
								<div class="controls">
								  <input  class="input-xlarge focused" id="password" name="password" type="password" value="<?php echo isset($outArray[0]['url']) ? $outArray[0]['url'] : ''?>">
								<span>(If you would like to change the password type a new one. Otherwise leave this blank.)</span>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="focusedInput">Verify Password</label>
								<div class="controls">
								  <input  class="input-xlarge focused" id="confirm_password" name="confirm_password" type="password" value="<?php echo isset($outArray[0]['url']) ? $outArray[0]['url'] : ''?>">
								<span id="retypemsg">Re-enter password to verify</span>
								<span id="error_pass_confirm" style="color: red;"></span>
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="focusedInput">E-mail</label>
								<div class="controls">
								  <input class="input-xlarge focused" id="user_email" name="user_email" type="text" value="<?php echo isset($outArray[0]['email_address']) ? $outArray[0]['email_address'] : ''?>">
								<span id="error_email" style="color: red;"></span>
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="focusedInput">Phone</label>
								<div class="controls">
								  <input onkeyup="validateUSPhone(this,event);" class="input-xlarge focused" id="user_phone" name="user_phone" type="text" value="<?php echo isset($outArray[0]['phone']) ? $outArray[0]['phone'] : ''?>">
								
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="selectError">User Type</label>
								<div class="controls">
								  <select id="user_type" data-rel="chosen">
									<option value="0">Plese Select</option>
									<?php for($i=0;$i<count($outArrGroups);$i++){?>
									<?php if(isset($outArray[0]['group_id']) && $outArray[0]['group_id']==$outArrGroups[$i]['group_id']){?>
									<option selected="selected" value="<?php echo $outArrGroups[$i]['group_id']; ?>"><?php echo $outArrGroups[$i]['group_name']; ?></option>
									
									<?php }else{?>
									
									<option value="<?php echo $outArrGroups[$i]['group_id']; ?>"><?php echo $outArrGroups[$i]['group_name']; ?></option>
									<?php }?>
									<?php }?>
								  
								  </select>
								</div>
							  </div>
							<input type="hidden" name="user_id" id="user_id" value="<?php echo isset($outArray[0]['user_id']) ? $outArray[0]['user_id'] : ''?>">
							<div class="form-actions">
							  <button type="submit" class="btn btn-primary" onclick="return updateUserDetails();">Save changes</button>
						          <button type="reset" class="btn">Cancel</button>
  							</div>
						  </fieldset>
						</form>   

					</div>
				</div><!--/span-->

			</div><!--/row-->


    
					<!-- content ends -->
			</div><!--/#content.span10-->
				</div><!--/fluid-row-->
				
		
