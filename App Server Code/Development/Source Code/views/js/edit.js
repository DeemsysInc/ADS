//image preview
function showimagepreview(input) {
if (input.files && input.files[0]) {
var filerdr = new FileReader();
filerdr.onload = function(e) {
$('#img_prev').attr('src', e.target.result);
}
filerdr.readAsDataURL(input.files[0]);
}
}

 /*  function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#img_prev').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        } */

$("form#frm_client_product_edit").submit(function(){
	 var p_title = jQuery("#p_title").val();
	 var p_website = jQuery("#p_website").val();
	 var isRequired = true;
	 jQuery("form input").removeClass("error");
    
	/* if(p_website!='')
	{
		var urlregex = new RegExp("^(http:\/\/www.|https:\/\/www.|ftp:\/\/www.|www.){1}([0-9A-Za-z]+\.)");
		if (urlregex.test(p_website)) {
			isRequired = true;
		}
		else
		{
			jQuery('#frm_error_website').html('Please enter Valid Website URL', {type: 'error'}).css("color", "red").show();
			jQuery("#p_website").addClass("error");
			isRequired = false;
		}
	} */
	if(jQuery("#p_image").val()!='')
	{
		var fileExtension = ['jpeg','jpg','png', 'gif'];
		if ($.inArray(jQuery("#p_image").val().split('.').pop().toLowerCase(), fileExtension) == -1) {
				jQuery("#frm_error_image").html("Only '.jpeg','.jpg', '.png', '.gif' formats are allowed.", {type: 'error'}).css("color", "red").show();
				jQuery("#p_image").addClass("error");
				isRequired = false;
		}
	}
	if (p_title==""){
		jQuery('#frm_error_title').html('Please enter title', {type: 'error'}).css("color", "red").show();
		jQuery("#p_title").addClass("error");
		isRequired = false;
	}
	if (isRequired==false){
		$("html").scrollTop(0);
		return false;
	}
	$('#loadingmessage').show();  // show the loading message.

    var formData = new FormData($(this)[0]);
        formData.append('action', "saveClientProduct");
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
				alert("Product details updated successfully");
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
$("form#frm_client_trigger_edit").submit(function(){
	
	var t_title= jQuery("#t_title").val();
    var isRequired = true;
    jQuery("form input").removeClass("error");
   if(jQuery("#t_image").val()!='')
		{
			var fileExtension = ['jpeg','jpg','png', 'gif'];
			if ($.inArray(jQuery("#t_image").val().split('.').pop().toLowerCase(), fileExtension) == -1) {
					jQuery('#frm_error_image').html("Only '.jpeg','.jpg', '.png', '.gif' formats are allowed.", {type: 'error'}).css("color", "red").show();
				jQuery("#t_image").addClass("error");
				isRequired = false;
		}
	}
	if (t_title==""){
		jQuery('#frm_error_title').html('Please enter title', {type: 'error'}).css("color", "red").show();
		jQuery("#t_title").addClass("error");
		isRequired = false;
	}
    if (isRequired==false){
		$("html").scrollTop(0);
		return false;
	}
	$('#loadingmessage').show();  // show the loading message.

   
    var formData = new FormData($(this)[0]);
        formData.append('action', "updateClientTrigger");
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
				alert("Trigger details updated successfully");
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

function updateClientLogo(){
	
	//var sData = jQuery("#frm_client_product").serialize();
	var actionType = "updateClientLogo";
	
	
	var c_id = jQuery("#c_id").val();
	var c_exist_logo=jQuery("#c_exist_logo").val();
	var c_logo = $('input[type=file]').val().replace(/C:\\fakepath\\/i, '')
	if(c_logo=='')
	{
		c_logo=c_exist_logo;
	}
		

	jQuery.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType,c_logo:c_logo,c_id:c_id},
		function(data){
			
			if(data.msg=='success')
			{
				
				window.location=data.redirect;
			} else
			{
				jQuery('#frm_error').removeBlockMessages().blockMessage('Error Occured: Please contact us', {type: 'error'});
				return false;
			}
		}, 'json'
	);	
}

$("form#frm_client_edit").submit(function(){
	var isRequired = true;
	jQuery("form input").removeClass("error");
	var c_name = jQuery("#c_name").val();
	var c_website= jQuery("#c_website").val();
	if(c_website!='')
	{
		var urlregex = new RegExp("^(http:\/\/www.|https:\/\/www.|ftp:\/\/www.|www.){1}([0-9A-Za-z]+\.)");
		if (urlregex.test(c_website)) {
			isRequired = true;
		}
		else
		{
			jQuery('#frm_error_url').html('Please enter Valid Website URL', {type: 'error'}).css("color", "red").show();
			jQuery("#c_website").addClass("error");
			isRequired = false;
		}
	}
	if(jQuery("#c_logo").val()!='')
	{
		var fileExtension = ['jpeg','jpg','png', 'gif'];
		if ($.inArray(jQuery("#c_logo").val().split('.').pop().toLowerCase(), fileExtension) == -1) {
				jQuery('#frm_error_logo').html("Only '.jpeg','.jpg', '.png', '.gif' formats are allowed.", {type: 'error'}).css("color", "red").show();
				jQuery("#c_logo").addClass("error");
				isRequired = false;
		}
	}
	if(jQuery("#c_bgimage").val()!='')
	{
		var fileExtension = ['jpeg','jpg','png', 'gif'];
		if ($.inArray(jQuery("#c_bgimage").val().split('.').pop().toLowerCase(), fileExtension) == -1) {
				jQuery('#frm_error_image').html("Only '.jpeg','.jpg', '.png', '.gif' formats are allowed.", {type: 'error'}).css("color", "red").show();
				jQuery("#c_bgimage").addClass("error");
				isRequired = false;
		}
	}
    if (isRequired==false){
		$("html").scrollTop(0);
		return false;
	}
	$('#loadingmessage').show();  // show the loading message.
    var formData = new FormData($(this)[0]);
        formData.append('action', "updateClientDetails");
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
				alert("Client details updated successfully");
				//  jQuery('#notifications').show();
				 // $('#notifications').delay(1000).fadeOut();	
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



function deleteClient(cid){
	var r=confirm("Are you sure want to delete?");
if (r==true)
  {
	var actionType = "deleteClient";
	$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, cid:cid},
		function(data){
			if(data.msg=='success')
			{
				window.location=data.redirect;
				return true;
			} else
			{
				alert('Client not deleted');
				return false;
			}
		}, 'json'
	);	

	return false;
  }
}




$("form#frm_client_add").submit(function(){
//alert("clients........");
    var isRequired = true;
	jQuery("form input").removeClass("error");
	var c_name = jQuery("#c_name").val();
	var c_website= jQuery("#c_website").val();
	//var bcid=jQuery("#bcid").val();
	 var hdn_bcid=jQuery("#hdn_bcid").val();
	// var c_is_demo=jQuery("#c_is_demo").val();
	
	 if (jQuery("#c_name").val() == "" ) 
	 {
	  jQuery("#frm_error_name").html("Please enter Client Name").css("color", "red").show();
       jQuery("#c_name").addClass("error");   
         isRequired = false;   
     }
	/*  if (jQuery("#c_prefix").val() == "" ) 
	 {
	  jQuery("#frm_error8").html("Please enter prefix").css("color", "red").show();
       jQuery("#c_prefix").addClass("error");   
         isRequired = false;   
     } */
		 
 /*  if(jQuery("#c_website").val() == ""){
		jQuery('#frm_error1').html('Please enter Website').css("color", "red").show();
		jQuery("#c_website").addClass("error");
		isRequired = false;
	} */
	if(c_website!='')
	{
		var urlregex = new RegExp("^(http:\/\/www.|https:\/\/www.|ftp:\/\/www.|www.){1}([0-9A-Za-z]+\.)");
		if (urlregex.test(c_website)) {
			isRequired = true;
		}
		else
		{
			jQuery('#frm_error_website').html('Please enter valid Website').css("color", "red").show();
			jQuery("#c_website").addClass("error");
			isRequired = false;
		}
	}
	/*  if(jQuery("#bcid").val() == ""){
		jQuery('#frm_error4').html('Please enter Background Color').css("color", "red").show();
		jQuery("#bcid").addClass("error");
		isRequired = false;
	}
	 if(jQuery("#lcid").val() == ""){
		jQuery('#frm_error5').html('Please enter Light Color').css("color", "red").show();
		jQuery("lbcid").addClass("error");
		isRequired = false;
	}
	
	 if(jQuery("#dcid").val() == ""){
		jQuery('#frm_error6').html('Please enter Dark Color').css("color", "red").show();
		jQuery("#dcid").addClass("error");
		isRequired = false;
	}
	
	 if (jQuery("#c_is_demo").val == ""){
   jQuery("#frm_error10").html("You must select an option").css("color", "red").show();
   return false;
         }
	 if (jQuery("#c_client_vertical").val == ""){
   jQuery("#frm_error11").html("You must select client vertical").css("color", "red").show();
   return false;
         }
	 if(jQuery("#store_notify_msg").val() == ""){
		jQuery('#frm_error7').html('Please enter store notify').css("color", "red").show();
		jQuery("#store_notify_msg").addClass("error");
		isRequired = false;
	} */
	if(jQuery("#c_logo").val()!='')
	{
		var fileExtension = ['jpeg','jpg','png', 'gif'];
		if ($.inArray(jQuery("#c_logo").val().split('.').pop().toLowerCase(), fileExtension) == -1) {
				jQuery('#frm_error_logo').html("Only '.jpeg','.jpg', '.png', '.gif' formats are allowed.", {type: 'error'}).css("color", "red").show();
				jQuery("#c_logo").addClass("error");
				isRequired = false;
		}
	}
	
	/* if(jQuery("#c_logo").val()=='')
	 { 
	jQuery('#frm_error2').html(" Please upload image").css("color", "red").show();
     }
	 */
	if(jQuery("#c_bgimage").val()!='')
	{
		var fileExtension = ['jpeg','jpg','png', 'gif'];
		if ($.inArray(jQuery("#c_bgimage").val().split('.').pop().toLowerCase(), fileExtension) == -1) {
				jQuery('#frm_error_image').html("Only '.jpeg','.jpg', '.png', '.gif' formats are allowed.", {type: 'error'}).css("color", "red").show();
				jQuery("#c_bgimage").addClass("error");
				isRequired = false;
		}
	
	}
	
	/* if(jQuery("#c_bgimage").val()=='')
	 { 
	jQuery('#frm_error3').html(" Please upload  background image").css("color", "red").show();
     } */
	
	
	if (isRequired==false){
		$("html").scrollTop(0);
		return false;
	}
	$('#loadingmessage').show();  // show the loading message.
    var formData = new FormData($(this)[0]);
        formData.append('action', "CreateClient");
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
				alert("Client details created successfully");
				window.location=data.redirect;
			} else
			{
				jQuery('#frm_error').html('Error Occured: Please contact us').css("color", "red").show();
				return false;
			}
        },
        cache: false,
        contentType: false,
        processData: false
    });

    return false;
});
function deleteProduct(cid,pid){
	var r=confirm("Are you sure want to delete?");
if (r==true)
  {
	var actionType = "deleteProduct";
	$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, cid:cid,pid:pid},
		function(data){
			if(data.msg=='success')
			{
				alert("Deleted");
				window.location=data.redirect;
				return true;
			} else
			{
				alert('Client not deleted');
				return false;
			}
		}, 'json'
	);	

	return false;
  }
}
function deleteTrigger(cid,tid){
	var r=confirm("Are you sure want to delete?");
if (r==true)
  {
	var actionType = "deleteTrigger";
	$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, cid:cid,tid:tid},
		function(data){
			if(data.msg=='success')
			{
				window.location=data.redirect;
				return true;
			} else
			{
				alert('Trigger not deleted');
				return false;
			}
		}, 'json'
	);	

	return false;
  }
}
function deleteTriggerVisual(visual_id,cid,tid){
	

var r=confirm("Are you sure want to delete?");
if (r==true)
  {
	  var actionType = "deleteTriggerVisual";
	$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, visual_id:visual_id,cid:cid,tid:tid},
		function(data){
			if(data.msg=='success')
			{
				window.location=data.redirect;
				return true;
			} else
			{
				alert('Visual not deleted');
				return false;
			}
		}, 'json'
	);	

	return false;
 
  }
}
function deleteAdditionalMedia(cid,pid,additional_id){
	var r=confirm("Are you sure want to delete?");
if (r==true)
  {
	var actionType = "deleteAdditionalMedia";
	$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, cid:cid,pid:pid,additional_id:additional_id},
		function(data){
			if(data.msg=='success')
			{
				window.location=data.redirect;
				return true;
			} else
			{
				alert('Trigger not deleted');
				return false;
			}
		}, 'json'
	);	

	return false;
  }
}



