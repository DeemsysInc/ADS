<?php
include '../smcfg.php';
$tid = isset($_REQUEST['tid']) ? $_REQUEST['tid'] : 0;
$cid = isset($_REQUEST['cid']) ? $_REQUEST['cid'] : 0;
$visual_id = isset($_REQUEST['visual_id']) ? $_REQUEST['visual_id'] : 0;
require_once(SRV_ROOT.'model/clients.model.class.php');
$objClients = new mClients();
$outArrClientProducts = $objClients->getProductsByCID($cid);
if(!empty($visual_id))
{
   $outArrGetVisualProductIds = $objClients->getTriggerVisualsByID($visual_id);
}

?>
<script>
$("form#addUrlVisualForm").submit(function(){
      // alert("saveurlvisual");
            var actionType = "saveurlvisual";
			var isRequired = true;
			var cid = jQuery("#cid").val();
			var tid = jQuery("#tid").val();
			var url = jQuery("#url").val();
			//alert(url);
			
			var visual_id = jQuery("#visual_id").val();
			jQuery("form input").removeClass("error");
			/* if(jQuery("#visual_id").val()==0)
	             {
		       if (url==""){
					jQuery('#frm_error_url').html('Please enter valid Url', {type: 'error'}).css("color", "red").show();
					jQuery("#url").addClass("error");
					isRequired = false;
				     }
				if (isRequired==false){
					$("html").scrollTop(0);
					return false;
				}
			} */
			
    var formData = new FormData($(this)[0]);
    formData.append('action', "saveurlvisual");
    $.ajax({
        url: root_url+"includes/ajax/clients.ajax.php",
        type: 'POST',
        data: formData,
        async: false,
		dataType: 'json',
        success: function (data) {
            if(data.msg=='success')
			{
			//alert("data="+data);
			//alert("Added URL successfully");
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
<form class="block-content form"  method="post" id="addUrlVisualForm"  name="addUrlVisualForm" > 
   <input type="hidden" value="<?php echo $cid;?>" name="cid" id="cid">
   <input type="hidden" value="<?php echo $tid;?>" name="tid" id="tid">
   <input type="hidden" value="<?php echo $visual_id;?>" name="visual_id" id="visual_id">
  <div style="display:none;" id="frm_error">
  </div>
  <div align="center">
    <span>URL:</span>
      <input type="text" value="<?php echo isset($outArrGetVisualProductIds[0]['url']) ? $outArrGetVisualProductIds[0]['url'] : ''; ?>" name="url" id="url"><div id="frm_error_url"></div>
      </div><br/>
	   <div align="right">
	  <button  class="btn btn-success">Save</button>&nbsp;
	 <input type="reset" class="btn btn-danger" value="Clear"/></div>
</form>