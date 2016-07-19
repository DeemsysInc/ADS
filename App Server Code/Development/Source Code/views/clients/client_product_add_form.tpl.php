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
						<a class="current" href="#">Add Product Details</a>
					</li>
				</ul>
			</div>
        </div>       
    <div class="row">
         <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                 Add Product Details
                </header> 
            <div class="panel-body">
			<form id="frm_client_product_add" name="frm_client_product_add" method="post" class="form-horizontal bucket-form" enctype="multipart/form-data">
                <input type="hidden" id="cid" name="cid" value="<?php echo $cid;?>"/>
			    <p><span style="color:red;">*</span> Fields are mandatory</p>
			    <div id="frm_error" style="display:none;"></div>
				<fieldset class="grey-bg no-margin">
				  <p style="float:right;"><button class="btn btn-success" >Save</button><a href="<?php echo $config['LIVE_URL'];?>clients/id/<?php echo $cid;?>/products"><button type="button" class="btn btn-danger"  style="margin-left:2px;">Cancel</button></a></p>
                </fieldset>
				<div class="form-group">
				    <fieldset style="height:215px;" class="block-content form">
                        <label class="control-label col-md-3">Upload Image:</label>
						<div class="col-md-4">
							<p style="float:right;padding:25px;"><img id="img_prev"  src="<?php echo $config['LIVE_URL']."views/images/no_logo.png";?>" alt="client logo" width="253px" height="115px" /></p>
							<p style="float:right;padding:12px;"><input type="file" onchange="showimagepreview(this)"  id="p_image" class="default" name="p_image" /></p>
						</div>
						<div id="frm_error_image"></div>
					</fieldset>
                </div>
				<div class="form-group">
                    <label class="col-sm-3 control-label"><span style="color:red;">*</span>Title:</label>
                        <div class="col-sm-6">
                            <input type="text" id="p_title" name="p_title" value="" class="form-control"/><div id="frm_error_title"></div>
                        </div>
                </div>
			    <div class="form-group">
                    <label class="col-sm-3 control-label">Barcode:</label>
                        <div class="col-sm-6">
                           <input type="text" id="p_barcode" name="p_barcode" value="" class="form-control"/>
						</div>
                </div>
						
				<div class="form-group">
                    <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Status:</label>
                        <div class="col-lg-6">
							<select name="p_status" id="p_status" class="form-control m-bot15" value="">         
							<option value="" >Please Select</option>
							<option value="1" >Active</option>
							<option value="0">In Active</option>
							 </select>
                        </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Description:</label>
                        <div class="col-sm-6">
						    <textarea name="p_description" id="p_description" style="width: 416px; height: 99px;"class="form-control"/></textarea>
						</div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Price($):</label>
                        <div class="col-sm-6">
                            <input type="text" id="p_price" name="p_price" value="" class="form-control"/>
				        </div>
                </div>
				<div class="form-group">
                    <label class="col-sm-3 control-label">Short description:</label>
                        <div class="col-sm-6">
			                <textarea name="p_shortdescription" id="p_shortdescription" class="form-control"/></textarea>
						</div>
                </div>	
                <div class="form-group">
                    <label class="col-sm-3 control-label">Url:</label>
                        <div class="col-sm-6">
                            <input type="text" id="p_website" name="p_website" value="" class="form-control"/><div id="frm_error_url"></div>
				        </div>
                </div>						
                <div class="form-group">
                    <label class="col-sm-3 control-label">Button Name:</label>
                        <div class="col-sm-6">
                             <input type="text" id="p_button_name" name="p_button_name" value="" class="form-control"/>
				        </div>
                </div>	
                <div class="form-group">
                    <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Is try on?:</label>
					<div class="col-lg-6">
						<select name="p_is_tryon" id="p_is_tryon" class="form-control m-bot15" value="">         
						    <option value="" >Please Select</option>
						    <option value="1" >Yes</option>
						    <option value="0">No</option>	
						</select>
					</div>
                </div>
					
				<div class="form-group">
					<label class="col-sm-3 control-label">Background Color:</label>
					<div class="col-sm-6">
					    <div data-color-format="rgb" data-color="rgb(255, 146, 180)" class="input-append colorpicker-default color">
							<input id="bcid" name="bcid" class="form-control" value="" type="text" />
							<input type="hidden" id="hdn_bcid" name="hdn_bcid" value="" />
							<span class=" input-group-btn add-on">
							    <button class="btn btn-white" type="button" style="padding: 8px">
								  <i style="background-color: rgb(124, 66, 84);"></i>
							    </button>
						    </span>
					    </div>
					</div>
                </div>	
					
			    <div class="form-group">
                    <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Background:</label>
                    <div class="col-lg-6">
                        <select name="p_background" id="p_background" class="form-control m-bot15" value="">         
                          <option value="" >Please Select</option>
                          <option value="1" >Show</option>
                           <option value="0">Hide</option>		
                        </select>
                    </div>
                </div>		
					
		       <div class="form-group">
                    <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Category:</label>
					<div class="col-lg-6">
						 <select name="p_catagory" id="p_catagory" class="form-control m-bot15" value="">         
							<!-- <option value="" >Please Select</option>-->
							<?php for($i=0;$i<count($getCatagories);$i++){?>
							<?php if($getCatagories[$i]['pd_category_id']==4){?>
							<option value="<?php echo isset($getCatagories[$i]['pd_category_id']) ? $getCatagories[$i]['pd_category_id'] : '';?>" ><?php echo isset($getCatagories[$i]['pd_category_name']) ? $getCatagories[$i]['pd_category_name'] : '';?></option>
							<?php }}?>   		
						</select>
					</div>
                </div>
				<div class="form-group">
                    <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Is SeeMore Cart?:</label>
					<div class="col-lg-6">
						<select name="p_is_smcart" id="p_is_smcart" class="form-control m-bot15" value="">         
						    <option value="" >Please Select</option>
						    <option value="1" >Yes</option>
						    <option value="0">No</option>	
						</select>
					</div>
                </div>


				
           <div class="clear"></div>       
        <fieldset class="grey-bg no-margin">
          <p style="float:right;">
            <button class="btn btn-success" >Save</button>
           <a href="<?php echo $config['LIVE_URL'];?>clients/id/<?php echo $cid;?>/products">
            <button type="button" class="btn btn-danger"  style="margin-left:2px;">Cancel</button></a> </p>
        </fieldset>
					
					
                </form>
            </div>
        </section>

        
              </div>
        </div>
  </section>
    </section>					
					
					
					
					
					
					
					
					
					
					









							