function modalEditAdditionalMedia(cid,pid,additional_id)
		{
			$.modal({
				url: root_url+'dynamic_content/client_products_edit_additional_media.tpl.php?pid='+pid+'&cid='+cid+'&additional_id='+additional_id,
				title: 'Edit Additonal Media',
				maxWidth: 600,
				maxHeight: 300
				//buttons: {
				//	'Upload': function(win) { openAdditionalMedia(); },
				//	'Close': function(win) { win.closeModal(); }
				//}
			});
		}
$("form#frm_client_product_add").submit(function(){
     var p_title = jQuery("#p_title").val();
	 var p_website = jQuery("#p_website").val();
	 var isRequired = true;
	 jQuery("form input").removeClass("error");
    
	/* if(p_website!='')
	{
		var urlregex = new RegExp("^(http:\/\/www.|https:\/\/www.|ftp:\/\/www.|www.){1}([0-9A-Za-z]+\.)");
		if (urlregex.test(p_website)) {
			isRequired = true;
		}
		else
		{
			jQuery('#frm_error_url').html('Please enter Valid Website URL').css("color", "red").show();
			jQuery("#p_website").addClass("error");
			isRequired = false;
		}
	} */
	if(jQuery("#p_image").val()!='')
	{
		var fileExtension = ['jpeg','jpg','png', 'gif'];
		if ($.inArray(jQuery("#p_image").val().split('.').pop().toLowerCase(), fileExtension) == -1) {
				jQuery('#frm_error_image').html("Only '.jpeg','.jpg', '.png', '.gif' formats are allowed.", {type: 'error'}).css("color", "red").show();
				jQuery("#p_image").addClass("error");
				isRequired = false;
		}
	}
	
	if (p_title==""){
		jQuery('#frm_error_title').html('Please enter title', {type: 'error'}).css("color", "red").show();
		jQuery("#p_title").addClass("error");
		isRequired = false;
	}
	if (isRequired==false){
		$("html").scrollTop(0);
		return false;
	}
   
   $('#loadingmessage').show();  // show the loading message.
	
    var formData = new FormData($(this)[0]);
	  
        formData.append('action', "CreateClientProduct");
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
				alert("Product created successfully");
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
$("form#frm_client_trigger_add").submit(function(){
	 var isRequired = true;
	 jQuery("form input").removeClass("error");
	var t_image = jQuery("#t_image").val();
	var t_title= jQuery("#t_title").val();
	var t_height= jQuery("#t_height").val();
	var t_width = jQuery("#t_width").val();
	var t_instruction = jQuery("#t_instruction").val();
	
	if(jQuery("#t_image").val()!='')
	{
		var fileExtension = ['jpeg','jpg','png', 'gif'];
		if ($.inArray(jQuery("#t_image").val().split('.').pop().toLowerCase(), fileExtension) == -1) {
				jQuery('#frm_error_image').html("Only '.jpeg','.jpg', '.png', '.gif' formats are allowed.", {type: 'error'}).css("color", "red").show();
				jQuery("#t_image").addClass("error");
				isRequired = false;
		}
	}
	if (t_title==""){
		jQuery('#frm_error_title').html('Please enter title', {type: 'error'}).css("color", "red").show();
		jQuery("#t_title").addClass("error");
		isRequired = false;
	}else if (t_height==""){
		jQuery('#frm_error_height').html('Please enter height', {type: 'error'}).css("color", "red").show();
		jQuery("#t_height").addClass("error");
		isRequired = false;
	}else if (t_width==""){
		jQuery('#frm_error_width').html('Please eneter width', {type: 'error'}).css("color", "red").show();
		jQuery("#t_width").addClass("error");
		isRequired = false;
	}
	if (isRequired==false){
		$("html").scrollTop(0);
		return false;
	}
	$('#loadingmessage').show();  // show the loading message.

    var formData = new FormData($(this)[0]);
        formData.append('action', "addClientTrigger");
    $.ajax({
        url: root_url+"includes/ajax/clients.ajax.php",
        type: 'POST',
        data: formData,
        async: false,
		dataType: 'json',
        success: function (data) {
			 $('#loadingmessage').hide(); // hide the loading message
            //alert(data)
			if(data.msg=='success')
			{
				alert("trigger details added successfully");
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
		function modalViewOffers(pid,cid)
		{
			$.modal({
				url: root_url+'dynamic_content/client_products_offered.tpl.php?pid='+pid+'&cid='+cid,
				title: 'View Offers',
				width: 600,
				height: 300,
				buttons: {
					'Update': function(win) { saveProductOffer(); },
					'Close': function(win) { win.closeModal(); }
				}
			});
		}
		function saveProductOffer(){
			
			var actionType = "saveProductOffer";
			var rd_products = $('input:radio[name=rd_products]:checked').val();
			var cid= jQuery("#cid").val();
			var pid=jQuery("#pid").val();
			
			//jQuery("form input").removeClass("error");
			
			jQuery.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, cid:cid,pid:pid,rd_products:rd_products},
				function(data){
					if(data.msg=='success')
					{
						//alert("Added related products successfully");
						window.location=data.redirect;
					} else
					{
						jQuery('#frm_error').removeBlockMessages().blockMessage('Error Occured: Please contact us', {type: 'error'});
						return false;
					}
				}, 'json'
			);	
		}
		

$("form#Edit_additional_media_form").submit(function(){
	 var isRequired = true;
	 jQuery("form input").removeClass("error");
	var uploaded_mediafile_edit = jQuery("#uploaded_mediafile_edit").val();
	//alert(uploaded_mediafile_edit);
	var fileExtension = ['jpeg', 'jpg', 'png', 'mp4'];
	
	if ($.inArray( jQuery("#uploaded_mediafile_edit").val().split('.').pop().toLowerCase(), fileExtension) == -1) {
	    //alert("Only '.jpeg','.jpg', '.png' formats are allowed.");
	    jQuery('#frm_error_mediafile_edit').html('Only .jpeg,.jpg, .png, .mp4 formats are allowed.', {type: 'error'}).css("color", "red").show();
		jQuery("#uploaded_mediafile_edit").addClass("error");
		isRequired = false;
	}
	if (uploaded_mediafile_edit==""){
		jQuery('#frm_error_mediafile_edit').html('Please upload valid file', {type: 'error'}).css("color", "red").show();
		jQuery("#uploaded_mediafile_edit").addClass("error");
		isRequired = false;
	}
	
	if($('input[name=genre]:checked').length<=0)
   { 
   jQuery('#frm_error_radio_edit').html('Please select one of radio button', {type: 'error'}).css("color", "red").show();
		jQuery("#genre").addClass("error");
		isRequired = false;
   }
  
	if (isRequired==false){
		$("html").scrollTop(0);
		return false;
	}
	$('#loadingmessage').show();  // show the loading message.
    var formData = new FormData($(this)[0]);
        formData.append('action', "UpdateAdditionalMedia");
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
				alert("Updated successfully");
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
			
		function modalAddButton(cid,tid,visual_id)
		{
			$.modal({
				url: root_url+'dynamic_content/client_visual_addbutton.tpl.php?tid='+tid+'&cid='+cid+'&visual_id='+visual_id,
				title: 'Add Button',
				width: 800,
				height: 500,
				buttons: {
					'Save': function(win) { addBtnVisual(); },
					'Close': function(win) { win.closeModal(); }
				}
			});
		}
		function addBtnVisual(){
			//alert("saveUserProfile");
			var isRequired = true;
	        jQuery("form input").removeClass("error");
	
			var actionType = "savebtnvisual";
			var x = jQuery("#x").val();
			var y= jQuery("#y").val();
			var cid= jQuery("#cid").val();
			var tid = jQuery("#tid").val();
			var visual_id = jQuery("#visual_id").val();
			var product_id=$("input:radio[name=add_product]:checked").val();
			if(product_id=='')
			{
				jQuery('#frm_error').removeBlockMessages().blockMessage('Please Select product', {type: 'error'});
				return false;
			}
			if(visual_id=='')
			{
				var isRequired = true;
				jQuery("form input").removeClass("error");
				if (x==""){
					jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter X value', {type: 'error'});
					jQuery("#x").addClass("error");
					isRequired = false;
				}else if (y==""){
					jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter Y value', {type: 'error'});
					jQuery("#y").addClass("error");
					isRequired = false;
				}
				
			}
			if (isRequired==false){
					$("html").scrollTop(0);
					return false;
			}
			jQuery.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, x:x,y:y,cid:cid,tid:tid,pid:product_id,visual_id:visual_id},
				function(data){
					if(data.msg=='success')
					{
						alert("Added Button successfully");
						window.location=data.redirect;
					} else
					{
						jQuery('#frm_error').removeBlockMessages().blockMessage('Error Occured: Please contact us', {type: 'error'});
						return false;
					}
				}, 'json'
			);	
		}
		function modalAdd3DModel(cid,tid,visual_id)
		{
			$.modal({
				url: root_url+'dynamic_content/client_visual_add3dmodel.tpl.php?tid='+tid+'&cid='+cid+'&visual_id='+visual_id,
				title: '3D Model',
				width: 700,
				height: 400,
				buttons: {
					'Save': function(win) { add3DModelVisual(); },
					'Close': function(win) { win.closeModal(); }
				}
			});
		}
		function add3DModelVisual(){
			//alert("saveUserProfile");
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
					jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter X value', {type: 'error'});
					jQuery("#x").addClass("error");
					isRequired = false;
				}else if (y==""){
					jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter Y value', {type: 'error'});
					jQuery("#y").addClass("error");
					isRequired = false;
				}else if (scale==""){
					jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter Scale value', {type: 'error'});
					jQuery("#scale").addClass("error");
					isRequired = false;
				}else if (x_rot==""){
					jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter X-Rotation', {type: 'error'});
					jQuery("#x_rot").addClass("error");
					isRequired = false;
				}else if (y_rot==""){
					jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter Y-Rotation', {type: 'error'});
					jQuery("#y_rot").addClass("error");
					isRequired = false;
				}else if (z_rot==""){
					jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter Z-Rotation', {type: 'error'});
					jQuery("#z_rot").addClass("error");
					isRequired = false;
				}
				
				if (isRequired==false){
					$("html").scrollTop(0);
					return false;
				}
			}
			
   		  
	      
		 
		 if ($('#animateRecog').is(':checked')) {
    var animate_recog= 1;
} else {
   var animate_recog= 0;
} 
         
			

			jQuery.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, x:x,y:y,cid:cid,tid:tid,scale:scale,animate_recog:animate_recog,x_rot:x_rot,y_rot:y_rot,z_rot:z_rot,visual_id:visual_id,rd_products:rd_products},
				function(data){
					if(data.msg=='success')
					{
						//alert("Added 3D Model successfully");
						window.location=data.redirect;
						// win.closeModal();
					
						 						
					 
					} else
					{
						jQuery('#frm_error').removeBlockMessages().blockMessage('Error Occured: Please contact us', {type: 'error'});
						return false;
					}
				}, 'json'
			);	
		}
		function modalAddUrl(cid,tid,visual_id)
		{
			$.modal({
				url: root_url+'dynamic_content/client_visual_addurl.tpl.php?tid='+tid+'&cid='+cid+'&visual_id='+visual_id,
				title: 'Add Url',
				width: 600,
				height: 300,
				buttons: {
					'Save': function(win) { addUrlVisual(); },
					'Close': function(win) { win.closeModal(); }
				}
			});
		}
		function addUrlVisual(){
			//alert("saveUserProfile");
			var actionType = "saveurlvisual";
			var isRequired = true;
			var cid = jQuery("#cid").val();
			var tid = jQuery("#tid").val();
			var url = jQuery("#url").val();
			var visual_id = jQuery("#visual_id").val();
			if(visual_id=="")
			{
				jQuery("form input").removeClass("error");
				if (url==""){
					jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter valid Url', {type: 'error'});
					jQuery("#url").addClass("error");
					isRequired = false;
				}
				if (isRequired==false){
					$("html").scrollTop(0);
					return false;
				}
			}
			jQuery.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, cid:cid,tid:tid,url:url,visual_id:visual_id},
				function(data){
					if(data.msg=='success')
					{
						alert("Added URL successfully");
						window.location=data.redirect;
					} else
					{
						jQuery('#frm_error').removeBlockMessages().blockMessage('Error Occured: Please contact us', {type: 'error'});
						return false;
					}
				}, 'json'
			);	
		}
		function modalAddVideo(cid,tid,visual_id)
		{
			$.modal({
				url: root_url+'dynamic_content/client_visual_addvideo.tpl.php?tid='+tid+'&cid='+cid+'&visual_id='+visual_id,
				title: 'Video Details',
				width: 700,
				height: 400,
				/*buttons: {
					//'Save': function(win) { },
					'Close': function(win) { win.closeModal(); }
				}*/
			});
		}
		function modalUpdate3DModel(cid,tid,visual_id,model_id)
		{
			$.modal({
				url: root_url+'dynamic_content/client_models_edit.tpl.php?tid='+tid+'&cid='+cid+'&visual_id='+visual_id+'&model_id='+model_id,
				title: '3D Model Details',
				width: 700,
				height: 400,
				/*buttons: {
					//'Save': function(win) { },
					'Close': function(win) { win.closeModal(); }
				}*/
			});
		}
		function modalAddNew3DModel(cid,tid,visual_id)
		{
			$.modal({
				url: root_url+'dynamic_content/client_models_add.tpl.php?tid='+tid+'&cid='+cid+'&visual_id='+visual_id,
				title: '3D Model Details',
				width: 700,
				height: 400,
				/*buttons: {
					//'Save': function(win) { },
					'Close': function(win) { win.closeModal(); }
				}*/
			});
		}
		function delete3DModel(cid,tid,visual_id,model_id){
			var r=confirm("Are you sure want to delete?");
		if (r==true)
		  {
			var actionType = "delete3DModel";
			$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, cid:cid, tid:tid, visual_id:visual_id, model_id:model_id},
				function(data){
					if(data.msg=='success')
					{
						window.location=data.redirect;
						return true;
					} else
					{
						alert('Deleted');
						return false;
					}
				}, 'json'
			);	
		
			return false;
		  }
		}
