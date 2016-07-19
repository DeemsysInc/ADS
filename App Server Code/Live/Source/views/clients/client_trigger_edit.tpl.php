
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
			<form id="frm_client_trigger_edit" name="frm_client_trigger_edit" method="post" action="" class="block-content form" enctype="multipart/form-data">
				<h1>Edit Trigger</h1>
                <div style="float:right;"><button type="button" class="blue" onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $cid; ?>/triggers/<?php echo $outArrClientTriggerEdit[0]['id']; ?>/visuals/'">Visuals</button></div>
						<div id="form_content">
							<div id="frm_error" style="display:none;"></div>
							<input type="hidden" name="t_id" id="t_id" value="<?php echo $outArrClientTriggerEdit[0]['id'];?>" />
                            <input type="hidden" name="c_id" id="c_id" value="<?php echo $cid;?>" />
                            <input type="hidden" name="t_exist_image" id="t_exist_image" value="<?php echo isset($outArrClientTriggerEdit[0]['url']) ? $outArrClientTriggerEdit[0]['url'] : ''; ?>" />
							<ul>
                             <li class="colx2-right">
                            
									<span>Modify Image:</span><br />
									<input type="file" onchange="readURL(this);" id="t_image" name="t_image" />
                                   <div>
                                   <?php if($outArrClientTriggerEdit[0]['url']!=''){?>
									   
                                    <img id="img_prev" src="<?php echo str_replace("{client_id}",$cid,$config['files']['triggers']).$outArrClientTriggerEdit[0]['url']; ?>" alt="<?php echo $outArrClientTriggerEdit[0]['url'];?>" width="250px" height="250px">
       
                                       <?php }else{?>
                             <img id="img_prev" src="<?php echo $config['LIVE_URL'];?>views/images/no-product.png" alt="your image" width="250px" height="250px"/>

                                    <?php }?>
                                    </div>
                                 
								</li>
								<li class="colx2-left_m">
									<span>Title:</span><br />
									<input type="text" id="t_title" name="t_title" value="<?php echo isset($outArrClientTriggerEdit[0]['title']) ? $outArrClientTriggerEdit[0]['title'] : ''; ?>"/>
								</li>
                               
                               
                              	<li class="colx2-left_m">
									<span>Height:</span><br />
									<input type="text" id="t_height" name="t_height" value="<?php echo isset($outArrClientTriggerEdit[0]['height']) ? $outArrClientTriggerEdit[0]['height'] : ''; ?>"/>
								</li>
								<li class="colx2-left_m">
									<span>Width:</span><br />
									<input type="text" id="t_width" name="t_width" value="<?php echo isset($outArrClientTriggerEdit[0]['width']) ? $outArrClientTriggerEdit[0]['width'] : ''; ?>"/>
								</li>
								<li class="colx2-left_m">
									<span>Instruction:</span><br />
									<textarea id="t_instruction" name="t_instruction"  style="width: 413px; height: 100px;"><?php echo isset($outArrClientTriggerEdit[0]['instruction']) ? $outArrClientTriggerEdit[0]['instruction'] : ''; ?></textarea>
								</li>
                                
                                <li class="colx2-left_m">
									<span>Status:</span><br />
                                    
                                    <select name="t_status" id="t_status">
                                      
                                      <?php if(isset($outArrClientTriggerEdit[0]['active']) && $outArrClientTriggerEdit[0]['active']=='1'){?>
                                      <option value="1" selected="selected" >Active</option>
                                      <option value="0" >In Active</option>
                                      <?php }else if(isset($outArrClientTriggerEdit[0]['active']) && $outArrClientTriggerEdit[0]['active']=='0'){?>
                                      
                                      <option value="0" selected="selected">In Active</option>
                                      <option value="1">Active</option>
                                    <?php }?>
                                    </select>
                                    
								</li>
								<li class="colx2-left_m">
									<span>Client Veriticals:</span><br />
                                    
                                    <select name="t_client_vertical" id="t_client_vertical">
                                    <option value="0" >Please Select</option>
                                     <?php for($i=0;$i<count($outArrAllVerticalClients);$i++){?>
                                      <?php if($outArrAllVerticalClients[$i]['client_vertical_id']==$outArrClientTriggerEdit[0]['client_vertical_id']){?>
                                      <option value="<?php echo $outArrAllVerticalClients[$i]['client_vertical_id'];?>" selected="selected" ><?php echo $outArrAllVerticalClients[$i]['client_vertical_name'];?></option>
                                      
                                    <?php }else{?>
                                    <option value="<?php echo $outArrAllVerticalClients[$i]['client_vertical_id'];?>" ><?php echo $outArrAllVerticalClients[$i]['client_vertical_name'];?></option>
                                    <?php }}?>
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