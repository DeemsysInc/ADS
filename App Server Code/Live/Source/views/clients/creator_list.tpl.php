
<div id="control-bar" class="grey-bg clearfix" style="opacity: 1;"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="history.go(-1);return false;"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-180.png" width="16" height="16" > Back</button>
		</div>
		<?php /*?><div style="float:left; padding-top: 6px;text-align: center;width: 80%;"><h1 id="client_name"><?php echo $outArrClientTriggerView[0]['name']; ?></h1></div>
		<?php */?>
			<?php /*?><div class="float-right"> 
			<button type="button" class="red"  onclick="deleteTrigger(<?php echo $cid;?>,<?php echo isset($outArrClientTriggerView[0]['client_id']) ? $outArrClientTriggerView[0]['client_id'] : 0; ?>);">Delete Trigger</button> 
		</div><?php */?>
	</div>
</div>
	<!-- Content -->
	<article class="container_12">
	
<style type="text/css">
ul.simple-list li span{
float:right;
}
</style>
<style>

  
.imgA1 { left: 0px;top: 0px; } 
.imgB1 { left: 0px;position:absolute; top: 0px; z-index: 2; } 
  div.container {
	border: 1px solid #000000;
	overflow: hidden;
	width: 100%;
}

div.left {
	width: 40%;
	float: left;
}

div.right {
	width: 60%;
	float: right;
}
  
</style>



<section class="grid_12">
	<div class="block-border">
	<form class="block-content form" id="complex_form" method="post" >
		<h1>Trigger Details</h1>
		<button type="button" onclick="addButtonCreator();">Add Button</button> &nbsp;<button type="button"  onclick="add3DmodelCreator();">Add 3D Model</button> &nbsp;<button type="button" >Add Video</button> &nbsp;<button type="button" onclick="addURLCreator();" >Add URL</button> &nbsp;
        <div style="border:1px solid #CCC;padding: 10px;height:auto;">
        
			<?php /*?><div style="float:right;"><button onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientTriggerView[0]['client_id']; ?>/triggers/<?php echo $outArrClientTriggerView[0]['id']; ?>/edit/'" type="button" class="blue">Edit</button></div><?php */?>
			<div class="clear"></div>
			<hr style="border-top: 1px dotted #afafaf;"/>
		  <div class="columns">
          <div class="left">
               <img src="<?php echo $config['LIVE_URL']; ?>files/clients/2492/triggers/zales_catalog_rings.png" style="z-index: 1;height:400px;width:340px" class="imgA1" />
                    <p id="displayButton" class="imgB1"></p>
                    <p id="display3dModel" class="imgB1"></p>	
                    <p id="displayURL" class="imgB1"></p>
                    
            </div>
            <div class="right">
                 <span class="label"><h2>Properties</h2></span>
                
                <div id="addButtonProperties" style="display:none;">
                
                <div align="right" id="deletBtnProperties"><img src="<?php echo $config['LIVE_URL']; ?>views/images/TrashCan.png" /></div>
                <div class="column">
                    <p>
                        <label for="btn_opacity">Opacity:</label>
                        <input type="text" id="btn_opacity" style="border:0; color:#f6931f; font-weight:bold;" />
                    </p>
                    <div id="opacity_btn_slider"></div>
                </div>
                
                <div class="column">
                    <p>
                        <label for="btn_rotation"> Rotation:</label>
                        <input type="text" id="btn_rotation" style="border:0; color:#f6931f; font-weight:bold;" />
                    </p>
                    <div id="btn_rotation_slider"></div>
                </div>
                
                  <div class="column">
                    <p>
                        <label for="btn_x">X</label>
                        <input type="text" id="btn_x" style="border:0; color:#f6931f; font-weight:bold;" />
                    </p>
                    <div id="x_btn_slider"></div>
                </div>
                
                 <div class="column">
                    <p>
                        <label for="btn_y">Y:</label>
                        <input type="text" id="btn_y" style="border:0; color:#f6931f; font-weight:bold;" />
                    </p>
                    <div id="y_btn_slider"></div>
                </div>
                
                 <div class="column">
                    <p>
                        <label for="btn_text">Text:</label>
                        <input type="text" id="btn_text" onchange="changeButtonText();" style="border:0; color:#f6931f; font-weight:bold;" />
                    </p>
                   
                </div>
                
                </div>
              
              
              <div id="add3DModelProperties" style="display:none;">
              <div align="right" id="deletModelProperties"><img src="<?php echo $config['LIVE_URL']; ?>views/images/TrashCan.png" /></div>
               <div class="column">
                    <p>
                        <label for="model_x">X:</label>
                        <input type="text" id="model_x" style="border:0; color:#f6931f; font-weight:bold;" />
                    </p>
                    <div id="x_model_slider"></div>
                </div>
                
                 <div class="column">
                    <p>
                        <label for="model_y">Y:</label>
                        <input type="text" id="model_y" style="border:0; color:#f6931f; font-weight:bold;" />
                    </p>
                    <div id="y_model_slider"></div>
                </div>
              
              
                <div class="column">
                    <p>
                        <label for="model_scale">Scale:</label>
                        <input type="text" id="model_scale" style="border:0; color:#f6931f; font-weight:bold;" />
                    </p>
                    <div id="scale_model_slider"></div>
                </div>
                
                <div class="column">
                    <p>
                        <label for="model_x_rot">X-Rotation:</label>
                        <input type="text" id="model_x_rot" style="border:0; color:#f6931f; font-weight:bold;" />
                    </p>
                    <div id="x_rot_model_slider"></div>
                </div>
                
                 <div class="column">
                    <p>
                        <label for="model_y_rot">Y-Rotation:</label>
                        <input type="text" id="model_y_rot" style="border:0; color:#f6931f; font-weight:bold;" />
                    </p>
                    <div id="y_rot_model_slider"></div>
                </div>
                 <div class="column">
                    <p>
                        <label for="model_z_rot"> Z-Rotation:</label>
                        <input type="text" id="model_z_rot" style="border:0; color:#f6931f; font-weight:bold;" />
                    </p>
                    <div id="z_rot_model_slider"></div>
                </div>
                 
                
                
                </div>
                
                
                <div id="addURLProperties" style="display:none;">
                
                <!--<div align="right" id="deletBtnProperties"><img src="<?php echo $config['LIVE_URL']; ?>views/images/TrashCan.png" /></div>-->
                <div class="column">
                    <p>
                        <label for="url">URL:</label>
                        <input type="text" id="url" onchange="changeURL();" style="border:0; color:#f6931f; font-weight:bold;" />
                    </p>
                    
                </div>
                
               
                
                </div>
                
            </div>
          
          
				
			
              
			</div>
		</div>
	</form>
	</div>
	
</section>


<div class="clear"></div>
<script type="text/javascript">
	
</script>
</article>
<!-- End content -->