$("form#frm_client_store_edit").submit(function(){
	 var storeName = jQuery("#s_name").val();
	 var isRequired = true;
	 jQuery("form input").removeClass("error");
    
    if (storeName==""){
		jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter store name', {type: 'error'});
		jQuery("#s_name").addClass("error");
		isRequired = false;
	}
	if (isRequired==false){
		$("html").scrollTop(0);
		return false;
	}
	$('#loadingmessage').show();  // show the loading message.

    var formData = new FormData($(this)[0]);
        formData.append('action', "UpdateClientStores");
    $.ajax({
        url: root_url+"includes/ajax/clients.ajax.php",
        type: 'POST',
        data: formData,
        async: false,
		dataType: 'json',
        success: function (data) {
			 $('#loadingmessage').hide(); // hide the loading message
            //alert(data)
			if(data.msg=='success')
			{
				alert("Store details updated successfully");
				window.location=data.redirect;
			} else
			{
				jQuery('#frm_error').removeBlockMessages().blockMessage('Error Occured: Please contact us', {type: 'error'});
				return false;
			}
        },
        cache: false,
        contentType: false,
        processData: false
    });

    return false;
});	
$("form#frm_client_store_add").submit(function(){
	 var storeName = jQuery("#s_name").val();
	 var isRequired = true;
	 jQuery("form input").removeClass("error");
    
    if (storeName==""){
		jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter store name', {type: 'error'});
		jQuery("#s_name").addClass("error");
		isRequired = false;
	}
	if (isRequired==false){
		$("html").scrollTop(0);
		return false;
	}
	$('#loadingmessage').show();  // show the loading message.

    var formData = new FormData($(this)[0]);
        formData.append('action', "saveClientStores");
    $.ajax({
        url: root_url+"includes/ajax/clients.ajax.php",
        type: 'POST',
        data: formData,
        async: false,
		dataType: 'json',
        success: function (data) {
			 $('#loadingmessage').hide(); // hide the loading message
            //alert(data)
			if(data.msg=='success')
			{
				alert("Store details created successfully");
				window.location=data.redirect;
			} else
			{
				jQuery('#frm_error').removeBlockMessages().blockMessage('Error Occured: Please contact us', {type: 'error'});
				return false;
			}
        },
        cache: false,
        contentType: false,
        processData: false
    });

    return false;
});	
function deleteStore(cid,sid){
	var r=confirm("Are you sure want to delete?");
if (r==true)
  {
	var actionType = "deleteStore";
	$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, cid:cid,sid:sid},
		function(data){
			if(data.msg=='success')
			{
				window.location=data.redirect;
				return true;
			} else
			{
				alert('Store not deleted');
				return false;
			}
		}, 'json'
	);	

	return false;
  }
}
function modalRelatedStoreOffers(storeId,cid)
	{
		$.modal({
			url: root_url+'dynamic_content/client_stores_related_offers.tpl.php?storeId='+storeId+'&cid='+cid,
			title: 'Related Offers',
			width: 600,
			height: 300,
			buttons: {
				'Update': function(win) { updateStoreRelatedOffers(); },
				'Close': function(win) { win.closeModal(); }
			}
		});
	}
