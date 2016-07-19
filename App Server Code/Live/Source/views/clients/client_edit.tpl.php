<div id="control-bar" class="grey-bg clearfix" style="opacity: 1;">
  <div class="container_12">
    <div class="float-left">
      <button type="button" onclick="history.go(-1);return false;"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-180.png" width="16" height="16" > Back</button>
    </div>
    <div style="float:left; padding-top: 6px;text-align: center;width: 80%;"><h1 id="client_name"><?php echo isset($outArrClientEditDetails[0]['name']) ? $outArrClientEditDetails[0]['name'] : ''; ?></h1><br /><div id='loadingmessage' style='display:none'> <img src='<?php echo $config['LIVE_URL']."views/images/loading-cafe.gif";?>'/> <br />Updating data....</div></div>
    

        <div class="float-right"> 
			<button type="button" class="red" onclick="deleteClient('<?php echo isset($outArrClientEditDetails[0]['id']) ? $outArrClientEditDetails[0]['id'] : 0; ?>');">Delete Client</button> 
		</div>
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


.image-fav {
  display:none;
  position:absolute;
  bottom:0;
  left:0;
  width:15px;
  height:15px;
  background-image:url(http://www.codeflex.com.au/images2013/hoverplus.png);
}
.image-thumb:hover .image-fav {
  display:block;
}
.image-fav:hover {
  background-image:url(http://www.codeflex.com.au/images2013/hoverplus.png);
}

</style>
<ul id="notifications" style="display:none;width:534px">
<li style="">Client details updated Successfully..<span class="close-bt"></span></li>
</ul>
<section class="grid_12">
			<div class="block-border">
			<form id="frm_client_edit" name="frm_client_edit" method="post" action="" class="block-content form" enctype="multipart/form-data">
				<h1>Edit Client Details</h1>
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
						<div id="form_content">
							<div id="frm_error" style="display:none;"><p class="message error no-margin" style="">&nbsp;</p></div>
							
                            <input type="hidden" name="c_id" id="c_id" value="<?php echo $cid;?>" />
                            <ul>
                             <li class="colx2-right" style="width:54%">
                            
                                    
                                    <fieldset style="height:150px;" class="block-content form">
                                    <h1>Modify Logo:</h1><br />
                                     <div id="frm_error2" style="display:none;">
                                          <p style="">&nbsp;</p>
                                      </div>
                                        <p style="float:left;padding:25px;">
                                           <img id="img_prev"  src="<?php echo $dispLogo; ?>" alt="client logo" width="203px" height="115px" />
                                       <a href="#" class="image-fav"></a>
                                        </p>
                                        <p style="float:right;padding:12px;"><input type="file" onchange="previewLogo(this);" id="c_logo" name="c_logo" /><?php /*?><button class="blue" type="button">Modify Logo</button><?php */?></p>
                                    </fieldset>
                                 <fieldset style="height:280px;width:94%" class="block-content form">
                                    <h1>Modify Background Image:</h1><br />
                                     <div id="frm_error3" style="display:none;">
                                          <p style="">&nbsp;</p>
                                      </div>

                                        <p style="float:left;padding:25px;">
                                           <img id="img_prev_bgimage"  src="<?php echo $dispBackgroundImage; ?>" alt="Client Background image" width="253px" height="230px"/>
                                        </p>
                                        <p style="float:right;padding:12px;"><input type="file" onchange="previewBgImage(this);" id="c_bgimage" name="c_bgimage" /><?php /*?><button class="blue" type="button">Modify Logo</button><?php */?></p>
                                    </fieldset>
								</li>
                                
                                
                               
								
                                <li class="colx2-left_m">
									<span>Client Name:</span><br />
									<input type="text" id="c_name" name="c_name" value="<?php echo isset($outArrClientEditDetails[0]['name']) ? $outArrClientEditDetails[0]['name'] : ''; ?>"/>
								</li>
                               
                               
                              	<li class="colx2-left_m">
									<span>Prefix:</span><br />
									<input type="text" id="c_prefix" name="c_prefix" value="<?php echo isset($outArrClientEditDetails[0]['prefix']) ? $outArrClientEditDetails[0]['prefix'] : ''; ?>"/>
								</li>
								<li class="colx2-left_m">
									<span>Status:</span><br />
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
                                    
                                    
								</li>
								<li class="colx2-left_m">
                                 
									<span>Website:</span><div id="frm_error_url" style="display:none;">
                                          <p style="">&nbsp;</p>
                                      </div><br />
									<input type="text" id="c_website" name="c_website" value="<?php echo isset($outArrClientEditDetails[0]['url']) ? $outArrClientEditDetails[0]['url'] : ''; ?>"/>
								</li>
                                <li class="colx2-left_m">
									<span>Background Color:</span><br />
									
                                    <input id="bcid" class="minicolors" type="text" value="<?php echo $outArrClientEditDetails[0]['background_color']; ?>" style="margin-bottom: 6px;"><input type="hidden" id="hdn_bcid" name="hdn_bcid" value="<?php echo $outArrClientEditDetails[0]['background_color']; ?>" />
								</li>
                                
                                
                                <li class="colx2-left_m">
									<span>Light Color:</span><br />
								<input id="lcid" class="minicolors" type="text" value="<?php echo $outArrClientEditDetails[0]['light_color']; ?>"style="margin-bottom: 6px;"><input type="hidden" id="hdn_lcid" name="hdn_lcid" value="<?php echo $outArrClientEditDetails[0]['light_color']; ?>" />
								</li>
                                  <li class="colx2-left_m">
									<span>Dark Color:</span><br />
								<input id="dcid" class="minicolors" type="text" value="<?php echo $outArrClientEditDetails[0]['dark_color']; ?>"style="margin-bottom: 6px;"><input type="hidden" id="hdn_dcid" name="hdn_dcid" value="<?php echo $outArrClientEditDetails[0]['dark_color']; ?>" />
								</li>
								
								<li class="colx2-left_m">
									<span>Is Demo:</span><br />
                                    <select name="c_is_demo" id="c_is_demo" class="full-width">
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
                                </li>
                                <li class="colx2-left_m">
									<span>Client Veriticals:</span><br />
                                    
                                    <select name="c_client_vertical" id="c_client_vertical">
                                    <option value="0" >Please Select</option>
                                     <?php for($i=0;$i<count($outArrAllVerticalClients);$i++){?>
                                      <?php if($outArrAllVerticalClients[$i]['client_vertical_id']==$outArrClientEditDetails[0]['client_vertical_id']){?>
                                      <option value="<?php echo $outArrAllVerticalClients[$i]['client_vertical_id'];?>" selected="selected" ><?php echo $outArrAllVerticalClients[$i]['client_vertical_name'];?></option>
                                      
                                    <?php }else{?>
                                    <option value="<?php echo $outArrAllVerticalClients[$i]['client_vertical_id'];?>" ><?php echo $outArrAllVerticalClients[$i]['client_vertical_name'];?></option>
                                    <?php }}?>
                                    </select>
                                </li>
                                <li class="colx2-left_m">
									<span>Store notify message:</span><br />
								    <textarea id="store_notify_msg" name="store_notify_msg" style="width: 413px; height: 100px;"><?php echo isset($outArrClientEditDetails[0]['store_notify_msg']) ? $outArrClientEditDetails[0]['store_notify_msg'] :''; ?></textarea>
							    </li>
                                								
							</ul>
							<div class="clear"></div>
							<fieldset class="grey-bg no-margin">
							<p style="float:right;">
								<button>Update</button>
								<a href="<?php echo $config['LIVE_URL'].'clients/id/'.$cid;?>"><button type="button" style="margin-left:2px;">Cancel</button></a>
							</p>
						</fieldset>
						</div>	
			</form>
			</div>
		</section>