 <section id="main-content">
    <section class="wrapper">
        <div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumbs-alt">
                        <li>
                            <a href="<?php echo $config['LIVE_URL'];?>">Dashboard</a>
                        </li>
                        <li>
                            <a href="<?php echo $config['LIVE_URL'];?>clients/id/<?php echo $cid; ?>/products/">Product List</a>
                        </li>
                        <li>
                           <a href="<?php echo $config['LIVE_URL'];?>clients/id/<?php echo $cid;?>/products/<?php echo $pid;?>/view/ ">Product Details</a>
                        </li>
                         <li>
                            <a class="current" href="#">Edit Product Details</a>
                        </li>
                    </ul>
                </div>
        </div>      

<div id="notifications"></div>

<div style="clear: both;"></div>
         <div class="row">
           <div class="col-lg-12">
            <section class="panel">
            <header class="panel-heading">
              Edit Product Details
            </header>
            <div class="panel-body">
                <form id="frm_client_product_edit" name="frm_client_product_edit" method="post" action="" class="form-horizontal bucket-form" enctype="multipart/form-data"> 
			 <div align="center" style="display:none" id="loadingmessage">
                <img src="<?php echo $config['LIVE_URL']; ?>views/images/loading-cafe.gif"><br /><p align="center">Updating data...</p>
             </div>				   
					 <div id="frm_error" style="display:none;"></div> 
					 <input type="hidden" name="p_id" id="p_id" value="<?php echo $pid;?>" />
                     <input type="hidden" name="c_id" id="c_id" value="<?php echo $cid;?>" />
					<div class="form-group">
	                    <label class="control-label col-md-3">Image&nbsp;</label>
                            <div class="col-md-4">       
                                   <?php if(isset($outArrClientProductEdit[0]['pd_image']) && $outArrClientProductEdit[0]['pd_image']!=''){?>
									   
                                    <img id="img_prev" src="<?php echo str_replace("{client_id}",$cid,$config['files']['products']).$outArrClientProductEdit[0]['pd_image']; ?>" alt="<?php echo $outArrClientProductEdit[0]['pd_image'];?>"  width="150px" height="100px">
       
                                       <?php }else{?>
                                    <img id="img_prev" src="<?php echo $config['LIVE_URL'];?>views/images/no-product.png" alt="your image"   width="250px" height="250px"/>

                                    <?php }?>
									<input type="file" onchange="showimagepreview(this)"  id="p_image" name="p_image" />
                                    <div id="frm_error_image"></div>
                            </div>
                    </div>       
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Title&nbsp;</label>
                          <div class="col-sm-6">
                            <input type="text" id="p_title" class="form-control" name="p_title"  value="<?php echo isset($outArrClientProductEdit[0]['pd_name']) ? $outArrClientProductEdit[0]['pd_name'] : ''; ?>"/>
			                    <div id="frm_error_title" style="display:none;"></div>
                            </div>
				    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Barcode&nbsp;</label>
                          <div class="col-sm-6">
                            <input type="text" id="p_barcode" name="p_barcode" class="form-control"  value="<?php echo isset($outArrClientProductEdit[0]['pd_barcode']) ? $outArrClientProductEdit[0]['pd_barcode'] : ''; ?>"/>
                          </div>     
                    </div>  
					<div class="form-group">
					<label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Status&nbsp;</label>
						<div class="col-lg-6">
							 <select name="p_status" id="p_status" class="form-control m-bot15"  > 
								<?php if(isset($outArrClientProductEdit[0]['pd_status']) && $outArrClientProductEdit[0]['pd_status']=='1'){?>
								<option value="1" selected="selected" >Active</option>
								<option value="0" >In Active</option>
								<?php }else if(isset($outArrClientProductEdit[0]['pd_status']) && $outArrClientProductEdit[0]['pd_status']=='0'){?>
								<option value="0" selected="selected">In Active</option>
								<option value="1">Active</option>
								<?php }?>
								</select>
						</div>
					</div>
					<div class="form-group">
					    <label class="col-sm-3 control-label">Description&nbsp;</label>
							<div class="col-sm-6">
			                    <textarea id="p_description" name="p_description" class="form-control" style="width: 413px; height: 100px;"><?php echo isset($outArrClientProductEdit[0]['pd_description']) ? $outArrClientProductEdit[0]['pd_description'] : ''; ?></textarea>
							</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Price&nbsp;$</label>
						    <div class="col-sm-6">
				              <input type="text" id="p_price" name="p_price" class="form-control" value="<?php echo isset($outArrClientProductEdit[0]['pd_price']) ? $outArrClientProductEdit[0]['pd_price'] : ''; ?>"/>
							</div>
					</div>			
					<div class="form-group">
						<label class="col-sm-3 control-label">Short Description&nbsp;</label>
							<div class="col-sm-6">
				              <textarea id="p_shortdescription" name="p_shortdescription"  class="form-control" ><?php echo isset($outArrClientProductEdit[0]['pd_short_description']) ? $outArrClientProductEdit[0]['pd_short_description'] : ''; ?></textarea>
							</div>
					</div>			
				   <div class="form-group">
						<label class="col-sm-3 control-label">Url&nbsp;</label>
							<div class="col-sm-6">
				               <input type="text" id="p_website"  class="form-control" name="p_website"  value="<?php echo isset($outArrClientProductEdit[0]['pd_url']) ? $outArrClientProductEdit[0]['pd_url'] : ''; ?>"/><div id="frm_error_website" style="display:none;"></div>
							</div>	
					</div>						
				    <div class="form-group">
						<label class="col-sm-3 control-label">Button Name&nbsp;</label>
							<div class="col-sm-6">
			                    <input type="text" id="p_button_name" class="form-control" name="p_button_name"  value="<?php echo isset($outArrClientProductEdit[0]['pd_button_name']) ? $outArrClientProductEdit[0]['pd_button_name'] : ''; ?>"/>
							</div>	
					</div>						
					<div class="form-group">
						<label class="col-sm-3 control-label col-lg-3" for="inputSuccess" >Is try on?&nbsp;</label>
							<div class="col-lg-6">
								<select name="p_is_tryon" id="p_is_tryon" class="form-control m-bot15" >             
									<?php if(isset($outArrClientProductEdit[0]['pd_istryon']) && $outArrClientProductEdit[0]['pd_istryon']=='1'){?>
									<option value="1" selected="selected" >YES</option>
									<option value="0" >NO</option>
									<?php }else if(isset($outArrClientProductEdit[0]['pd_istryon']) && $outArrClientProductEdit[0]['pd_istryon']=='0'){?>
									<option value="0" selected="selected">NO</option>
									<option value="1">YES</option>
									<?php }?>
									</select>
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
								<div data-color-format="rgb" data-color="<?php  echo hex2rgb($outArrClientProductEdit[0]['pd_bg_color']); ?>" class="input-append colorpicker-default color" >
								<input id="bcid" name="bcid" class="form-control" type="text" value="<?php echo isset($outArrClientProductEdit[0]['pd_bg_color']) ? $outArrClientProductEdit[0]['pd_bg_color'] : ''; ?>" >
								<input type="hidden" id="hdn_bcid" name="hdn_bcid" value="<?php echo isset($outArrClientProductEdit[0]['pd_bg_color']) ? $outArrClientProductEdit[0]['pd_bg_color'] : ''; ?>" />					           
								<span class=" input-group-btn add-on">
								<button class="btn btn-white" type="button" style="padding: 8px">
								<i style="background-color:<?php  echo hex2rgb($outArrClientProductEdit[0]['pd_bg_color']); ?>"></i>
								</button>
								</span>
							    </div>
							</div>
					</div>
					<div class="form-group">
					    <label class="col-sm-3 control-label col-lg-3" for="inputSuccess" >Background&nbsp;</label>
						    <div class="col-lg-6">
							<select name="p_background" id="p_background" class="form-control m-bot15" >                    
								<?php if(isset($outArrClientProductEdit[0]['pd_hide_bg_image']) && $outArrClientProductEdit[0]['pd_hide_bg_image']=='1'){?>
								<option value="1" selected="selected" >Show</option>
								<option value="0" >Hide</option>
								<?php }else if(isset($outArrClientProductEdit[0]['pd_hide_bg_image']) && $outArrClientProductEdit[0]['pd_hide_bg_image']=='0'){?>
								<option value="0" selected="selected">Hide</option>
								<option value="1">Show</option>
								<?php }?>
							</select>
							</div>
					</div>
				   <div class="form-group">
					    <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Category&nbsp;</label>
						    <div class="col-lg-6">
							  <select name="p_catagory" id="p_catagory" class="form-control m-bot15"  > 
							 <?php //echo $getCatagories ?>
								<?php for($i=0;$i<count($getCatagories);$i++){?>
								<?php if($getCatagories[$i]['pd_category_id']==$outArrClientProductEdit[0]['pd_category_id']){?>
								<option value="<?php echo $getCatagories[$i]['pd_category_id'];?>" selected="selected" ><?php echo $getCatagories[$i]['pd_category_name'];?></option>
								<?php }?>
								<option value="<?php echo $getCatagories[$i]['pd_category_id'];?>" ><?php echo $getCatagories[$i]['pd_category_name'];?></option>
								<?php }?>
								</select>
						    </div>
					</div>
					<fieldset class="grey-bg no-margin">
						<p style="float:right;">
							<button class="btn btn-success">Update</button>
							<a href="<?php echo $config['LIVE_URL'].'clients/id/'.$cid.'/products/' ?>"><button type="button" class="btn btn-danger" style="margin-left:2px;">Cancel</button></a>
						</p>
					</fieldset>
                </form>
            </div>
        </section>

        
              </div>
        </div>
  </section>
    </section>


	







							
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
							   
							   
							   
							   
							   
							   
							   
							   
							   