function updateStoreRelatedOffers(){

	
	var actionType = "updateStoreRelatedOffers";
	var chk_offers = $("input[name=chk_offers]:checked").map(function () {return this.value;}).get().join(",");
	var cid= jQuery("#cid").val();
	var storeId=jQuery("#storeId").val();
	
	jQuery.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, cid:cid,storeId:storeId,related_offers:chk_offers},
		function(data){
			if(data.msg=='success')
			{
				//alert("Added related products successfully");
				window.location=data.redirect;
			} else
			{
				jQuery('#frm_error').removeBlockMessages().blockMessage('Error Occured: Please contact us', {type: 'error'});
				return false;
			}
		}, 'json'
	);	
}
 
// function getUserTrackingDashboard() {
// 	//alert(arr);
//   //google.load("visualization", "1", {packages:["corechart"]});
// 	jQuery('#loader').show();
//  		var actionType = "getUserTrackingDashboard";
//  		var start_date =jQuery("#from").val();
//  		var end_date =jQuery("#to").val();
// //google.load("visualization", "1", {packages:["corechart"]});
//  		//alert(root_url+"includes/ajax/clients.ajax.php");
// 		//jQuery("#displayUserTrackingDashnboard").load(root_url+"includes/ajax/clients.ajax.php?action="+actionType+"&start_date="+start_date+"&end_date="+end_date);
//  		jQuery.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType,start_date:start_date,end_date:end_date },
//  			function(data) {
//  			//	alert(data);
//  				jQuery("#displayUserTrackingDashnboard").html(data);
// google.load("visualization", "1", {packages:["corechart"]});
// 				google.setOnLoadCallback(drawChartProductsTrackNew);
//  				alert("new");
//  				jQuery('#loader').hide();
//  			}
//  		);
//  }
 
