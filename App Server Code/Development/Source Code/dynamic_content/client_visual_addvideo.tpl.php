<?php
include '../smcfg.php';
$tid = isset($_REQUEST['tid']) ? $_REQUEST['tid'] : 0;
$cid = isset($_REQUEST['cid']) ? $_REQUEST['cid'] : 0;
$visual_id = isset($_REQUEST['visual_id']) ? $_REQUEST['visual_id'] : '';
require_once(SRV_ROOT.'model/clients.model.class.php');
$objClients = new mClients();
$outArrClientProducts = $objClients->getProductsByCID($cid);
if(!empty($visual_id))
{
   $outArrGetVisualProductIds = $objClients->getTriggerVisualsByID($visual_id);
}
?>
<script>
$("form#addVideoVisualForm").submit(function(){
  
	var isRequired = true;
	var visual_id = jQuery("#visual_id").val();
	var x = jQuery("#x").val();
	var y = jQuery("#y").val();
	var scale = jQuery("#scale").val();
	var play_in_3d = jQuery("#play_in_3d").val();
	var ignore_tracking = jQuery("#ignore_tracking").val();
	var videofile = jQuery("#videofile").val();
	//alert("scale"+scale);
	//alert(videofile);
	jQuery("form input").removeClass("error");
	
	 if(jQuery("#videofile").val()!='')
		{
			var fileExtension = ['mp4'];
			if ($.inArray(jQuery("#videofile").val().split('.').pop().toLowerCase(), fileExtension) == -1) {
					jQuery('#frm_error_video').html("Only '.mp4' format is allowed.", {type: 'error'}).css("color", "red").show();
				jQuery("#videofile").addClass("error");
				isRequired = false;
		}
	}
	
	if(visual_id=="")
	{
		
		if (x==""){
			jQuery('#frm_error_x').html('Please enter X value', {type: 'error'}).css("color", "red").show();
			jQuery("#x").addClass("error");
			isRequired = false;
		} if (y==""){
			jQuery('#frm_error_y').html('Please enter Y value', {type: 'error'}).css("color", "red").show();
			jQuery("#y").addClass("error");
			isRequired = false;
		} if (scale==""){
			jQuery('#frm_error_scale').html('Please enter scale', {type: 'error'}).css("color", "red").show();
			jQuery("#scale").addClass("error");
			isRequired = false;
		
		}/*else if (videofile==""){
			jQuery('#frm_error').html('Please upload video', {type: 'error'}).css("color", "red").show();
			jQuery("#videofile").addClass("error");
			isRequired = false;
		}*/
		
		
	}
	if (isRequired==false){
			$("html").scrollTop(0);
			return false;
		}
	$('#loadingmessage').show();  // show the loading message.
    var formData = new FormData($(this)[0]);
        formData.append('action', "saveVideoVisual");
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

<div class="row">
           <div class="col-lg-12">
            <section class="panel">	
            <div class="panel-body">
	<form class="form-horizontal bucket-form" id="addVideoVisualForm"  name="addVideoVisualForm" method="post"  enctype="multipart/form-data">
             <div class="profile-desk">
		<div style="display:none" id="loadingmessage" align="center">
  <img src="<?php echo $config['LIVE_URL']; ?>views/images/loading-cafe.gif">
      </div>
    <input type="hidden" value="saveVideoVisual" name="action" id="action">
    <input type="hidden" value="<?php echo $cid;?>" name="cid" id="cid">
    <input type="hidden" value="<?php echo $tid;?>" name="tid" id="tid">
    <input type="hidden" value="<?php echo $visual_id;?>" name="visual_id" id="visual_id">	 
	<p><span style="color:red;">*</span> Fields are mandatory</p>
  <div style="display:none;" id="frm_error">
    <p style="" class="message error no-margin">&nbsp;</p>
  </div>				 	 
				<div class="form-group">
                        <label class="col-sm-3 control-label"><span style="color:red;">*</span>X:</label>
                        <div class="col-sm-6">
                    <input type="text" value="<?php echo isset($outArrGetVisualProductIds[0]['x']) ? $outArrGetVisualProductIds[0]['x'] : ''; ?>" name="x" id="x"> <div id="frm_error_x"></div>	
                        </div>
                    </div>		 
			   <div class="form-group">
                        <label class="col-sm-3 control-label"><span style="color:red;">*</span>Y:</label>
                        <div class="col-sm-6">
              <input type="text" value="<?php echo isset($outArrGetVisualProductIds[0]['y']) ? $outArrGetVisualProductIds[0]['y'] : ''; ?>" name="y" id="y"><div id="frm_error_y"></div>
                        </div>
                    </div>							 
			 <div class="form-group">
                        <label class="col-sm-3 control-label"><span style="color:red;">*</span>Scale:</label>
                        <div class="col-sm-6">
          <input type="text" value="<?php echo isset($outArrGetVisualProductIds[0]['scale']) ? $outArrGetVisualProductIds[0]['scale'] : ''; ?>" name="scale" id="scale"><div id="frm_error_scale"></div>	
                        </div>
                    </div>							 
					 
			  <div class="form-group">
              <label class="col-sm-5 control-label">Play in 3D:</label>
                <div class="col-sm-6">
        <?php if(isset($outArrGetVisualProductIds[0]['video_in_metaio']) && $outArrGetVisualProductIds[0]['video_in_metaio']==0 ){?>
      <input type="radio" style="width: 14%;" value="1" name="play_in_3d" id="play_in_3d">Yes &nbsp;<input type="radio" style="width: 14%;" value="0" name="play_in_3d" id="play_in_3d" checked="checked" >No
<?php }else if(isset($outArrGetVisualProductIds[0]['video_in_metaio']) && $outArrGetVisualProductIds[0]['video_in_metaio']==1){?>
      <input type="radio" style="width: 14%;" value="1" name="play_in_3d" id="play_in_3d" checked="checked">Yes &nbsp;<input type="radio" style="width: 14%;" value="0" name="play_in_3d" id="play_in_3d"  >No
<?php }else{?>
      <input type="radio" style="width: 14%;" value="1" name="play_in_3d" id="play_in_3d" >Yes &nbsp;<input type="radio" style="width: 14%;" value="0" name="play_in_3d" id="play_in_3d" checked="checked" >No
<?php }?>
                         </div>
                    </div>		 
                 <div class="form-group">
                  <label class="col-sm-5 control-label"><span style="color:red;">*</span>Ignore Tracking:</label>
                   <div class="col-sm-6">
           <?php if(isset($outArrGetVisualProductIds[0]['ignore_tracking']) && $outArrGetVisualProductIds[0]['ignore_tracking']==0 ){?>
      <input type="radio" style="width: 14%;" value="1" name="ignore_tracking" id="ignore_tracking">Yes &nbsp;<input type="radio" style="width: 14%;" value="0" name="ignore_tracking" id="ignore_tracking" checked="checked">No
      <?php }else if(isset($outArrGetVisualProductIds[0]['ignore_tracking']) && $outArrGetVisualProductIds[0]['ignore_tracking']==1){?>
       <input type="radio" style="width: 14%;" value="1" name="ignore_tracking" id="ignore_tracking" checked="checked">Yes &nbsp;<input type="radio" style="width: 14%;" value="0" name="ignore_tracking" id="ignore_tracking">No
       <?php }else{?>
        <input type="radio" style="width: 14%;" value="1" name="ignore_tracking" id="ignore_tracking">Yes &nbsp;<input type="radio" style="width: 14%;" value="0" name="ignore_tracking" id="ignore_tracking" checked="checked">No
     <?php }?>
                        </div>
                    </div>					

					
			<div class="form-group">
                        <label class="col-sm-5 control-label"><span style="color:red;">*</span>Add Video:</label>
                        <div class="col-sm-6">
          <input type="file" name="videofile" id="videofile" value=""/><div id="frm_error_video"></div>
                        </div>
                    </div>	<br/>
					
				   </div>
				  <div align="right">
				 <button  class="btn btn-success">Save</button>&nbsp;
				 <input type="reset" class="btn btn-danger" value="Clear"/></div>	
                </form>
            </div>
        </section>	
					
		  </div>
        </div>		
					
					
					
					
					
					
					
					
					
					
 