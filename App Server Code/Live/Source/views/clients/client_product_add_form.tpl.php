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
			<form id="frm_client_product_add" name="frm_client_product_add" method="post" class="block-content form" enctype="multipart/form-data">
          
<input type="hidden" id="cid" name="cid" value="<?php echo $cid;?>"/>
				<h1>Add Product Details</h1>
    					<div id="form_content">
                        <div id="frm_error" style="display:none;"></div>
                        <p><span style="color:red;">*</span> Fields are mandatory</p>
							
							<ul>
                                <li class="colx2-right" style="width:53%">	
                                    <fieldset style="height:338px;" class="block-content form">
                                      <h1>Upload Image:</h1><br />
                                      
                                        <div style="float:left;padding:25px;">
                                           <img id="img_prev_product"  src="<?php echo $config['LIVE_URL']."views/images/no-product.png";?>" alt="client logo" width="253px" height="250px" />
                                        </div>
                                        <div style="float:right;padding:12px;"><input type="file" onchange="previewProductImage(this);" id="p_image" name="p_image" /><?php /*?><button class="blue" type="button">Modify Logo</button><?php */?></div>
                                    </fieldset1>
                                </li>
                                <li class="colx2-left_m">
									<span style="color:red;">*</span><span>Title:</span><br />
									<input type="text" id="p_title" name="p_title" value=""/>
								</li>
                                <li class="colx2-left_m">
									<span>Barcode:</span><br />
									<input type="text" id="p_barcode" name="p_barcode" value=""/>
								</li>
								<li class="colx2-left_m">
									<span>Status:</span><br />
                                    <select name="p_status" id="p_status">
                                      <option value="0" >Please Select</option>
                                      <option value="1" >Active</option>
                                      <option value="0">In Active</option>	
                                    </select>
   								</li>
								<li class="colx2-left_m">
									<span>Description:</span><br />
									<textarea name="p_description" id="p_description" style="width: 416px; height: 99px;"></textarea>
								</li>
                                <li class="colx2-left_m">
									<span>Price:</span><br />
									
                                    <input type="text" id="p_price" name="p_price" value=""/>
								</li>
                                <li class="colx2-left_m">
									<span>Short description:</span><br />
									<textarea name="p_shortdescription" id="p_shortdescription" style="width: 416px; height: 99px;"></textarea>
								</li>
                                <!-- <li class="colx2-left_m">
									<span>Html:</span><br />
									<textarea name="p_html" id="p_html" style="width: 416px; height: 99px;"></textarea>
								</li> -->
                                	
                                <li class="colx2-left_m">
									<span>Website:</span><br />
                                    <div id="frm_error_url" style="display:none;">
                                          <p style="">&nbsp;</p>
                                      </div>
                                    <input type="text" id="p_website" name="p_website" value=""/>
									
								</li>
                                <!-- <li class="colx2-left_m">
									<span>Red:</span>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="checkbox" id="p_red" name="p_red" value="1" style="width:15%"/>
								</li>
                                <li class="colx2-left_m">
									<span>Green:</span>&nbsp;
                                    <input type="checkbox" id="p_green" name="p_green" value="1" style="width:15%"/>
								</li>
                                <li class="colx2-left_m">
									<span>Blue:</span>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="checkbox" id="p_blue" name="p_blue" value="1" style="width:15%"/>
								</li>
                                <li class="colx2-left_m">
									<span>Background:</span><br />
                                   <select name="p_background" id="p_background">
                                      <option value="0" >Please Select</option>
                                      <option value="1" >Show</option>
                                      <option value="0">Hide</option>	
                                    </select>
									
								</li> -->
                                <li class="colx2-left_m">
									<span>Catagory:</span><br />
                                   <select name="p_catagory" id="p_catagory">
                                   <option value="0" >Please Select</option>
                                   <?php for($i=0;$i<count($getCatagories);$i++){?>
                                      <option value="<?php echo isset($getCatagories[$i]['pd_category_id']) ? $getCatagories[$i]['pd_category_id'] : '';?>" ><?php echo isset($getCatagories[$i]['pd_category_name']) ? $getCatagories[$i]['pd_category_name'] : '';?></option>
                                      
                                   <?php }?>   
                                    </select>
									
								</li>
                                <!-- <li class="colx2-left_m">
									<span>Style:</span><br />
                                   <select name="p_style" id="p_style"">
                                   <option value="0" >Please Select</option>
                                   <?php for($i=0;$i<count($getStyles);$i++){?>
                                      <option value="<?php echo isset($getStyles[$i]['id']) ? $getStyles[$i]['id'] : '';?>" ><?php echo isset($getStyles[$i]['id']) ? $getStyles[$i]['description'] : '';?></option>
                                      
                                   <?php }?>   
                                    </select>
									
								</li> 
                                <li class="colx2-left_m">
									<span>Offer:</span>
                                    <input type="checkbox" id="p_offer" name="p_offer" value="1"/ style="width:15%">
								</li>
								-->	
							</ul>
							<div class="clear"></div>
							<fieldset class="grey-bg no-margin">
							<p style="float:right;">
								<button>Save</button>
								<a href="<?php echo $config['LIVE_URL'];?>clients/id/<?php echo $cid;?>/products"><button type="button" style="margin-left:40px;">Cancel</button></a>
							</p>
						</fieldset>
						</div>	
			</form>
			</div>
		</section>