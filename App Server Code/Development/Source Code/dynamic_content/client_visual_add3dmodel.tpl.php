<?php
include '../smcfg.php';
$tid = isset($_REQUEST['tid']) ? $_REQUEST['tid'] : 0;
$cid = isset($_REQUEST['cid']) ? $_REQUEST['cid'] : 0;
$visual_id = isset($_REQUEST['visual_id']) ? $_REQUEST['visual_id'] : '';
require_once(SRV_ROOT.'model/clients.model.class.php');
$objClients = new mClients();
$outArrClientProducts=array();
$outArrClientProducts = $objClients->getProductsByCID($cid);
$outArrGetVisualProductIds=array();
if(!empty($visual_id))
{
 $outArrGetVisualProductIds = $objClients->getTriggerVisualsByID($visual_id);
}
?>
<script>
$("form#frm_visual_add3dmodel").submit(function(){
          var isRequired = true;
	      var actionType = "save3dmodelvisual";
		   var cid = jQuery("#cid").val();
			var tid = jQuery("#tid").val();
			var visual_id = jQuery("#visual_id").val();
			var x = jQuery("#x").val();
			var y= jQuery("#y").val();
			var scale =jQuery("#scale").val();
			var x_rot = jQuery("#x_rot").val();
			var y_rot = jQuery("#y_rot").val();
			var z_rot = jQuery("#z_rot").val();
			
			//var rd_products = jQuery("#rd_products").val();
			var rd_products =$('input:radio[name=rd_products]:checked').val();
			
			if(visual_id=='')
			{
				var isRequired = true;
				jQuery("form input").removeClass("error");
				if (x==""){
					jQuery('#frm_error_x_model').html('Please enter X value', {type: 'error'}).css("color", "red").show();
					jQuery("#x").addClass("error");
					isRequired = false;
				} if (y==""){
					jQuery('#frm_error_y_model').html('Please enter Y value', {type: 'error'}).css("color", "red").show();
					jQuery("#y").addClass("error");
					isRequired = false;
				}if (scale==""){
					jQuery('#frm_error_scale_model').html('Please enter Scale value', {type: 'error'}).css("color", "red").show();
					jQuery("#scale").addClass("error");
					isRequired = false;
				} if (x_rot==""){
					jQuery('#frm_error_x_rot').html('Please enter X-Rotation', {type: 'error'}).css("color", "red").show();
					jQuery("#x_rot").addClass("error");
					isRequired = false;
				} if (y_rot==""){
					jQuery('#frm_error_y_rot').html('Please enter Y-Rotation', {type: 'error'}).css("color", "red").show();
					jQuery("#y_rot").addClass("error");
					isRequired = false;
				} if (z_rot==""){
					jQuery('#frm_error_z_rot').html('Please enter Z-Rotation', {type: 'error'}).css("color", "red").show();
					jQuery("#z_rot").addClass("error");
					isRequired = false;
				}
				
				
			}
			if (isRequired==false){
					$("html").scrollTop(0);
					return false;
				}
   		  
		 if ($('#animateRecog').is(':checked')) {
                       var animate_recog= 1;
                                 } else {
                            var animate_recog= 0;
                                       } 
         
			
	$('#loadingmessage').show();  // show the loading message.
    var formData = new FormData($(this)[0]);
      formData.append('action', "save3dmodelvisual");
    $.ajax({
        url: root_url+"includes/ajax/clients.ajax.php",
        type: 'POST',
        data: formData,
        async: false,
		dataType: 'json',
        success: function (data) {
			
        $('#loadingmessage').hide(); // hide the loading message
            if(data.msg=='success')
			{
			//alert("data="+data);
			//alert("Added 3D Model successfully");
				window.location=data.redirect;
			} else
			{
				jQuery('#frm_error').html('Error Occured: Please contact us', {type: 'error'}).css("color", "red").show();
				return false;
			}
        },
        cache: false,
        contentType: false,
        processData: false
    });

    return false;
});		
</script>
<form class="block-content form" name="frm_visual_add3dmodel" id="frm_visual_add3dmodel" method="post" action="" > 
   <input type="hidden" value="<?php echo $cid;?>" name="cid" id="cid">
    <input type="hidden" value="<?php echo $tid;?>" name="tid" id="tid">
     <input type="hidden" value="<?php echo $visual_id;?>" name="visual_id" id="visual_id">
  <fieldset> <p style="float:left"><span style="color:red;">*</span> Fields are mandatory</p>
  <legend>Properties</legend>
 
  <div style="display:none;" id="frm_error">
    <p style="" class="message error no-margin">&nbsp;</p>
  </div>

