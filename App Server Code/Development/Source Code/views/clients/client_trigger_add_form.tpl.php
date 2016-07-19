  <section id="main-content">
        <section class="wrapper">
<div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumbs-alt">
                        <li>
                            <a href="<?php echo $config['LIVE_URL'];?>">Dashboard</a>
                        </li>
                         <li>
                            <a class="current" href="#">Add Triggers Details</a>
                        </li>
                    </ul>
                </div>
</div>       
        <div class="row">
           <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
               Add Triggers Details
            </header>
			
            <div class="panel-body">
			    <form id="frm_client_trigger_add" name="frm_client_trigger_add" method="post" action="" class="form-horizontal bucket-form" enctype="multipart/form-data">
			    <div id='loadingmessage' style='display:none'> <img src='<?php echo $config['LIVE_URL']."views/images/loading-cafe.gif";?>'/> <br />Updating data....</div>
				<p><span style="color:red;">*</span> Fields are mandatory</p>
				<div id="frm_error" style="display:none;"></div>
				<input type="hidden" name="cid" id="cid" value="<?php echo $cid;?>" />
				<div class="form-group">
					<fieldset style="height:215px;" class="block-content form">
						<label class="control-label col-md-3">Upload Image:</label>
						<div class="col-md-4">
							<p style="float:right;padding:25px;"><img id="img_prev" src="<?php echo $config['LIVE_URL'];?>views/images/no-product.png" alt="your image"width="253px" height="115px"/></p>
							<p style="float:right;padding:12px;"><input type="file" onchange="showimagepreview(this)" id="t_image" name="t_image" /></p>
						</div> <div id="frm_error_image"></div>
					</fieldset>
				</div>	
				<div class="form-group">
					<label class="col-sm-3 control-label"><span style="color:red;">*</span>Title:</label>
					<div class="col-sm-6">
					   <input type="text" id="t_title" name="t_title" value="" class="form-control"><div id="frm_error_title"></div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label"><span style="color:red;">*</span>Width:</label>
					<div class="col-sm-6">
				       <input type="text" id="t_width" name="t_width" value="" class="form-control"><div id="frm_error_width"></div>
					</div>
				</div>	
				<div class="form-group">
					<label class="col-sm-3 control-label"><span style="color:red;">*</span>Height:</label>
					<div class="col-sm-6">
					    <input type="text" id="t_height" name="t_height" value="" class="form-control"><div id="frm_error_height"></div>
					</div>
				</div>
				
			    		
			     <div class="form-group">
					<label class="col-sm-3 control-label">Instruction:</label>
				    <div class="col-sm-6">
				      <input type="text" id="t_instruction" name="t_instruction" value="" class="form-control">
					</div>
				</div>	
				<div class="form-group">
					<label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Status:</label>
					<div class="col-lg-6">
						<select name="t_status" id="t_status" class="form-control m-bot15" value="">         
							<option value="" >Please Select</option>
							<option value="1" >Active</option>
							<option value="0">In Active</option>
						</select>
					</div>
				</div>	
                <div class="form-group">
                    <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Client Verticals:</label>
                    <div class="col-lg-6">
					    <select name="t_client_vertical" id="t_client_vertical" class="form-control m-bot15" value="">        
							<option value="" >Please Select</option>
						    <?php for($i=0;$i<count($outArrAllVerticalClients);$i++){?>
							<option value="<?php echo $outArrAllVerticalClients[$i]['client_vertical_id'];?>" ><?php echo $outArrAllVerticalClients[$i]['client_vertical_name'];?></option>
						    <?php }?>
					    </select>
                    </div>
                </div>	
          	    <div class="clear"></div>
						<fieldset class="grey-bg no-margin">
							<p style="float:right;">
						<button  class="btn btn-success">Save</button>
						<a href="<?php echo $config['LIVE_URL'].'clients/id/'.$cid.'/triggers' ?>"><button type="button" class="btn btn-danger">Cancel</button></a>
							</p>
						</fieldset>					
				   					
                </form>
            </div>
        </section>

        
              </div>
        </div>
  </section>
    </section>
				 
				 
				 
				 
				 
				 
				 
				 
				 
				 
				 
				 
				 
				 
				 
				 
				 
				 
				 
				 
				 
				 
				 
				 