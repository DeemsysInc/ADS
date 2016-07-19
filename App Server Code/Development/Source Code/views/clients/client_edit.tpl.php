    <section id="main-content">
        <section class="wrapper">
<div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumbs-alt">
                        <li>
                            <a href="<?php echo $config['LIVE_URL'];?>">Dashboard</a>
                        </li>
                        <li>
                            <a href="<?php echo $config['LIVE_URL'];?>clients/">Client list</a>
                        </li>
                        <li>
                           <a href="<?php echo $config['LIVE_URL'];?>clients/id/<?php echo isset($outArrClientEditDetails[0]['id']) ? $outArrClientEditDetails[0]['id'] : ''; ?>">Client Details</a>
                        </li>
                         <li>
                            <a class="current" href="#">Edit Client Details</a>
                        </li>
                    </ul>
                </div>
</div>       

<!--<div id="notifications"></div>-->

<div style="clear: both;"></div>


         <div class="row">
           <div class="col-lg-12">
            <section class="panel">
            <header class="panel-heading">
              Edit Client Details
            </header>
			
            <div class="panel-body">
				<form id="frm_client_edit" name="frm_client_edit" method="post" action="" class="form-horizontal bucket-form" enctype="multipart/form-data">
                               <div class="profile-desk">
				
                <?php 
			if (isset($outArrClientEditDetails[0]['logo']) && $outArrClientEditDetails[0]['logo']!=""){
				$dispLogo = str_replace("{client_id}",$cid,$config['files']['logo']).$outArrClientEditDetails[0]['logo'];
			}else{
				$dispLogo = $config['LIVE_URL']."views/images/no_logo.png";
			}
			if (isset($outArrClientEditDetails[0]['background_image']) && $outArrClientEditDetails[0]['background_image']!=""){
				$dispBackgroundImage = str_replace("{client_id}",$cid,$config['files']['background']).$outArrClientEditDetails[0]['background_image'];
			}else{
				$dispBackgroundImage = $config['LIVE_URL']."views/images/no-product.png";
			}
			if (isset($outArrClientEditDetails[0]['active']) && $outArrClientEditDetails[0]['active']=="1"){
				$dispClientStatus = "YES";
			}else{
				$dispClientStatus = "NO";
			}
		?>
		<!--<div id="frm_error" style="display:none;"><p class="message error no-margin" style="">&nbsp;</p></div>-->
		
		 <!--<div style="float:left; padding-top: 6px;text-align: center;width: 80%;"><h1 id="client_name"><?php echo isset($outArrClientEditDetails[0]['name']) ? $outArrClientEditDetails[0]['name'] : ''; ?></h1><br /></div>-->
				<div id='loadingmessage' align="center" style='display:none'><img src='<?php echo $config['LIVE_URL']."views/images/loading-cafe.gif";?>'/> <br/>Updating data....</div>
                            <input type="hidden" name="c_id" id="c_id" value="<?php echo $cid;?>" />
						<div id="frm_error" style="display:none;"></div>	
		             <div class="form-group">
				           
                             <label class="control-label col-md-3"> Logo&nbsp;</label>
                                <div class="col-md-4">
					 <img id="img_prev"  src="<?php echo $dispLogo; ?>" alt="client logo" width="150px" height="100px" />
			    <p style="float:right;padding:12px;"><input type="file" onchange="showimagepreview(this)" id="c_logo" name="c_logo" /></p>
                                </div> <div id="frm_error_logo"></div>
                            </div>	
							
				 <div class="form-group">
				        <!--<fieldset style="height:215px;" class="block-content form">-->
                           <label class="control-label col-md-3"> Background Image&nbsp;</label>
                               <div class="col-md-4">  
					<img id="img_prev"  src="<?php echo $dispBackgroundImage; ?>" alt="Client Background image" width="150px" height="100px"/>
			     <p style="float:right;padding:12px;"><input type="file" onchange="showimagepreview(this)" id="c_bgimage" name="c_bgimage" /></p>
                                </div> <div id="frm_error_image"></div>
								 <!--</fieldset>-->
                            </div>		
						<div class="form-group">
                        <label class="col-sm-3 control-label"><span style="color:red;">*</span>Client Name&nbsp;</label>
                        <div class="col-sm-6">
                             <input type="text" id="c_name" name="c_name" value="<?php echo isset($outArrClientEditDetails[0]['name']) ? $outArrClientEditDetails[0]['name'] : ''; ?>" class="form-control"><div id="frm_error"></div>
                        </div>
                    </div>	
							
				   <div class="form-group">
                        <label class="col-sm-3 control-label">Prefix&nbsp;</label>
                        <div class="col-sm-6">
             <input type="text" id="c_prefix" name="c_prefix" value="<?php echo isset($outArrClientEditDetails[0]['prefix']) ? $outArrClientEditDetails[0]['prefix'] : ''; ?>" class="form-control"/></div>
                        </div>
                    	
				<div class="form-group">
                        <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Status:</label>
                        <div class="col-lg-6">
                     	 <select name="c_status" id="c_status" class="form-control m-bot15">
						   <option value="" >Please Select</option>
                                    <?php if(isset($outArrClientEditDetails[0]['active']) && $outArrClientEditDetails[0]['active']==0){?>
									 <option value="0" selected="selected">In Active</option>
                                      <option value="1">Active</option>	
										
									<?php }else if(isset($outArrClientEditDetails[0]['active']) && $outArrClientEditDetails[0]['active']==1){?>
									 <option value="1" selected="selected">Active</option>
                                      <option value="0">In Active</option>	
									
									<?php }else{?>
									 <option value="1" >Active</option>
                                      <option value="0">In Active</option>	
                                    <?php }?>
   
                                    </select>
                        </div>
                    </div>
					
                     <div class="form-group">
                         <label class="col-sm-3 control-label">Website&nbsp;</label>
                        <div class="col-sm-6">
        <input type="text" id="c_website" name="c_website"  class="form-control" value="<?php echo isset($outArrClientEditDetails[0]['url']) ? $outArrClientEditDetails[0]['url'] : ''; ?>"/><div id="frm_error1"></div>
                         </div>
                    </div>
					
           <?php	
 function hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = "rgb($r, $g, $b)";
   //return implode(",", $rgb); // returns the rgb values separated by commas
   return $rgb; // returns an array with the rgb values
}
?>				
           
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Background Color&nbsp;</label>
                        <div class="col-sm-6">
						<div data-color-format="rgb" data-color="<?php  echo hex2rgb($outArrClientEditDetails[0]['background_color']); ?>" class="input-append colorpicker-default color">
                          <input id="bcid" class="form-control" type="text" value="<?php echo $outArrClientEditDetails[0]['background_color']; ?>" />
						  <input type="hidden" id="hdn_bcid" name="hdn_bcid" value="<?php echo $outArrClientEditDetails[0]['background_color']; ?>" />
						 <span class=" input-group-btn add-on">
                                                  <button class="btn btn-white" type="button" style="padding: 8px">
                                                      <i style="background-color:<?php  echo hex2rgb($outArrClientEditDetails[0]['background_color']); ?>"></i>
                                                  </button>
                                              </span>
                                    </div>
                        </div>
                    </div>
					
					<div class="form-group">
                        <label class="col-sm-3 control-label">Light Color&nbsp;</label>
						  <div class="col-sm-6">
                             <div data-color-format="rgb" data-color=" <?php  echo hex2rgb($outArrClientEditDetails[0]['light_color']); ?>" class="input-append colorpicker-default color">
                                  <input id="lcid" class="form-control"  type="text" value="<?php echo $outArrClientEditDetails[0]['light_color']; ?>"/>
									<input type="hidden" id="hdn_lcid" name="hdn_lcid" value="<?php echo $outArrClientEditDetails[0]['light_color']; ?>" />
                                              <span class=" input-group-btn add-on">
                                                  <button class="btn btn-white" type="button" style="padding: 8px">
                                                      <i style="background-color: <?php  echo hex2rgb($outArrClientEditDetails[0]['light_color']); ?>"></i>
                                                  </button>
                                              </span>
                                    </div>
                                </div>
                        </div>
                   
					
					<div class="form-group">
                        <label class="col-sm-3 control-label">Dark Color&nbsp;</label>
                        <div class="col-sm-6">
						<div data-color-format="rgb" data-color="<?php  echo hex2rgb($outArrClientEditDetails[0]['dark_color']); ?>" class="input-append colorpicker-default color">
                        <input id="dcid" class="form-control" type="text" value="<?php echo $outArrClientEditDetails[0]['dark_color']; ?>"/>
						 <input type="hidden" id="hdn_dcid" name="hdn_dcid" value="<?php echo $outArrClientEditDetails[0]['dark_color']; ?>" />
						 <span class=" input-group-btn add-on">
                                     <button class="btn btn-white" type="button" style="padding: 8px">
                                           <i style="background-color: <?php  echo hex2rgb($outArrClientEditDetails[0]['dark_color']); ?>"></i>
                                          </button>
                                              </span>
                                   </div>
                        </div>
                    </div>
					
					
					 <div class="form-group">
                     <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Is Demo&nbsp;</label>
                     <div class="col-lg-6">
                     <select name="c_is_demo" id="c_is_demo" class="form-control m-bot15" > 
                            <option value="" >Please Select</option>					 
                                    <?php if(isset($outArrClientEditDetails[0]['is_demo']) && $outArrClientEditDetails[0]['is_demo']==0){?>
									 <option value="0" selected="selected">NO</option>
                                      <option value="1">YES</option>	
										
									<?php }else if(isset($outArrClientEditDetails[0]['is_demo']) && $outArrClientEditDetails[0]['is_demo']==1){?>
									 <option value="1" selected="selected">YES</option>
                                      <option value="0">NO</option>	
									
									<?php }else{?>
									 <option value="1" >YES</option>
                                      <option value="0">NO</option>	
                                    <?php }?>
   
                                    </select>
                        </div>
                    </div>

				 <div class="form-group">
                     <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Client Veriticals&nbsp;</label>
                     <div class="col-lg-6">
                        <select name="c_client_vertical" id="c_client_vertical" class="form-control m-bot15" >        
							<option value="0" >Please Select</option>
							 <?php for($i=0;$i<count($outArrAllVerticalClients);$i++){?>
							  <?php if($outArrAllVerticalClients[$i]['client_vertical_id']==$outArrClientEditDetails[0]['client_vertical_id']){?>
							  <option value="<?php echo $outArrAllVerticalClients[$i]['client_vertical_id'];?>" selected="selected" ><?php echo $outArrAllVerticalClients[$i]['client_vertical_name'];?></option>
							  
							<?php }else{?>
							<option value="<?php echo $outArrAllVerticalClients[$i]['client_vertical_id'];?>" ><?php echo $outArrAllVerticalClients[$i]['client_vertical_name'];?></option>
							<?php }}?>
                        </select>
                        </div>
                    </div>
					<div class="form-group">
						 <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Location Based:</label>
						 <div class="col-lg-6">
							 <select name="c_is_location_based" id="c_is_location_based" class="form-control m-bot15" value="">         
								 <option value="">Select</option>
								 <?php if(isset($outArrClientEditDetails[0]['is_location_based']) && $outArrClientEditDetails[0]['is_location_based']==0){?>
								 <option value="0" selected="selected">NO</option>
                                 <option value="1">YES</option>	
								<?php }else if(isset($outArrClientEditDetails[0]['is_location_based']) && $outArrClientEditDetails[0]['is_location_based']==1){?>
								 <option value="1" selected="selected">YES</option>
								  <option value="0">NO</option>	
								
								<?php }else{?>
								 <option value="1" >YES</option>
								  <option value="0">NO</option>	
								<?php }?> 
							 </select>
						 </div>
                    </div>
					

									
									
					
					<div class="form-group">
						 <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Affiliate:</label>
						 <div class="col-lg-6">
							 <select name="c_is_affiliate" id="c_is_affiliate" class="form-control m-bot15" value="">         
								 <option value="">Select</option>
								 <?php if(isset($outArrClientEditDetails[0]['is_affiliate']) && $outArrClientEditDetails[0]['is_affiliate']==0){?>
								 <option value="0" selected="selected">NO</option>
                                 <option value="1">YES</option>	
								<?php }else if(isset($outArrClientEditDetails[0]['is_affiliate']) && $outArrClientEditDetails[0]['is_affiliate']==1){?>
								 <option value="1" selected="selected">YES</option>
								  <option value="0">NO</option>	
								
								<?php }else{?>
								 <option value="1" >YES</option>
								  <option value="0">NO</option>	
								<?php }?> 
							 </select>
						 </div>
                    </div>
					
					<div class="form-group">
                        <label class="col-sm-3 control-label">Store notify message&nbsp;</label>
                        <div class="col-sm-6">
                           <textarea id="store_notify_msg" name="store_notify_msg" style="width: 413px; height: 100px;" value=" <?php echo isset($outArrClientEditDetails[0]['store_notify_msg']) ? $outArrClientEditDetails[0]['store_notify_msg'] :'None'; ?>"></textarea>
						  
                    </div>
                    </div>
					
					<div class="form-group">
                         <label class="col-sm-3 control-label">Company:</label>
                        <div class="col-sm-6">
                            <input type="text" id="c_company" name="c_company" value="<?php echo isset($outArrClientEditDetails[0]['client_details_company']) ? $outArrClientEditDetails[0]['client_details_company'] :''; ?>" class="form-control">
                         </div>
                    </div>
					<div class="form-group">
                        <label class="col-sm-3 control-label">Address:</label>
                        <div class="col-sm-6">
                           <input type="text" id="c_address" name="c_address" value="<?php echo isset($outArrClientEditDetails[0]['client_details_address']) ? $outArrClientEditDetails[0]['client_details_address'] :''; ?>" class="form-control">
                        </div>
                    </div>
					
					<div class="form-group">
                        <label class="col-sm-3 control-label">Country:</label>
                        <div class="col-sm-6">
                            <select name="c_country" id="c_country" class="form-control m-bot15" value="">        
								 <option value="0" >Select Country</option>
								 <?php for($i=0;$i<count($outArrCountryList);$i++){?>
								 <?php if($outArrCountryList[$i]['country_code_char2']==$outArrClientEditDetails[0]['client_details_country_code']){?>
								 <option value="<?php echo $outArrCountryList[$i]['country_code_char2'];?>" selected="selected" ><?php echo $outArrCountryList[$i]['country_name'];?></option>
								<?php }else{?>
								<option value="<?php echo $outArrCountryList[$i]['country_code_char2'];?>" ><?php echo $outArrCountryList[$i]['country_name'];?></option>
								<?php }}?>
							</select>
                        </div>
                    </div>
					
					<div class="form-group">
                        <label class="col-sm-3 control-label">State/Province:</label>
                        <div class="col-sm-6">
						    <select name="c_state" id="c_state" class="form-control m-bot15" value="">        
								 <option value="0" >Select State/Province</option>
								 <?php for($i=0;$i<count($outArrStatesList);$i++){?>
								 <?php if($outArrStatesList[$i]['state_subdivision_name']==$outArrClientEditDetails[0]['client_details_state']){?>
								 <option value="<?php echo $outArrStatesList[$i]['state_subdivision_name'];?>"  selected="selected"><?php echo $outArrStatesList[$i]['state_subdivision_name'];?></option>
								 <?php }else{?>
								<option value="<?php echo $outArrStatesList[$i]['state_subdivision_name'];?>" ><?php echo $outArrStatesList[$i]['state_subdivision_name'];?></option>
								<?php }}?>
							</select>
                        </div>
                    </div>
					<div class="form-group">
                        <label class="col-sm-3 control-label">City:</label>
                        <div class="col-sm-6">
                           <input type="text" id="c_city" name="c_city" value="<?php echo isset($outArrClientEditDetails[0]['client_details_city']) ? $outArrClientEditDetails[0]['client_details_city'] :''; ?>" class="form-control">
                        </div>
                    </div>
					<div class="form-group">
                        <label class="col-sm-3 control-label">ZIP:</label>
                        <div class="col-sm-6">
                           <input type="text" id="c_zip" name="c_zip" value="<?php echo isset($outArrClientEditDetails[0]['client_details_zip']) ? $outArrClientEditDetails[0]['client_details_zip'] :''; ?>" class="form-control">
                        </div>
                    </div>
					<div class="form-group">
                        <label class="col-sm-3 control-label">Phone:</label>
                        <div class="col-sm-6">
                           <input type="text" id="c_phone" name="c_phone" value="<?php echo isset($outArrClientEditDetails[0]['client_details_phone']) ? $outArrClientEditDetails[0]['client_details_phone'] :''; ?>" class="form-control">
                        </div>
                    </div>
					<div class="form-group">
                        <label class="col-sm-3 control-label">Email:</label>
                        <div class="col-sm-6">
                           <input type="text" id="c_email" name="c_email" value="<?php echo isset($outArrClientEditDetails[0]['client_details_email']) ? $outArrClientEditDetails[0]['client_details_email'] :''; ?>" class="form-control">
                        </div>
                    </div>
					<div class="form-group">
                        <label class="col-sm-3 control-label">Currency Code:</label>
                        <div class="col-sm-6">
                           <input type="text" id="c_currency_code" name="c_currency_code" value="<?php echo isset($outArrClientEditDetails[0]['client_details_currency_code']) ? $outArrClientEditDetails[0]['client_details_currency_code'] :''; ?>" class="form-control" placeholder="Ex: USD">
                        </div>
                    </div>
					<div class="form-group">
                        <label class="col-sm-3 control-label">Shipping Methods:</label>
                        <div class="col-sm-6">
                           <input type="text" id="c_shipping_methods" name="c_shipping_methods" value="<?php echo isset($outArrClientEditDetails[0]['client_shipping_methods']) ? $outArrClientEditDetails[0]['client_shipping_methods'] :''; ?>" class="form-control" placeholder="Ex: 1">
                        </div>
                    </div>
					<div class="form-group">
                        <label class="col-sm-3 control-label">Payment Methods:</label>
                        <div class="col-sm-6">
                           <input type="text" id="c_payment_methods" name="c_payment_methods" value="<?php echo isset($outArrClientEditDetails[0]['client_payment_methods']) ? $outArrClientEditDetails[0]['client_payment_methods'] :''; ?>" class="form-control" placeholder="Ex: 1,2">
                        </div>
                    </div>
            	 		
          
					<div class="clear"></div>
					<fieldset class="grey-bg required" style="height:40px;">
						<p style="float:right;">
							<button class="btn btn-success">Update</button>
								<a href="<?php echo $config['LIVE_URL'].'clients/id/'.$cid;?>"><button type="button" class="btn btn-danger" style="margin-left:2px;">Cancel</button></a>
							</p>
						</fieldset>

            </div>
             		
                </form>
            </div>
        </section>

        
              </div>
        </div>
  </section>
    </section>











					
							
							
							
					<!--<div class="col-md-9">
                           <div class="profile-desk">
			    <div style="float:left; padding-top: 6px;text-align: center;width: 80%;"><h1 id="client_name"><?php echo isset($outArrClientEditDetails[0]['name']) ? $outArrClientEditDetails[0]['name'] : ''; ?></h1><br />
				<div id='loadingmessage' style='display:none'><img src='<?php echo $config['LIVE_URL']."views/images/loading-cafe.gif";?>'/> <br/>Updating data....</div></div>
                            <input type="hidden" name="c_id" id="c_id" value="<?php echo $cid;?>" />
                               <p>
                                   <div class="prf-box">
								   
								    <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Logo</div>
                                                <div class="col-md-4 col-xs-4">
                                                   <strong>  <img id="img_prev"  src="<?php echo $dispLogo; ?>" alt="client logo" width="203px" height="115px" /></strong>
												   <p style="float:right;padding:12px;"><input type="file" onchange="previewLogo(this);" id="c_logo" name="c_logo" /></p>
												  
                                                </div>
                                             </div>
								   
								   
								   <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Background Image</div>
                                                <div class="col-md-4 col-xs-4">
                                                   <strong><img id="img_prev_bgimage"  src="<?php echo $dispBackgroundImage; ?>" alt="Client Background image" width="150px" height="100px"/> </strong>
												   <p style="float:right;padding:12px;"><input type="file" onchange="previewBgImage(this);" id="c_bgimage" name="c_bgimage" /></p>
												  
                                                </div>
                                             </div>
                                
                                   <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Client Name&nbsp;</div>
                                                <div class="col-md-4 col-xs-4">
									<strong><input type="text" id="c_name" name="c_name" value="<?php echo isset($outArrClientEditDetails[0]['name']) ? $outArrClientEditDetails[0]['name'] : ''; ?>"/></strong>
								
								    </div>
									   </div>
								
								
                                   <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Prefix&nbsp;</div>
                                                <div class="col-md-4 col-xs-4">
									<strong><input type="text" id="c_prefix" name="c_prefix" value="<?php echo isset($outArrClientEditDetails[0]['prefix']) ? $outArrClientEditDetails[0]['prefix'] : ''; ?>"/></strong>
								
								    </div>
									   </div>
								
								   <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Status:</div>
                                                <div class="col-md-4 col-xs-4">
									 <select name="c_status" id="c_status" class="full-width">
                                    <?php if(isset($outArrClientEditDetails[0]['active']) && $outArrClientEditDetails[0]['active']==0){?>
									 <option value="0" selected="selected">In Active</option>
                                      <option value="1">Active</option>	
										
									<?php }else if(isset($outArrClientEditDetails[0]['active']) && $outArrClientEditDetails[0]['active']==1){?>
									 <option value="1" selected="selected">Active</option>
                                      <option value="0">In Active</option>	
									
									<?php }else{?>
									 <option value="1" >Active</option>
                                      <option value="0">In Active</option>	
                                    <?php }?>
   
                                    </select>
								    </div>
									   </div>
									  <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Website&nbsp;</div>
                                                <div class="col-md-4 col-xs-4">
									<strong><input type="text" id="c_website" name="c_website" value="<?php echo isset($outArrClientEditDetails[0]['url']) ? $outArrClientEditDetails[0]['url'] : ''; ?>"/></strong>
								
								    </div>
									   </div> 
									  <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Background Color&nbsp;</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong> <input id="bcid" class="colorpicker-default form-control" type="text" value="<?php echo $outArrClientEditDetails[0]['background_color']; ?>" style="margin-bottom: 6px;">
													<input type="hidden" id="hdn_bcid" name="hdn_bcid" value="<?php echo $outArrClientEditDetails[0]['background_color']; ?>" />
													</strong>
                                                </div>
                                            </div>  
								    <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Light Color:&nbsp;</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><input id="lcid" class="colorpicker-rgba form-control" data-color-format="rgba" type="text" value="<?php echo $outArrClientEditDetails[0]['light_color']; ?>"style="margin-bottom: 6px;">
													<input type="hidden" id="hdn_lcid" name="hdn_lcid" value="<?php echo $outArrClientEditDetails[0]['light_color']; ?>" />
													</strong>
                                                </div>
                                            </div>
                                             <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Dark Color:&nbsp;</div>
                                                <div class="col-md-4 col-xs-4">
                                             <strong><input id="dcid" class="colorpicker-rgba form-control" data-color-format="rgba" type="text" value="<?php echo $outArrClientEditDetails[0]['dark_color']; ?>"style="margin-bottom: 6px;">
											 <input type="hidden" id="hdn_dcid" name="hdn_dcid" value="<?php echo $outArrClientEditDetails[0]['dark_color']; ?>" />
											   </strong>
                                                </div>
                                            </div>
											 <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Is Demo&nbsp;</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong> <select name="c_is_demo" id="c_is_demo" class="full-width">
                                    <?php if(isset($outArrClientEditDetails[0]['is_demo']) && $outArrClientEditDetails[0]['is_demo']==0){?>
									 <option value="0" selected="selected">NO</option>
                                      <option value="1">YES</option>	
										
									<?php }else if(isset($outArrClientEditDetails[0]['is_demo']) && $outArrClientEditDetails[0]['is_demo']==1){?>
									 <option value="1" selected="selected">YES</option>
                                      <option value="0">NO</option>	
									
									<?php }else{?>
									 <option value="1" >YES</option>
                                      <option value="0">NO</option>	
                                    <?php }?>
   
                                    </select>
									</strong>
                                        </div>
                                            </div>
									<div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Client Vertical&nbsp;</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong>
                                    <select name="c_client_vertical" id="c_client_vertical">
                                    <option value="0" >Please Select</option>
                                     <?php for($i=0;$i<count($outArrAllVerticalClients);$i++){?>
                                      <?php if($outArrAllVerticalClients[$i]['client_vertical_id']==$outArrClientEditDetails[0]['client_vertical_id']){?>
                                      <option value="<?php echo $outArrAllVerticalClients[$i]['client_vertical_id'];?>" selected="selected" ><?php echo $outArrAllVerticalClients[$i]['client_vertical_name'];?></option>
                                      
                                    <?php }else{?>
                                    <option value="<?php echo $outArrAllVerticalClients[$i]['client_vertical_id'];?>" ><?php echo $outArrAllVerticalClients[$i]['client_vertical_name'];?></option>
                                    <?php }}?>
                                    </select></strong>
 									  </div>
                                          </div>		
										 <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Store Notify message&nbsp;</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php echo isset($outArrClient[0]['store_notify_msg']) ? $outArrClient[0]['store_notify_msg'] :'None'; ?></strong>
                                                </div>
                                            </div>	
								  </div>
                               </p>
                              
							<div class="clear"></div>
							<fieldset class="grey-bg required" style="height:40px;">
							<p style="float:right;">
								<button class="btn btn-success">Update</button>
								<a href="<?php echo $config['LIVE_URL'].'clients/id/'.$cid;?>"><button type="button" class="btn btn-danger" style="margin-left:2px;">Cancel</button></a>
							</p>
						</fieldset>
						
						   </div>
                       </div>
                      
                 </div>  
					
			</form>
			     
                </section>
            </div>

		 </div>
		 
		     </section>
    </section>-->