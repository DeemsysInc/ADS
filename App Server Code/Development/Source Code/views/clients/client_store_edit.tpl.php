
<div id="control-bar" class="grey-bg clearfix" style="opacity: 1;"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="history.go(-1);return false;"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-180.png" width="16" height="16" > Back</button>
		</div>
         <div align="center" style="display:none" id="loadingmessage">
  <img src="<?php echo $config['LIVE_URL']; ?>views/images/loading-cafe.gif"><br /><p align="center">Updating data...</p>
</div>
		<?php /*?><div style="float:left; padding-top: 6px;text-align: center;width: 80%;"><h1 id="client_name"><?php echo $outArrClientTriggerView[0]['name']; ?></h1></div>
		<div class="float-right"> 
			<button type="button" class="red">Delete Client</button> 
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
			<form id="frm_client_store_edit" name="frm_client_store_edit" method="post" action="" class="block-content form" enctype="multipart/form-data">
				<h1>Edit Store</h1>
            
						<div id="form_content">
							<div id="frm_error" style="display:none;"></div>
							<input type="hidden" name="s_id" id="s_id" value="<?php echo $storeId;?>" />
                            <input type="hidden" name="c_id" id="c_id" value="<?php echo $cid;?>" />
                           
							<ul>
                            
								<li class="colx2-left_m">
									<span>Store Name:</span><br />
									<input type="text" id="s_name" name="s_name" value="<?php echo isset($outArrClientStoreEditDetails[0]['store_name']) ? $outArrClientStoreEditDetails[0]['store_name'] : ''; ?>"/>
								</li>
                               
                                <li class="colx2-left_m">
									<span>Store Code:</span><br />
									<input type="text" id="s_code" name="s_code" value="<?php echo isset($outArrClientStoreEditDetails[0]['store_code']) ? $outArrClientStoreEditDetails[0]['store_code'] : ''; ?>"/>
									
								</li>
								<li class="colx2-left_m">
									<span>Store search type:</span><br />
									<input type="text" id="s_search_type" name="s_search_type" value="<?php echo isset($outArrClientStoreEditDetails[0]['store_search_type']) ? $outArrClientStoreEditDetails[0]['store_search_type'] : ''; ?>"/>
									
								</li>
                              	
								<li class="colx2-left_m">
									<span>Latitude:</span><br />
									<input type="text" id="s_latitude" name="s_latitude" value="<?php echo isset($outArrClientStoreEditDetails[0]['latitude']) ? $outArrClientStoreEditDetails[0]['latitude'] : ''; ?>"/>
								</li>
								
                                <li class="colx2-left_m">
									<span>Longitude:</span><br />
									<input type="text" id="s_longitude" name="s_longitude" value="<?php echo isset($outArrClientStoreEditDetails[0]['longitude']) ? $outArrClientStoreEditDetails[0]['longitude'] : ''; ?>"/>
								</li>
                                
                                 <li class="colx2-left_m">
									<span>Address1:</span><br />
									<textarea id="s_address1" name="s_address1"  style="width: 413px; height: 100px;"><?php echo isset($outArrClientStoreEditDetails[0]['address_1']) ? $outArrClientStoreEditDetails[0]['address_1'] : ''; ?></textarea>
								</li>
                                 
                                <li class="colx2-left_m">
									<span>Address2:</span><br />
									<textarea id="s_address2" name="s_address2"  style="width: 413px; height: 100px;"><?php echo isset($outArrClientStoreEditDetails[0]['address_2']) ? $outArrClientStoreEditDetails[0]['address_2'] : ''; ?></textarea>
								</li>
								 <li class="colx2-left_m">
									<span>Phone:</span><br />
									<input type="text" id="s_phone" name="s_phone" value="<?php echo isset($outArrClientStoreEditDetails[0]['phone']) ? $outArrClientStoreEditDetails[0]['phone'] : ''; ?>"/>
								</li>
								 <li class="colx2-left_m">
									<span>City:</span><br />
									<input type="text" id="s_city" name="s_city" value="<?php echo isset($outArrClientStoreEditDetails[0]['city']) ? $outArrClientStoreEditDetails[0]['city'] : ''; ?>"/>
								</li>
								 <li class="colx2-left_m">
									<span>State:</span><br />
									<input type="text" id="s_state" name="s_state" value="<?php echo isset($outArrClientStoreEditDetails[0]['state']) ? $outArrClientStoreEditDetails[0]['state'] : ''; ?>"/>
								</li>
								 <li class="colx2-left_m">
									<span>ZIP:</span><br />
									<input type="text" id="s_zip" name="s_zip" value="<?php echo isset($outArrClientStoreEditDetails[0]['zip']) ? $outArrClientStoreEditDetails[0]['zip'] : ''; ?>"/>
								</li>
								 <li class="colx2-left_m">
									<span>Email:</span><br />
									<input type="text" id="s_email" name="s_email" value="<?php echo isset($outArrClientStoreEditDetails[0]['email']) ? $outArrClientStoreEditDetails[0]['email'] : ''; ?>"/>
								</li>
								 <li class="colx2-left_m">
									<span>Store trigger threshold:</span><br />
									<input type="text" id="s_trigger_threshold" name="s_trigger_threshold" value="<?php echo isset($outArrClientStoreEditDetails[0]['store_trigger_threshold']) ? $outArrClientStoreEditDetails[0]['store_trigger_threshold'] : ''; ?>"/>
								</li>
								<li class="colx2-left_m">
									<span>Store update threshold:</span><br />
									<input type="text" id="s_update_threshold" name="s_update_threshold" value="<?php echo isset($outArrClientStoreEditDetails[0]['store_update_threshold']) ? $outArrClientStoreEditDetails[0]['store_update_threshold'] : ''; ?>"/>
								</li>
								<!-- <li class="colx2-left_m">
									<span>Store related offer:</span><br />
									<input type="text" id="p_website" name="p_website" value="<?php echo isset($outArrClientProductEdit[0]['pd_url']) ? $outArrClientProductEdit[0]['pd_url'] : ''; ?>"/>
								</li> -->
								
                                
                               						
							</ul>
							<div class="clear"></div>
							<fieldset class="grey-bg no-margin">
							<p style="float:right;">
								<button>Update</button>
								<a href="<?php echo $config['LIVE_URL'].'clients/id/'.$cid.'/stores/' ?>"><button type="button" style="margin-left:2px;">Cancel</button></a>
							</p>
						</fieldset>
						</div>	
			</form>
			</div>
		</section>