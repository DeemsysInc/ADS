
<div id="control-bar" class="grey-bg clearfix" style="opacity: 1;"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="history.go(-1);return false;"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-180.png" width="16" height="16" > Back</button>
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
			<form id="frm_client_trigger_visual_edit" name="frm_client_trigger_visual_edit" method="post" action="" class="block-content form" enctype="multipart/form-data">
				<h1>Edit Trigger</h1>
						<div id="form_content">
							<div id="frm_error" style="display:none;"><p class="message error no-margin" style="">&nbsp;</p></div>
							<input type="hidden" name="tid" id="tid" value="<?php echo $tid;?>" />
                            <input type="hidden" name="cid" id="cid" value="<?php echo $cid;?>" />
                            <input type="hidden" name="visual_id" id="visual_id" value="<?php echo $visual_id;?>" />
                            <ul>
                             <li class="colx2-right">
                            
									<span>Modify Image:</span><br />
									<input type="file" onchange="readURL(this);" id="t_image" name="t_image" />
                                   <div>
                                   <?php if($outArrClientTriggerEdit[0]['url']!=''){?>
									   
                                    <img id="img_prev" src="<?php echo str_replace("{client_id}",$cid,$config['files']['triggers']).$outArrClientTriggerEdit[0]['url']; ?>" alt="<?php echo $outArrClientTriggerEdit[0]['url'];?>" />
       
                                       <?php }else{?>
                             <img id="img_prev" src="<?php echo $config['LIVE_URL'];?>views/images/no-product.png" alt="your image" width="150px"/>

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
									<input type="text" id="t_instruction" name="t_instruction" value="<?php echo isset($outArrClientTriggerEdit[0]['instruction']) ? $outArrClientTriggerEdit[0]['instruction'] : ''; ?>"/>
								</li>
																
							</ul>
							<div class="clear"></div>
							<fieldset class="grey-bg no-margin">
							<p style="float:right;">
								<button>Save</button>
								<a href="<?php echo $config['LIVE_URL'].'clients/id/'.$cid.'/triggers' ?>"><button type="button" style="margin-left:40px;">Cancel</button></a>
							</p>
						</fieldset>
						</div>	
			</form>
			</div>
		</section>