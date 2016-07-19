
<div id="control-bar" class="grey-bg clearfix" style="opacity: 1;"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="history.go(-1);return false;"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-180.png" width="16" height="16" > Back</button>
		</div>
         <div style="float:left; padding-top: 6px;text-align: center;width: 80%;"><h1 id="client_name"><?php echo isset($getClientDetails[0]['name']) ? $getClientDetails[0]['name'] : ''; ?></h1><br /><div id='loadingmessage' style='display:none'> <img src='<?php echo $config['LIVE_URL']."views/images/loading-cafe.gif";?>'/> <br />Updating data....</div></div>
        
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
			<form id="frm_client_trigger_add" name="frm_client_trigger_add" method="post" action="" class="block-content form" enctype="multipart/form-data">
				<h1>Add Trigger</h1>
						<div id="form_content">
                            <p><span style="color:red;">*</span> Fields are mandatory</p>
							<div id="frm_error" style="display:none;"></div>
							<input type="hidden" name="cid" id="cid" value="<?php echo $cid;?>" />
                            <ul>
                             <li class="colx2-right">
                            
									<span>Upload Image:</span><br />
									<input type="file" onchange="readURL(this);" id="t_image" name="t_image" />
                                   <div>
                                    <img id="img_prev" src="<?php echo $config['LIVE_URL'];?>views/images/no-product.png" alt="your image" width="150px"/>                                  
                                    </div>
                                 
								</li>
								<li class="colx2-left_m">
									<span><span style="color:red;">*</span>Title:</span><br />
									<input type="text" id="t_title" name="t_title" value=""/>
								</li>
                               
                               
                              	<li class="colx2-left_m">
									<span><span style="color:red;">*</span>Height:</span><br />
									<input type="text" id="t_height" name="t_height" value=""/>
								</li>
								<li class="colx2-left_m">
									<span><span style="color:red;">*</span>Width:</span><br />
									<input type="text" id="t_width" name="t_width" value=""/>
								</li>
								<li class="colx2-left_m">
									<span>Instruction:</span><br />
									<input type="text" id="t_instruction" name="t_instruction" value=""/>
								</li>
								<li class="colx2-left_m">
									<span>Instruction:</span><br />
									<input type="text" id="t_instruction" name="t_instruction" value=""/>
								</li>
								<li class="colx2-left_m"> <span>Status:</span><br />
						            <select name="t_status" id="t_status">
						              <option value="0" >Please Select</option>
						              <option value="1" >Active</option>
						              <option value="0">In Active</option>
						            </select>
					            </li>
					            <li class="colx2-left_m">
					              <span>Client Veriticals:</span><br />
					              <select name="t_client_vertical" id="t_client_vertical">
					              <option value="" >Please Select</option>
					               <?php for($i=0;$i<count($outArrAllVerticalClients);$i++){?>
					                <option value="<?php echo $outArrAllVerticalClients[$i]['client_vertical_id'];?>" ><?php echo $outArrAllVerticalClients[$i]['client_vertical_name'];?></option>
					              <?php }?>
					              </select>
					            </li>							
							</ul>
							<div class="clear"></div>
							<fieldset class="grey-bg no-margin">
							<p style="float:right;">
								<button>Save</button>
								<a href="<?php echo $config['LIVE_URL'].'clients/id/'.$cid.'/triggers' ?>"><button type="button" style="margin-left:2px;">Cancel</button></a>
							</p>
						</fieldset>
						</div>	
			</form>
			</div>
		</section>