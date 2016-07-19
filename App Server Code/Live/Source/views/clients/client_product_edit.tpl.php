
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
			<form id="frm_client_product_edit" name="frm_client_product_edit" method="post" action="" class="block-content form" enctype="multipart/form-data">
				<h1>Edit Product</h1>
            
						<div id="form_content">
							<div id="frm_error" style="display:none;"></div>
							<input type="hidden" name="p_id" id="p_id" value="<?php echo $pid;?>" />
                            <input type="hidden" name="c_id" id="c_id" value="<?php echo $cid;?>" />
                           
							<ul>
                             <li class="colx2-right">
                            
									<span>Modify Image:</span><br />
									<input type="file" onchange="readURL(this);" id="p_image" name="p_image" />
                                   <div>
                                   
                                   <?php if(isset($outArrClientProductEdit[0]['pd_image']) && $outArrClientProductEdit[0]['pd_image']!=''){?>
									   
                                    <img id="img_prev" src="<?php echo str_replace("{client_id}",$cid,$config['files']['products']).$outArrClientProductEdit[0]['pd_image']; ?>" alt="<?php echo $outArrClientProductEdit[0]['pd_image'];?>"  width="250px" height="250px">
       
                                       <?php }else{?>
                                    <img id="img_prev" src="<?php echo $config['LIVE_URL'];?>views/images/no-product.png" alt="your image"   width="250px" height="250px"/>

                                    <?php }?>
                                    </div>
                                 
								</li>
								<li class="colx2-left_m">
									<span>Title:</span><br />
									<input type="text" id="p_title" name="p_title" value="<?php echo isset($outArrClientProductEdit[0]['pd_name']) ? $outArrClientProductEdit[0]['pd_name'] : ''; ?>"/>
								</li>
                               
                                <li class="colx2-left_m">
									<span>Description:</span><br />
									<textarea id="p_description" name="p_description"  style="width: 413px; height: 100px;"><?php echo isset($outArrClientProductEdit[0]['pd_description']) ? $outArrClientProductEdit[0]['pd_description'] : ''; ?></textarea>
								</li>
                              	
								<li class="colx2-left_m">
									<span>Barcode:</span><br />
									<input type="text" id="p_barcode" name="p_barcode" value="<?php echo isset($outArrClientProductEdit[0]['pd_barcode']) ? $outArrClientProductEdit[0]['pd_barcode'] : ''; ?>"/>
								</li>
								
                                <li class="colx2-left_m">
									<span>Price:</span><br />
									<input type="text" id="p_barcode" name="p_barcode" value="<?php echo isset($outArrClientProductEdit[0]['pd_price']) ? $outArrClientProductEdit[0]['pd_price'] : ''; ?>"/>
								</li>
                                
                                 <li class="colx2-left_m">
									<span>Short Description:</span><br />
									<textarea id="p_shortdescription" name="p_shortdescription"  style="width: 413px; height: 100px;"><?php echo isset($outArrClientProductEdit[0]['pd_short_description']) ? $outArrClientProductEdit[0]['pd_short_description'] : ''; ?></textarea>
								</li>
                                 
                                <li class="colx2-left_m">
									<span>Website:</span><br />
									<input type="text" id="p_website" name="p_website" value="<?php echo isset($outArrClientProductEdit[0]['pd_url']) ? $outArrClientProductEdit[0]['pd_url'] : ''; ?>"/>
								</li>
                                <!--  <li class="colx2-left_m">
									<span>HTML:</span><br />
									
                                    <textarea id="p_html" name="p_html"  style="width: 413px; height: 100px;"><?php echo isset($outArrClientProductEdit[0]['html']) ? $outArrClientProductEdit[0]['html'] : ''; ?></textarea>
								</li>
                                 <li class="colx2-left_m">
									<span>Background:</span><br />
                                    
                                    <select name="p_background" id="p_background">
                                      
                                      <?php if(isset($outArrClientProductEdit[0]['hide_background']) && $outArrClientProductEdit[0]['hide_background']=='1'){?>
                                      <option value="1" selected="selected" >Yes</option>
                                      <option value="0" >No</option>
                                      <?php }else if(isset($outArrClientProductEdit[0]['hide_background']) && $outArrClientProductEdit[0]['hide_background']=='0'){?>
                                      
                                      <option value="0" selected="selected">No</option>
                                      <option value="1">Yes</option>
                                    <?php }?>
                                    </select>
                                    
								</li>
                                 <li class="colx2-left_m">
									<span>Offer:</span><br />
                                    
                                    <select name="p_offer" id="p_offer">
                                      
                                      <?php if(isset($outArrClientProductEdit[0]['offer']) && $outArrClientProductEdit[0]['offer']=='1'){?>
                                      <option value="1" selected="selected" >Yes</option>
                                      <option value="0" >No</option>
                                      <?php }else if(isset($outArrClientProductEdit[0]['offer']) && $outArrClientProductEdit[0]['offer']=='0'){?>
                                      
                                      <option value="0" selected="selected">No</option>
                                      <option value="1">Yes</option>
                                    <?php }?>
                                    </select>
                                    
								</li> -->
                                 <li class="colx2-left_m">
									<span>Catagory:</span><br />
                                    
                                    <select name="p_catagory" id="p_catagory">
                                    <option value="0" >Please Select</option>
                                     <?php for($i=0;$i<count($getCatagories);$i++){?>
                                      <?php if($getCatagories[$i]['pd_category_id']==$outArrClientProductEdit[0]['pd_category_id']){?>
                                      <option value="<?php echo $getCatagories[$i]['pd_category_id'];?>" selected="selected" ><?php echo $getCatagories[$i]['pd_category_name'];?></option>
                                      
                                    <?php }?>
                                    <option value="<?php echo $getCatagories[$i]['pd_category_id'];?>" ><?php echo $getCatagories[$i]['pd_category_name'];?></option>
                                    <?php }?>
                                    </select>
                                    
								</li>
                                
                               <li class="colx2-left_m">
									<span>Status:</span><br />
                                    
                                    <select name="p_status" id="p_status">
                                      
                                      <?php if(isset($outArrClientProductEdit[0]['pd_status']) && $outArrClientProductEdit[0]['pd_status']=='1'){?>
                                      <option value="1" selected="selected" >Active</option>
                                      <option value="0" >In Active</option>
                                      <?php }else if(isset($outArrClientProductEdit[0]['pd_status']) && $outArrClientProductEdit[0]['pd_status']=='0'){?>
                                      
                                      <option value="0" selected="selected">In Active</option>
                                      <option value="1">Active</option>
                                    <?php }?>
                                    </select>
                                    
								</li>
																
							</ul>
							<div class="clear"></div>
							<fieldset class="grey-bg no-margin">
							<p style="float:right;">
								<button>Save</button>
								<a href="<?php echo $config['LIVE_URL'].'clients/id/'.$cid.'/products/' ?>"><button type="button" style="margin-left:2px;">Cancel</button></a>
							</p>
						</fieldset>
						</div>	
			</form>
			</div>
		</section>