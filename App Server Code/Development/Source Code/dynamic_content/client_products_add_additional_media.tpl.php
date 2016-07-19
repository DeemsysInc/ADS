<?php
include '../smcfg.php';
$pid = isset($_REQUEST['pid']) ? $_REQUEST['pid'] : 0;
$cid = isset($_REQUEST['cid']) ? $_REQUEST['cid'] : 0;
require_once(SRV_ROOT.'model/clients.model.class.php');
$objClients = new mClients();
$outArrClientProducts = $objClients->getProductsByCID($cid);

?>
<script>

 $("form#upload_additional_media_form").submit(function(){
	 var isRequired = true;
	 jQuery("form input").removeClass("error");
	var uploaded_mediafile = jQuery("#uploaded_mediafile").val();
	var fileExtension = ['jpeg', 'jpg', 'png', 'mp4'];
	if ($.inArray( jQuery("#uploaded_mediafile").val().split('.').pop().toLowerCase(), fileExtension) == -1) {
	    //alert("Only '.jpeg','.jpg', '.png' formats are allowed.");
	    jQuery('#frm_error_mediafile').html('Only .jpeg,.jpg, .png, .mp4 formats are allowed.', {type: 'error'}).css("color", "red").show();
		jQuery("#uploaded_mediafile").addClass("error");
		isRequired = false;
	}
	if (uploaded_mediafile==""){
		jQuery('#frm_error_mediafile').html('Please upload valid file', {type: 'error'}).css("color", "red").show();
		jQuery("#uploaded_mediafile").addClass("error");
		isRequired = false;
	}
	
	if($('input[name=genre]:checked').length<=0)
      { 
     jQuery('#frm_error_radio').html('Please select one of radio button', {type: 'error'}).css("color", "red").show();
		jQuery("#genre").addClass("error");
		isRequired = false;
     }
	
	if (isRequired==false){
		$("html").scrollTop(0);
		return false;
	}
	$('#loadingmessage').show();  // show the loading message.
    var formData = new FormData($(this)[0]);
        formData.append('action', "uploadAdditionalMedia");
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
				alert("Added successfully");
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
<form name="upload_additional_media_form" id="upload_additional_media_form" method="post" > 
<div id="frm_error"></div>
   <input type="hidden" value="<?php echo $cid;?>" name="cid" id="cid">
    <input type="hidden" value="<?php echo $pid;?>" name="pid" id="pid">
	 <div class="form-group">
                     <p>
            <input type="radio" name="genre"  id="genre" value="" >Image</input>
     
              <input type="radio" name="genre" id="genre" value=""> Video</input>
	  
              <input type="radio" name="genre" id="genre" value=""> Audio</input>
                      </p>  <div id="frm_error_radio"></div>
           <input type="file" name="uploaded_mediafile" id="uploaded_mediafile"><div id="frm_error_mediafile"></div>
                        </div>
   <div align="center" style="display:none" id="loadingmessage">
 <img src="<?php echo $config['LIVE_URL']; ?>views/images/loading-cafe.gif">
</div>
   <div align="right"> <button type="submit" class="btn btn-success">Submit</button>
  <button type="reset"class="btn btn-danger">Reset</button></div>
</form>