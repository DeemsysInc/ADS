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
$("form#addAudioVisualForm").submit(function(){
 // alert("audioform");
	var isRequired = true;
	var visual_id = jQuery("#visual_id").val();
	var audiofile = jQuery("#audiofile").val();
	//alert(audiofile);
	jQuery("form input").removeClass("error");
	
	 if(jQuery("#audiofile").val()!='')
		{
			var fileExtension = ['mp3'];
			if ($.inArray(jQuery("#audiofile").val().split('.').pop().toLowerCase(), fileExtension) == -1) {
					jQuery('#frm_error_audio').html("Only '.mp3' format is allowed.", {type: 'error'}).css("color", "red").show();
				jQuery("#audiofile").addClass("error");
				isRequired = false;
		}
	}
	if (isRequired==false){
			$("html").scrollTop(0);
			return false;
		}
	$('#loadingmessage').show();  // show the loading message.
    var formData = new FormData($(this)[0]);
        formData.append('action', "saveAudioVisual");
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
				//alert("Audio added successfully");
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
	<form class="form-horizontal bucket-form" id="addAudioVisualForm"  name="addAudioVisualForm" method="post"  enctype="multipart/form-data">
  <div style="display:none" id="loadingmessage" align="center">
  <img src="<?php echo $config['LIVE_URL']; ?>views/images/loading-cafe.gif">
      </div>
  <input type="hidden" value="saveAudioVisual" name="action" id="action">
    <input type="hidden" value="<?php echo $cid;?>" name="cid" id="cid">
    <input type="hidden" value="<?php echo $tid;?>" name="tid" id="tid">
    <input type="hidden" value="<?php echo $visual_id;?>" name="visual_id" id="visual_id">	 
	<p><span style="color:red;">*</span> Fields are mandatory</p>
	<div style="display:none;" id="frm_error">
    <p style="" class="message error no-margin">&nbsp;</p>
  </div> 
  
  <table width="200" >
  <tr>
    <td height="45"> <span><span style="color:red;">*</span>AddAudio:</span></td>
    <td><input type="file" name="audiofile" id="audiofile" value=""/></td>
  </tr>
  <div id="frm_error_audio"></div>
	</table> 
				  <div align="right">
				 <button  class="btn btn-success">Save</button>&nbsp;
				 <input type="reset" class="btn btn-danger" value="Clear"/></div>	
                </form>
				