<table width="80%"  align="center">
  <tr>
    <td height="42"> <span><span style="color:red;">*</span>X:</span></td>
    <td> <input type="text" value="<?php echo isset($outArrGetVisualProductIds[0]['x']) ? $outArrGetVisualProductIds[0]['x'] : ''; ?>" name="x" id="x"><div id="frm_error_x_model"></div></td><br/>
  </tr>
  <tr>
    <td height="42">  <span><span style="color:red;">*</span>Y:</span></td>
    <td> <input type="text" value="<?php echo isset($outArrGetVisualProductIds[0]['y']) ? $outArrGetVisualProductIds[0]['y'] : ''; ?>" name="y" id="y"><div id="frm_error_y_model"></div></td>
  </tr>
  <tr>
    <td height="42"> <span><span style="color:red;">*</span>Scale:</span></td>
    <td><input type="text" value="<?php echo isset($outArrGetVisualProductIds[0]['scale']) ? $outArrGetVisualProductIds[0]['scale'] : ''; ?>" name="scale" id="scale"><div id="frm_error_scale_model"></div></td>
  </tr>
  <tr>
    <td height="42"> <span><span style="color:red;">*</span>Animate On Recognition:</span></td>
    
    
    <td> <?php if(isset($outArrGetVisualProductIds[0]['animate_on_recognition']) && $outArrGetVisualProductIds[0]['animate_on_recognition']==1 ){?>
    <input type="checkbox" id="animateRecog" checked="checked"  name="animateRecog" value="1" />
    
     <?php } else if(isset($outArrGetVisualProductIds[0]['animate_on_recognition']) && $outArrGetVisualProductIds[0]['animate_on_recognition']==0 ){?> 
       <input type="checkbox" id="animateRecog"  name="animateRecog" value="0" />
      <?php }else{?>
      <input type="checkbox" id="animateRecog"  name="animateRecog" value="1" />
      <?php }?>


    </td>
  </tr>
  
  <tr>
    <td height="42"> <span><span style="color:red;">*</span>X-Rotation:</span></td>
    <td>  <input type="text" value="<?php echo round(rad2deg(isset($outArrGetVisualProductIds[0]['rotation_x']) ? $outArrGetVisualProductIds[0]['rotation_x'] : 0)); ?>" name="x_rot" id="x_rot"><div id="frm_error_x_rot"></div></td>
  </tr>
    <tr>
    <td height="42"> <span><span style="color:red;">*</span>Y-Rotation:</span></td>
    <td>  <input type="text" value="<?php echo round(rad2deg(isset($outArrGetVisualProductIds[0]['rotation_y']) ? $outArrGetVisualProductIds[0]['rotation_y'] : 0)); ?>" name="y_rot" id="y_rot"><div id="frm_error_y_rot"></div></td>
  </tr>
    <tr>
    <td height="42"> <span><span style="color:red;">*</span>Z-Rotation:</span></td>
    <td>  <input type="text" value="<?php echo round(rad2deg(isset($outArrGetVisualProductIds[0]['rotation_z']) ? $outArrGetVisualProductIds[0]['rotation_z'] : 0)); ?>" name="z_rot" id="z_rot"><div id="frm_error_z_rot"></div></td>
  </tr>
  
  
</table>
	

</fieldset>

  <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                      Products
					    </header>
                    <div class="panel-body">
                    <div class="adv-table">
<div align="left"><input type="radio" name="rd_products" id="rd_products"  value="0" checked="checked" /><span>None</span></div>
<?php if(count($outArrClientProducts)>0){?>

  <table  class="display table table-bordered table-striped" id="dynamic-table">
                    <thead>
                    <tr>
					 <th>Action</th>
                        <th>Title</th>
                        <th>Products</th>
                         </tr>
                      </thead>
					   <tbody>
             <?php for($i=0;$i<count($outArrClientProducts);$i++) { 
				?>
                <tr>

                    <td align="center" valign="middle">
                     <?php if(isset($outArrGetVisualProductIds[0]['product_id']) ? $outArrGetVisualProductIds[0]['product_id'] : 0==$outArrClientProducts[$i]['pd_id']){?>
                    <input type="radio" checked="checked" name="rd_products" id="rd_products"  value="<?php echo $outArrClientProducts[$i]['pd_id']; ?>" />
                    <?php }else{?>
                    <input type="radio" name="rd_products" id="rd_products"  value="<?php echo $outArrClientProducts[$i]['pd_id']; ?>" />
                    <?php }?>
                     </td>
                 <td><?php echo $outArrClientProducts[$i]['pd_name']; ?></td>
					<td>
                    <?php if(isset($outArrClientProducts[$i]['pd_image']) && $outArrClientProducts[$i]['pd_image']!=''){?>
					<img height="100" src="<?php echo str_replace("{client_id}",$outArrClientProducts[$i]['client_id'],$config['files']['products']).$outArrClientProducts[$i]['pd_image']; ?>" alt="<?php echo $outArrClientProducts[$i]['pd_name']; ?>" />
                    <?php }else{?>
                    <img id="img_prev" src="<?php echo $config['LIVE_URL'];?>views/images/no-product.png" alt="your image" height="100"/>
                    <?php }?>
                    </td>
            </tr>
				<?php } ?>
			</tbody>
     <?php }else{ echo "There are no products.";}?>
             <tfoot>
                    <tr>
                      <th>Action</th>
                        <th>Title</th>
                        <th>Products</th>
                         </tr>
						 </tfoot>
                    </table>
					 <div align="right">
				 <button  class="btn btn-success">Save</button>&nbsp;
				 <input type="reset" class="btn btn-danger" value="Clear"/></div>
					</form>
                   </div>
                    </div>
                </section>
            </div>
        </div>			 
</form>

