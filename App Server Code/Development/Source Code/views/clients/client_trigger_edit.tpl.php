 <section id="main-content">
     <section class="wrapper">
        <div class="row">
            <div class="col-md-12">
                <ul class="breadcrumbs-alt">
                    <li>
                        <a href="<?php echo $config['LIVE_URL'];?>">Dashboard</a>
                    </li>
                    <li>
                         <a href="<?php echo $config['LIVE_URL'];?>clients/id/<?php echo $cid; ?>/triggers/">Trigger List</a>
                    </li>
                    <li>
                        <a href="<?php echo $config['LIVE_URL'];?>clients/id/<?php echo $cid;?>/triggers/<?php echo $outArrClientTriggerEdit[0]['id'];?>/view/ ">Trigger Details</a>
                    </li>
                    <li>
                      <a class="current" href="#">Edit Trigger Details</a>
                     </li>
                 </ul>
            </div>
        </div>   
    <div style="clear: both;"></div>
         <div class="row">
             <div class="col-lg-12">
                <section class="panel">
                   <header class="panel-heading">
                      Edit Trigger Details
                        </header>
			
    <div class="panel-body">
        <form id="frm_client_trigger_edit" name="frm_client_trigger_edit" method="post" action="" class="form-horizontal bucket-form" enctype="multipart/form-data">
		    <div style="float:right;"><button type="button"  class="btn btn-success" onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $cid; ?>/triggers/<?php echo $outArrClientTriggerEdit[0]['id']; ?>/visuals/'">Visuals</button></div>					   
				<div id="frm_error" style="display:none;"></div>
				<input type="hidden" name="t_id" id="t_id" value="<?php echo $outArrClientTriggerEdit[0]['id'];?>" />
				<input type="hidden" name="c_id" id="c_id" value="<?php echo $cid;?>" />
				<input type="hidden" name="t_exist_image" id="t_exist_image" value="<?php echo isset($outArrClientTriggerEdit[0]['url']) ? $outArrClientTriggerEdit[0]['url'] : ''; ?>" />	

	   <div class="form-group">
	        <label class="control-label col-md-3">Modify Image&nbsp;</label>
                <div class="col-md-4">       
                    <?php if($outArrClientTriggerEdit[0]['url']!=''){?>
                            <img id="img_prev" src="<?php echo str_replace("{client_id}",$cid,$config['files']['triggers']).$outArrClientTriggerEdit[0]['url']; ?>" alt="<?php echo $outArrClientTriggerEdit[0]['url'];?>" width="250px" height="250px">
                                <?php }else{?>
                            <img id="img_prev" src="<?php echo $config['LIVE_URL'];?>views/images/no-product.png" alt="your image" width="150px" height="150px"/>
                                <?php }?>
							    <div>
								<input type="file" onchange="showimagepreview(this)" id="t_image" name="t_image"  />                            
                                </div>
                                 <div id="frm_error_image"></div>
                </div>
        </div>             				
		<div class="form-group">
           <label class="col-sm-3 control-label">Title&nbsp;</label>
                <div class="col-sm-6">
			      <input type="text" id="t_title" name="t_title" class="form-control" value="<?php echo isset($outArrClientTriggerEdit[0]['title']) ? $outArrClientTriggerEdit[0]['title'] : ''; ?>"/>
			        <div id="frm_error_title" style="display:none;"></div>
                 </div>
		</div>					
		<div class="form-group">
             <label class="col-sm-3 control-label">Height&nbsp;</label>
                <div class="col-sm-6">
                    <input type="text" id="t_height" name="t_height" class="form-control" value="<?php echo isset($outArrClientTriggerEdit[0]['height']) ? $outArrClientTriggerEdit[0]['height'] : ''; ?>"/> 
                </div>
       </div>        				
	<div class="form-group">
         <label class="col-sm-3 control-label">Width&nbsp;</label>
            <div class="col-sm-6">
                    <input type="text" id="t_width" name="t_width" class="form-control" value="<?php echo isset($outArrClientTriggerEdit[0]['width']) ? $outArrClientTriggerEdit[0]['width'] : ''; ?>"/>
             </div>
    </div>
   <div class="form-group">
        <label class="col-sm-3 control-label">Instruction&nbsp;</label>
            <div class="col-sm-6">
                <textarea id="t_instruction" name="t_instruction" class="form-control" style="width: 413px; height: 100px;"><?php echo isset($outArrClientTriggerEdit[0]['instruction']) ? $outArrClientTriggerEdit[0]['instruction'] : ''; ?></textarea>
            </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Status&nbsp;</label>
            <div class="col-lg-6">
                <select name="t_status" id="t_status" class="form-control m-bot15" >             
                    <?php if(isset($outArrClientTriggerEdit[0]['active']) && $outArrClientTriggerEdit[0]['active']=='1'){?>
                     <option value="1" selected="selected" >Active</option>
                     <option value="0" >In Active</option>
                     <?php }else if(isset($outArrClientTriggerEdit[0]['active']) && $outArrClientTriggerEdit[0]['active']=='0'){?>                     
                     <option value="0" selected="selected">In Active</option>
                     <option value="1">Active</option>									  
                      <?php }?>
                </select>
             </div>
    </div>
	<div class="form-group">
        <label class="col-sm-3 control-label col-lg-3" for="inputSuccess">Client Verticals&nbsp;</label>
            <div class="col-lg-6">
                <select name="t_client_vertical" id="t_client_vertical" class="form-control m-bot15" >             
                    <option value="0" >Please Select</option>
                    <?php for($i=0;$i<count($outArrAllVerticalClients);$i++){?>
                    <?php if($outArrAllVerticalClients[$i]['client_vertical_id']==$outArrClientTriggerEdit[0]['client_vertical_id']){?>
                    <option value="<?php echo $outArrAllVerticalClients[$i]['client_vertical_id'];?>" selected="selected" ><?php echo $outArrAllVerticalClients[$i]['client_vertical_name'];?></option>                                     
                    <?php }else{?>
                    <option value="<?php echo $outArrAllVerticalClients[$i]['client_vertical_id'];?>" ><?php echo $outArrAllVerticalClients[$i]['client_vertical_name'];?></option>
                    <?php }}?>
                </select>
            </div>
    </div>	
    <div class="clear"></div>
	<fieldset class="grey-bg no-margin">
	    <p style="float:right;">
			<button class="btn btn-success">Save</button>
				<a href="<?php echo $config['LIVE_URL'].'clients/id/'.$cid.'/triggers' ?>"><button type="button" class="btn btn-danger" style="margin-left:2px;">Cancel</button></a>
		</p>
	</fieldset>
        </form>
    </div>
        </section>
            </div>
        </div>
    </section>
   </section>




					
					








	