//  function drawChartProductsTrackNew(){
// 	 alert("manohar");
//  }





// var chart_data;
// var actionType = "getUserTrackingDashboard";
// var start_date =jQuery("#from").val();
// var end_date =jQuery("#to").val();
// google.load("visualization", "1", {packages:["corechart"]});
// google.setOnLoadCallback(load_page_data);

// function load_page_data(){
//     $.ajax({
//         url: root_url+"includes/ajax/clients.ajax.php",
//         data: {'start_date':start_date,'end_date':end_date,'action':actionType},
//         async: false,
//         success: function(data){
//             if(data){
//             	alert(data);
//                 chart_data = $.parseJSON(data);
//                 drawChart(chart_data, "My Chart", "Data");
//             }
//         },
//     });
// }

// function drawChart(chart_data, chart1_main_title, chart1_vaxis_title) {
   

//  var data = new google.visualization.DataTable();

//     <!-- Create the data table -->
//     data.addColumn('string', 'Day');
//     data.addColumn('number', 'Product Views');

//     data.addRows([

//      <?php
//      $arrDataForGraph=json_decode(chart_data,true);
//      for($i=0;$i<count($arrDataForGraph);$i++)
//      {
//          echo '["'.date('M j,Y',strtotime($arrDataForGraph[$i]['dates'])).'", '.$arrDataForGraph[$i]['productids'].'],';
//      }
//       ?>
//     ]);
//       var chart = new google.visualization.AreaChart(document.getElementById('chart1_div'));
//     chart.draw(data, {width: 550, height: 250, title: '',
//                       colors:['#058dc7','#e6f4fa'],
//                       areaOpacity: 0.1,
//                       hAxis: {textPosition: 'bottom', showTextEvery: 20, slantedText: false, textStyle: { color: '#058dc7', fontSize: 10 } },
//                       pointSize: 7,
//                       legend: 'right'
//     });


