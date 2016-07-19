<div id="control-bar" class="grey-bg clearfix" style="opacity: 1;"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="history.go(-1);return false;"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-180.png" width="16" height="16" > Back</button>
		</div>
		  <div style="float:left; padding-top: 6px;text-align: center;width: 80%;"><h1 id="client_name"><?php echo isset($getClientDetails[0]['name']) ? $getClientDetails[0]['name'] : ''; ?></h1><br /><div id='loadingmessage' style='display:none'> <img src='<?php echo $config['LIVE_URL']."views/images/loading-cafe.gif";?>'/> <br />Updating data....</div></div>
    
	<?php /*?>	<div class="float-right"> 
			<button type="button" onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/add'">Add Client</button> 
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
</style>
<section class="grid_12">
			<div class="block-border">
			<form id="frm_client_store_add" name="frm_client_store_add" method="post" class="block-content form" enctype="multipart/form-data">
          
<input type="hidden" id="cid" name="cid" value="<?php echo $cid;?>"/>
				<h1>Add Store Details</h1>
    					<div id="form_content">
                        <div id="frm_error" style="display:none;"></div>
                        <p><span style="color:red;">*</span> Fields are mandatory</p>
							
							<ul>
                                <li class="colx2-left_m">
									<span>Store Name:</span><br />
									<input type="text" id="s_name" name="s_name" value=""/>
								</li>
                               
                                <li class="colx2-left_m">
									<span>Store Code:</span><br />
									<input type="text" id="s_code" name="s_code" value=""/>
									
								</li>
								<li class="colx2-left_m">
									<span>Store search type:</span><br />
									<input type="text" id="s_search_type" name="s_search_type" value=""/>
									
								</li>
                              	
								<li class="colx2-left_m">
									<span>Latitude:</span><br />
									<input type="text" id="s_latitude" name="s_latitude" value=""/>
								</li>
								
                                <li class="colx2-left_m">
									<span>Longitude:</span><br />
									<input type="text" id="s_longitude" name="s_longitude" value=""/>
								</li>
                                
                                 <li class="colx2-left_m">
									<span>Address1:</span><br />
									<textarea id="s_address1" name="s_address1"  style="width: 413px; height: 100px;"></textarea>
								</li>
                                 
                                <li class="colx2-left_m">
									<span>Address2:</span><br />
									<textarea id="s_address2" name="s_address2"  style="width: 413px; height: 100px;"></textarea>
								</li>
								 <li class="colx2-left_m">
									<span>Phone:</span><br />
									<input type="text" id="s_phone" name="s_phone" value=""/>
								</li>
								 <li class="colx2-left_m">
									<span>City:</span><br />
									<input type="text" id="s_city" name="s_city" value=""/>
								</li>
								 <li class="colx2-left_m">
									<span>State:</span><br />
									<input type="text" id="s_state" name="s_state" value=""/>
								</li>
								 <li class="colx2-left_m">
									<span>ZIP:</span><br />
									<input type="text" id="s_zip" name="s_zip" value=""/>
								</li>
								 <li class="colx2-left_m">
									<span>Email:</span><br />
									<input type="text" id="s_email" name="s_email" value=""/>
								</li>
								 <li class="colx2-left_m">
									<span>Store trigger threshold:</span><br />
									<input type="text" id="s_trigger_threshold" name="s_trigger_threshold" value=""/>
								</li>
								<li class="colx2-left_m">
									<span>Store update threshold:</span><br />
									<input type="text" id="s_update_threshold" name="s_update_threshold" value=""/>
								</li>
                                	
							</ul>
							<div class="clear"></div>
							<fieldset class="grey-bg no-margin">
							<p style="float:right;">
								<button>Save</button>
								<a href="<?php echo $config['LIVE_URL'];?>clients/id/<?php echo $cid;?>/stores"><button type="button" style="margin-left:40px;">Cancel</button></a>
							</p>
						</fieldset>
						</div>	
			</form>
			</div>
		</section>