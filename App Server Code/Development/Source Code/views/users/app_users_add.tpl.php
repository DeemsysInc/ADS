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
.colx2-left_m {
width: 48%;
margin-bottom: 0;
}
.colx2-right {
width: 59%;
margin-bottom: 0;
} 
</style>
<section class="grid_12">
			<div class="block-border">
			<form id="frm_app_users_add" name="frm_app_users_add" method="post" action="" class="block-content form">
				<h1>Add App User</h1>
						<div id="form_content">
							
							<div id="frm_error" style="display:none;"><p class="message error no-margin" style="">&nbsp;</p></div>
							<p><span style="color:red;">*</span> Fields are mandatory</p>
							<ul>
                                <li class="colx2-left_m">
                                <span style="color:red;">*</span>
									<span>User Name:</span><br />
									<input type="text" id="app_u_uname" name="app_u_uname" value=""/>
								</li>
                                 <li class="colx2-left_m">
                                 <span style="color:red;">*</span>
									<span>Email Address:</span><br />
									<input type="text" id="app_u_email" name="app_u_email" value="" />
								</li>
                                <li class="colx2-left_m">
                                <span style="color:red;">*</span>
									<span>First Name:</span><br />
									
                                    <input type="text" id="app_u_fname" name="app_u_fname" value="" />
								</li>
                                <li class="colx2-left_m">
                                <span style="color:red;">*</span>
									<span>Last Name:</span><br />
									<input type="text" id="app_u_lname" name="app_u_lname" value="" />
								</li>
                               
								<li class="colx2-left_m">
                                <span style="color:red;">*</span>
									<span>Password:</span><br />
									<input type="password" id="app_u_password" name="app_u_password" value=""/>
								</li>
								<li class="colx2-left_m">
                                <span style="color:red;">*</span>
									<span>Confirm Password:</span><br />
									<input type="password" id="app_u_password_confirm" name="app_u_password_confirm" value=""/>
								</li>
                               
                                
                               <li class="colx2-left_m">
                               <span style="color:red;">*</span>
									<span>Group:</span><br /><br />
									<select name="app_u_group" id="app_u_group">
                                       <option value="">Please select</option>
                                       <?php for($i=0;$i<count($outArrAppUserGropus);$i++){ ?>
                                         <option value="<?php echo $outArrAppUserGropus[$i]['id'];?>"><?php echo $outArrAppUserGropus[$i]['name'];?></option>
                                       <?php }?>
                                       
                                    </select>
                                    
								</li>
								
								
							</ul>
							<div class="clear"></div>
							<fieldset class="grey-bg no-margin">
							<p style="float:right;">
								<button type="button" onclick="AddAppUser();">Create</button>
								<a href="<?php echo $config['LIVE_URL'].'appusers' ?>"><button type="button" style="margin-left:2px;">Cancel</button></a>
							</p>
						</fieldset>
						</div>	
			</form>
			</div>
		</section>