// }

    

	$( "#c_country" ).change(function() {
    	var country = $( "#c_country" ).val();
        $.ajax({ url: root_url+"includes/ajax/clients.ajax.php",
         data: {action : 'getStatesByCountry',country_code: country},
         type: 'get',
         async: false,
         success:
         function(msg) {
         	$('#c_state').find('option').remove().end();
			
         	var state = jQuery.parseJSON(msg);
         	for(var i = 0 ; i < state.length ; i++)
         	{
         		$('#c_state').append('<option value="'+state[i].state_subdivision_code+'">'+state[i].state_subdivision_name+'</option>');
         	}
       	 }
        });
		$('#c_state').append('<option value="0">Select State/Province</option>');
	});
	/* $( "#c_state" ).change(function() {
    	var state = $( "#c_state" ).val();
    	$("#c_state option[value='0']").remove();

       	$.ajax({ url: root_url+"includes/ajax/clients.ajax.php",
         data: {action : 'getCountryByState',state_code: state},
         type: 'get',
         async: false,
         success:
         function(msg) {
         	$('#c_country').find('option').remove().end();
         	var country = jQuery.parseJSON(msg);
         	for(var i = 0 ; i < country.length ; i++)
         	{
         		$('#c_country').append('<option value="'+country[i].country_code_char2+'">'+country[i].country_name+'</option>');
         	}
       	 }
        });
    	$('#c_country').append('<option value="0">Select Country</option>');

	}); */
	
	

