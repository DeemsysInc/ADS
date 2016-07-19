<?php
include '../smcfg.php';
$tid = isset($_REQUEST['tid']) ? $_REQUEST['tid'] : 0;
$cid = isset($_REQUEST['cid']) ? $_REQUEST['cid'] : 0;
$visual_id = isset($_REQUEST['visual_id']) ? $_REQUEST['visual_id'] : '';
$model_id = isset($_REQUEST['model_id']) ? $_REQUEST['model_id'] : '';
require_once(SRV_ROOT.'model/clients.model.class.php');
$objClients = new mClients();
/*$outArrClientProducts = $objClients->getProductsByCID($cid);

$outArrGetModelProductIds = $objClients->getTriggerVisualsByVID($visual_id);

$arrrelId=array();
for($i=0;$i<count($outArrGetModelProductIds);$i++)
{
	$arrrelId[]=$outArrGetModelProductIds[$i]['product_id'];
	
}*/
?>
<script>
$("form#edit_models_form").submit(function(){
  
	 var isRequired = true;
	 jQuery("form input").removeClass("error");
	 if (jQuery("#model").val()==""){
		jQuery('#frm_error_model_edit').html("Please upload Model", {type: 'error'}).css("color", "red").show();
		jQuery("#model").addClass("error");
		isRequired = false;
	}
	 if(jQuery("#model").val()!='')
	{
		var fileExtension = ['md2','obj'];
		if ($.inArray(jQuery("#model").val().split('.').pop().toLowerCase(), fileExtension) == -1) {
			jQuery('#frm_error_model_edit').html("Only '.md2','.obj' formats are allowed.", {type: 'error'}).css("color", "red").show();
				jQuery("#model").addClass("error");
				isRequired = false;
		}
	}
	if(jQuery("#texture").val()!='')
	{
		var fileExtension = ['png'];
		if ($.inArray(jQuery("#texture").val().split('.').pop().toLowerCase(), fileExtension) == -1) {
				jQuery('#frm_error_texture_edit').html("Only '.png' format is allowed.", {type: 'error'}).css("color", "red").show();
				jQuery("#texture").addClass("error");
				isRequired = false;
		}
	}
	if(jQuery("#material").val()!='')
	{
		var fileExtension = ['mtl'];
		if ($.inArray(jQuery("#material").val().split('.').pop().toLowerCase(), fileExtension) == -1) {
				jQuery('#frm_error_material_edit').html("Only '.mtl' formats are allowed.", {type: 'error'}).css("color", "red").show();
				jQuery("#material").addClass("error");
				isRequired = false;
		}
	}	
	if (isRequired==false){
		$("html").scrollTop(0);
		return false;
	}
   
	$('#loadingmessage').show();  // show the loading message.
    var formData = new FormData($(this)[0]);
        formData.append('action', "update3Dmodel");
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
				//alert("Video added successfully");
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

<div class="block-border" align="center">
<form class="block-content form" action="" method="post" name="edit_models_form" id="edit_models_form" enctype="multipart/form-data">
<div style="display:none" id="loadingmessage" align="center">
  <img src="<?php echo $config['LIVE_URL']; ?>views/images/loading-cafe.gif">
</div>
<input type="hidden" name="cid" id="cid" value="<?php echo $cid;?>" />
<input type="hidden" name="tid" id="tid" value="<?php echo $tid;?>" />
<input type="hidden" name="visual_id" id="visual_id" value="<?php echo $visual_id;?>" />
<input type="hidden" name="model_id" id="model_id" value="<?php echo $model_id;?>" />
 
     
     
  <p align="left"><span style="color:red;">*</span> Fields are mandatory</p>
  <div style="display:none;" id="frm_error">
    <p style="" class="message error no-margin">&nbsp;</p>
  </div>

       
 <table width="200">
  <tr>
    <td height="45"> <span><span style="color:red;">*</span>Model:</span></td>
    <td><input type="file" name="model" id="model"/></td>
  </tr><div id="frm_error_model_edit"></div>
  <tr>
    <td height="45">  <span>Texture:</span></td>
    <td><input type="file" name="texture" id="texture"/></td>
  </tr><div id="frm_error_texture_edit"></div>
  <tr>
    <td height="45"> <span>Material:</span></td>
    <td><input type="file" name="material" id="material"/></td>
  </tr><div id="frm_error_material_edit"></div>
</table>
<div align="right"><button  class="btn btn-success">Save</button>&nbsp;
<input type="reset" class="btn btn-danger" value="Clear"/></div>
</form>
</div>