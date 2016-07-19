  <section id="main-content">
        <section class="wrapper">
<div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumbs-alt">
                        <li>
                            <a href="<?php echo $config['LIVE_URL'];?>">Dashboard</a>
                        </li>
                         <li>
                            <a class="current" href="#">Add Client Details</a>
                        </li>
                    </ul>
                </div>
</div>       
        <div class="row">
           <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
               <?php if(isset($outArrClientEditDetails[0]['id']) && $outArrClientEditDetails[0]['id']!=0){echo "Update Client Details";}else{echo "Add Client Details";}?>
            </header>
			<div class="panel-body">
				<form action="" class="form-horizontal" id="frm_save_client" name="frm_save_client">
				<?php 
				    $cid=isset($outArrClientEditDetails[0]['id']) ? $outArrClientEditDetails[0]['id'] : 0;
					if (isset($outArrClientEditDetails[0]['logo']) && $outArrClientEditDetails[0]['logo']!=""){
						$dispLogo = str_replace("{client_id}",$cid,$config['files']['logo']).$outArrClientEditDetails[0]['logo'];
					}else{
						$dispLogo = $config['LIVE_URL']."views/images/no-image.png";
					}
					if (isset($outArrClientEditDetails[0]['background_image']) && $outArrClientEditDetails[0]['background_image']!=""){
						$dispBackgroundImage = str_replace("{client_id}",$cid,$config['files']['background']).$outArrClientEditDetails[0]['background_image'];
					}else{
						$dispBackgroundImage = $config['LIVE_URL']."views/images/no-image.png";
					}
					if (isset($outArrClientEditDetails[0]['active']) && $outArrClientEditDetails[0]['active']=="1"){
						$dispClientStatus = "YES";
					}else{
						$dispClientStatus = "NO";
					}
				?>
					<div class="form-group last">
						<label class="control-label col-md-3">Upload Logo</label>
						<div class="col-md-9">
							<div class="fileupload fileupload-new" data-provides="fileupload">
								<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
								    <img src="<?php echo $dispLogo;?>" alt="">
								</div>
								<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
								<div>
								   <span class="btn btn-white btn-file">
								   <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select image</span>
								   <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
								   <input type="file" id="c_logo" name="c_logo" class="default">
								   </span>
									<a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> Remove</a>
								</div>
							</div>
							<span class="label label-danger">NOTE!</span>
									 <span>
									 Attached image thumbnail is
									 supported in Latest Firefox, Chrome, Opera,
									 Safari and Internet Explorer 10 only
									 </span>
						</div>
					</div>
					<div class="form-group last">
						<label class="control-label col-md-3">Upload Background Image</label>
						<div class="col-md-9">
							<div class="fileupload fileupload-new" data-provides="fileupload">
								<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
									<img src="<?php echo $dispBackgroundImage;?>" alt="">
								</div>
								<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
								<div>
								   <span class="btn btn-white btn-file">
								   <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select image</span>
								   <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
								   <input type="file" id="c_bgimage" name="c_bgimage" class="default">
								   </span>
									<a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> Remove</a>
								</div>
							</div>
							<span class="label label-danger">NOTE!</span>
								 <span>
								 Attached image thumbnail is
								 supported in Latest Firefox, Chrome, Opera,
								 Safari and Internet Explorer 10 only
								 </span>
						</div>
					</div>
					
					<div class="form-group ">
						<label for="c_name" class="control-label col-lg-3">Client Name<span style="color:red;">*</span></label>
						<div class="col-lg-6">
							<input class=" form-control" id="c_name" name="c_name" type="text" required="" value="<?php echo isset($outArrClientEditDetails[0]['name']) ? $outArrClientEditDetails[0]['name'] : ''; ?>">
						</div>
					</div>
					<div class="form-group ">
						<label for="c_prefix" class="control-label col-lg-3">Prefix</label>
						<div class="col-lg-6">
							<input class=" form-control" id="c_prefix" name="c_prefix" type="text" value="<?php echo isset($outArrClientEditDetails[0]['prefix']) ? $outArrClientEditDetails[0]['prefix'] : ''; ?>">
						</div>
					</div>
					<div class="form-group ">
						<label for="c_status" class="control-label col-lg-3">Status</label>
						<div class="col-lg-6">
							<select class="form-control m-bot15" id="c_status" name="c_status">
						        <option value="">Select</option>
                                <option value="1" <?=($outArrClientEditDetails[0]['active']=="1")?"selected":""?>>Active</option>
								<option value="0" <?=($outArrClientEditDetails[0]['active']=="0")?"selected":""?>>InActive</option>
							</select>
						</div>
					</div>
					<div class="form-group ">
						<label for="c_website" class="control-label col-lg-3">Website Url</label>
						<div class="col-lg-6">
							<input class=" form-control" id="c_website" name="c_website" type="text" value="<?php echo isset($outArrClientEditDetails[0]['url']) ? $outArrClientEditDetails[0]['url'] : ''; ?>">
						</div>
					</div>
					<div class="form-group ">
						<label for="bcid" class="control-label col-lg-3">Background Color</label>
						<div class="col-lg-6">
						<?php if(isset($outArrClientEditDetails[0]['background_color']) && $outArrClientEditDetails[0]['background_color']!=''){
						    $bgColor= hex2rgb($outArrClientEditDetails[0]['background_color']);
						}else{$bgColor="rgb(255, 146, 180)";}?>
							<div data-color-format="rgb" data-color="<?php echo $bgColor;?>" class="input-append colorpicker-default color">
								<input id="bcid"  name="bcid" class="form-control" value="" type="text" >
								<span class=" input-group-btn add-on">
									<button class="btn btn-white" type="button" style="padding: 8px">
									  <i style="background-color:<?php echo $bgColor;?>"></i>
									</button>
								</span>
							</div>
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
					<div class="form-group ">
						<label for="lcid" class="control-label col-lg-3">Light Color</label>
						<div class="col-lg-6">
						<?php if(isset($outArrClientEditDetails[0]['light_color']) && $outArrClientEditDetails[0]['light_color']!=''){
						    $lightColor= hex2rgb($outArrClientEditDetails[0]['light_color']);
						}else{$lightColor="rgb(255, 146, 180)";}?>
							<div data-color-format="rgb" data-color="<?php  echo $lightColor; ?>" class="input-append colorpicker-default color">
							    <input id="lcid" name="lcid" class="form-control" value="" type="text" >
								<!--  <input type="hidden" id="hdn_lcid" name="hdn_lcid" value="" />-->
							    <span class=" input-group-btn add-on">
									  <button class="btn btn-white" type="button" style="padding: 8px">
										  <i style="background-color: <?php  echo $lightColor; ?>"></i>
									  </button>
							    </span>
							</div>
						</div>
					</div>
					<div class="form-group ">
						<label for="dcid" class="control-label col-lg-3">Dark Color</label>
						<div class="col-lg-6">
						<?php if(isset($outArrClientEditDetails[0]['dark_color']) && $outArrClientEditDetails[0]['dark_color']!=''){
						    $darkColor= hex2rgb($outArrClientEditDetails[0]['dark_color']);
						}else{$darkColor="rgb(255, 146, 180)";}?>
							<div data-color-format="rgb" data-color="<?php  echo $darkColor; ?>" class="input-append colorpicker-default color">
							    <input id="dcid" name="dcid" class="form-control" value="" type="text" >
								<!--  <input type="hidden" id="hdn_lcid" name="hdn_lcid" value="" />-->
							    <span class=" input-group-btn add-on">
									  <button class="btn btn-white" type="button" style="padding: 8px">
										  <i style="background-color: <?php  echo $darkColor; ?>"></i>
									  </button>
							    </span>
							</div>
						</div>
					</div>
					<div class="form-group ">
						<label for="c_is_demo" class="control-label col-lg-3">Is Demo</label>
						<div class="col-lg-6">
							<select class="form-control m-bot15" id="c_is_demo" name="c_is_demo">
						        <option value="">Select</option>
                                <option value="1" <?=($outArrClientEditDetails[0]['is_demo']=="1")?"selected":""?>>Yes</option>
								<option value="0" <?=($outArrClientEditDetails[0]['is_demo']=="0")?"selected":""?>>No</option>
							</select>
						</div>
					</div>
					
					<div class="form-group ">
						<label for="c_client_vertical" class="control-label col-lg-3">Client Veriticals</label>
						<div class="col-lg-6">
							<select class="form-control m-bot15" id="c_client_vertical" name="c_client_vertical">
						        <option value="" >Please Select</option>
								 <?php for($i=0;$i<count($outArrAllVerticalClients);$i++){?>
								 <?php if($outArrAllVerticalClients[$i]['client_vertical_id']==$outArrClientEditDetails[0]['client_vertical_id']){?>
								 <option value="<?php echo $outArrAllVerticalClients[$i]['client_vertical_id'];?>" selected="selected"><?php echo $outArrAllVerticalClients[$i]['client_vertical_name'];?></option>
								 <?php }else{?>
								<option value="<?php echo $outArrAllVerticalClients[$i]['client_vertical_id'];?>" ><?php echo $outArrAllVerticalClients[$i]['client_vertical_name'];?></option>
								<?php }}?>
							</select>
						</div>
					</div>
					<div class="form-group ">
						<label for="c_is_location_based" class="control-label col-lg-3">Location Based</label>
						<div class="col-lg-6">
							<select class="form-control m-bot15" id="c_is_location_based" name="c_is_location_based">
						        <option value="">Select</option>
                                <option value="1" <?=($outArrClientEditDetails[0]['is_location_based']=="1")?"selected":""?>>Yes</option>
								<option value="0" <?=($outArrClientEditDetails[0]['is_location_based']=="0")?"selected":""?>>No</option>
							</select>
						</div>
					</div>
					<div class="form-group ">
						<label for="c_is_affiliate" class="control-label col-lg-3">Affiliate</label>
						<div class="col-lg-6">
							<select class="form-control m-bot15" id="c_is_affiliate" name="c_is_affiliate">
						        <option value="">Select</option>
                                <option value="1" <?=($outArrClientEditDetails[0]['is_affiliate']=="1")?"selected":""?>>Yes</option>
								<option value="0" <?=($outArrClientEditDetails[0]['is_affiliate']=="0")?"selected":""?>>No</option>
							</select>
						</div>
					</div>
					<div class="form-group">
                        <label for="store_notify_msg" class="control-label col-lg-3">Store notify message:</label>
                        <div class="col-lg-6">
                           <textarea id="store_notify_msg" name="store_notify_msg" style="width: 413px; height: 100px;"><?php echo isset($outArrClientEditDetails[0]['store_notify_msg']) ? $outArrClientEditDetails[0]['store_notify_msg'] :''; ?></textarea>
                        </div>
                    </div>
					<div class="form-group ">
						<label for="c_company" class="control-label col-lg-3">Company</label>
						<div class="col-lg-6">
							<input class=" form-control" id="c_company" name="c_company" type="text" value="<?php echo isset($outArrClientEditDetails[0]['client_details_company']) ? $outArrClientEditDetails[0]['client_details_company'] :''; ?>">
						</div>
					</div>
					<div class="form-group ">
						<label for="c_address" class="control-label col-lg-3">Address</label>
						<div class="col-lg-6">
							<input class=" form-control" id="c_address" name="c_address" type="text" value="<?php echo isset($outArrClientEditDetails[0]['client_details_address']) ? $outArrClientEditDetails[0]['client_details_address'] :''; ?>">
						</div>
					</div>
					<div class="form-group ">
						<label for="c_country" class="control-label col-lg-3">Country</label>
						<div class="col-lg-6">
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
					<div class="form-group ">
						<label for="c_state" class="control-label col-lg-3">State/Province:</label>
						<div class="col-lg-6">
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
					<div class="form-group ">
						<label for="c_city" class="control-label col-lg-3">City</label>
						<div class="col-lg-6">
							<input class=" form-control" id="c_city" name="c_city" type="text" value="<?php echo isset($outArrClientEditDetails[0]['client_details_city']) ? $outArrClientEditDetails[0]['client_details_city'] :''; ?>">
						</div>
					</div>
					<div class="form-group ">
						<label for="c_zip" class="control-label col-lg-3">ZIP</label>
						<div class="col-lg-6">
							<input class=" form-control" id="c_zip" name="c_zip" type="text" value="<?php echo isset($outArrClientEditDetails[0]['client_details_zip']) ? $outArrClientEditDetails[0]['client_details_zip'] :''; ?>">
						</div>
					</div>
					<div class="form-group ">
						<label for="c_phone" class="control-label col-lg-3">Phone</label>
						<div class="col-lg-6">
							<input class=" form-control" id="c_phone" name="c_phone" type="text" value="<?php echo isset($outArrClientEditDetails[0]['client_details_phone']) ? $outArrClientEditDetails[0]['client_details_phone'] :''; ?>">
						</div>
					</div>
					<div class="form-group ">
						<label for="c_email" class="control-label col-lg-3">Email</label>
						<div class="col-lg-6">
							<input class=" form-control" id="c_email" name="c_email" type="text" value="<?php echo isset($outArrClientEditDetails[0]['client_details_email']) ? $outArrClientEditDetails[0]['client_details_email'] :''; ?>">
						</div>
					</div>
					<div class="form-group ">
						<label for="c_currency_code" class="control-label col-lg-3">Currency Code</label>
						<div class="col-lg-6">
							<input type="text" id="c_currency_code" name="c_currency_code" value="" class="form-control" placeholder="Ex: USD" value="<?php echo isset($outArrClientEditDetails[0]['client_details_currency_code']) ? $outArrClientEditDetails[0]['client_details_currency_code'] :''; ?>">
						</div>
					</div>
					<div class="form-group ">
						<label for="c_shipping_methods" class="control-label col-lg-3">Shipping Methods</label>
						<div class="col-lg-6">
							<input type="text" id="c_shipping_methods" name="c_shipping_methods" value="" class="form-control" placeholder="Ex: 1,2" value="<?php echo isset($outArrClientEditDetails[0]['client_shipping_methods']) ? $outArrClientEditDetails[0]['client_shipping_methods'] :''; ?>">
						</div>
					</div>
					<div class="form-group ">
						<label for="c_payment_methods" class="control-label col-lg-3">Payment Methods</label>
						<div class="col-lg-6">
							<input type="text" id="c_payment_methods" name="c_payment_methods" value="" class="form-control" placeholder="Ex: 1,2" value="<?php echo isset($outArrClientEditDetails[0]['client_payment_methods']) ? $outArrClientEditDetails[0]['client_payment_methods'] :''; ?>">
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-offset-3 col-lg-6">
						    <input type="hidden" id="c_id" name="c_id" value="<?php echo isset($outArrClientEditDetails[0]['id']) ? $outArrClientEditDetails[0]['id'] :0; ?>">
							<input type="hidden" id="action" name="action" value="CreateClient" >
							<button class="btn btn-primary" type="submit">Save</button>
							<button class="btn btn-default" type="button">Cancel</button>
						</div>
					</div>
		
					

				</form>
			</div>
           
		   
		   
		   
        </section>

        
              </div>
        </div>
  </section>
    </section>




