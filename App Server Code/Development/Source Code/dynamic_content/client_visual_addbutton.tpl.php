<?php
include '../smcfg.php';
$tid = isset($_REQUEST['tid']) ? $_REQUEST['tid'] : 0;
$cid = isset($_REQUEST['cid']) ? $_REQUEST['cid'] : 0;
$visual_id = isset($_REQUEST['visual_id']) ? $_REQUEST['visual_id'] : '';
require_once(SRV_ROOT.'model/clients.model.class.php');
$objClients = new mClients();
$outArrClientProducts=array();
$outArrClientProducts = $objClients->getProductsByCID($cid);
if(!empty($visual_id))
{
$outArrGetVisualProductIds = $objClients->getTriggerVisualsByID($visual_id);
$arrVisualProductIds=isset($outArrGetVisualProductIds[0]['product_id']) ? $outArrGetVisualProductIds[0]['product_id'] : '';
}
?>
<script>
$("form#frm_visual_addbtn").submit(function(){

    //alert("saveUserProfile");
	var isRequired = true;
	var actionType = "savebtnvisual";
			var x = jQuery("#x").val();
			var y= jQuery("#y").val();
			var cid= jQuery("#cid").val();
			var tid = jQuery("#tid").val();
			var visual_id = jQuery("#visual_id").val();
			var product_id=$("input:radio[name=add_product]:checked").val();
			//alert(product_id);
	jQuery("form input").removeClass("error");
		/* 	if(product_id=='')
			{
				jQuery('#frm_error_product').html('Please Select product', {type: 'error'}).css("color", "red").show();
				jQuery("#add_product").addClass("error");
				return false;
			} */
	
	if(visual_id=="")
	{
		
		if (x==""){
					jQuery('#frm_error_xpostition').html('Please enter X value', {type: 'error'}).css("color", "red").show();
					jQuery("#x").addClass("error");
					isRequired = false;
				}
	   if (y==""){
					jQuery('#frm_error_ypostition').html('Please enter Y value', {type: 'error'}).css("color", "red").show();
					jQuery("#y").addClass("error");
					isRequired = false;
				}	
	}
	if (isRequired==false){
			$("html").scrollTop(0);
			return false;
		}
	$('#loadingmessage').show();  // show the loading message.
    var formData = new FormData($(this)[0]);
      formData.append('action', "savebtnvisual");
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
				///alert("Added Button successfully");
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

<form role="form" class="form-horizontal" method="post" name="frm_visual_addbtn" id="frm_visual_addbtn">
    <div style="display:none;" id="frm_error"><p style="" class="message error no-margin">&nbsp;</p></div>
	<div class="form-group">
		<label class="col-lg-2 col-sm-2 control-label" ><span style="color:red;">*</span>X</label>
		<div class="col-lg-10">
			<input type="text" placeholder="X" name="x" id="x" class="form-control" value="<?php echo isset($outArrGetVisualProductIds[0]['x']) ? $outArrGetVisualProductIds[0]['x']: '';?>"><div id="frm_error_xpostition"></div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-lg-2 col-sm-2 control-label" ><span style="color:red;">*</span>Y</label>
		<div class="col-lg-10">
			<input type="text" placeholder="Y" name="y" id="y" class="form-control" value="<?php echo isset($outArrGetVisualProductIds[0]['y']) ? $outArrGetVisualProductIds[0]['y']: '';?>"><div id="frm_error_ypostition"></div>
		</div>
	</div>
	<div align="left"><input type="radio" name="add_product" id="add_product" checked="checked" value="" /><span>None</span></div>
	<div class="form-group">
		<div class="col-lg-10">
		    <input type="hidden" name="tid" id="tid" value="<?php echo $tid;?>" />
		    <input type="hidden" name="cid" id="cid" value="<?php echo $cid;?>" />
		    <input type="hidden" name="visual_id" id="visual_id" value="<?php echo $visual_id;?>" />
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
				if(isset($arrVisualProductIds) && $arrVisualProductIds==$outArrClientProducts[$i]['pd_id']) { ?>
				    <tr>
						<!--<td class="th table-check-cell"><input type="checkbox" name="selected[]" id="table-selected-1" value="1"></td>-->
						<td align="center" valign="middle"><input type="radio" name="add_product" id="add_product" value="<?php echo $outArrClientProducts[$i]['pd_id']; ?>" checked="checked" /></td>
						<td><?php echo $outArrClientProducts[$i]['pd_name']; ?></td>
						<?php if($outArrClientProducts[$i]['pd_image']==''){?>
						<td><img id="img_prev" src="<?php echo $config['LIVE_URL'];?>views/images/no-product.png" alt="your image" width="200px"/></td>
						<?php }else{?>
						<td><img src="<?php echo str_replace("{client_id}",$cid,$config['files']['products']).$outArrClientProducts[$i]['pd_image']; ?>" alt="<?php echo $outArrClientProducts[$i]['pd_name']; ?>" width="200px;" /></td>
						<?php }?> 
					</tr>
				    <?php }else{ ?>
                    <tr>
						<td align="center" valign="middle"><input type="radio" name="add_product" id="add_product" value="<?php echo $outArrClientProducts[$i]['pd_id']; ?>" /></td>
						<td><?php echo $outArrClientProducts[$i]['pd_name']; ?></td>
						<?php if($outArrClientProducts[$i]['pd_image']==''){?>
						<td><img id="img_prev" src="<?php echo $config['LIVE_URL'];?>views/images/no-product.png" alt="your image" width="200px"/></td>
						<?php }else{?>
						<td><img src="<?php echo str_replace("{client_id}",$cid,$config['files']['products']).$outArrClientProducts[$i]['pd_image']; ?>" alt="<?php echo $outArrClientProducts[$i]['pd_name']; ?>" width="200px;" /></td>
						<?php }?>
                    </tr>  
                <?php }}?>
			    </tbody>
                <tfoot>
                    <tr>
                        <th>Action</th>
                        <th>Title</th>
                        <th>Products</th>
                    </tr>
			    </tfoot>
            </table>			
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			 <button  class="btn btn-success">Save</button>&nbsp;
		     <input type="reset" class="btn btn-danger" value="Clear"/></div>
		</div>
	</div>
</form>