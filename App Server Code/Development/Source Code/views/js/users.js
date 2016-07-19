function addUsers(){
	var actionType = "addUsers";
	var pFname = $("#fname").val();
	var pLname = $("#lname").val();
	var pUname = $("#uname").val();
	var pPassw = $("#passw").val();
	var pEmail = $("#email").val();
	var pPhone = $("#phone").val();
	$.post(root_url+"includes/ajax/users.ajax.php", { action: actionType, fname:pFname, lname:pLname, uname:pUname, passw:pPassw, email:pEmail, phone:pPhone},
		function(data){
			if(data.msg=='success')
			{
				window.location=data.redirect;
				return true;
			} else
			{
				$(".n_error").show();
				$("#fname").focus();
				return false;
			}
		}, 'json'
	);	
	return false;
}
function editUser(pUid){
	//alert('edit');
	window.location=root_url+"users/edit/"+pUid;
}
function deleteUser(pUserId){
	var actionType = "deleteUser";
	$.post(root_url+"includes/ajax/users.ajax.php", { action: actionType, uid:pUserId},
		function(data){
			if(data.msg=='success')
			{
				window.location=data.redirect;
				return true;
			} else
			{
				alert('User not deleted');
				return false;
			}
		}, 'json'
	);	

	return false;

}
function editSaveUser(pUserId){
	$(".n_error").hide();
	var actionType = "editSaveUser";
	var pFname = $("#fname").val();
	var pLname = $("#lname").val();
	var pPassw = $("#passw").val();
	var pPasswConfirm = $("#passw_confirm").val();
	var pEmail = $("#email").val();
	var pPhone = $("#phone").val();
	var isValid = true;
	if (!isEmtpy(pFname)){
		$("#err_fname").show();
		$("#err_fname").html('Please enter valid First name');
		isValid = false;
	}
	if (!isEmtpy(pLname)){
		$("#err_lname").show();
		$("#err_lname").html('Please enter valid Last name');
		isValid = false;
	}
	if (!isEmtpy(pEmail) || !validateEmail(pEmail)){
		$("#err_email").show();
		$("#err_email").html('Please enter valid Email Address');
		isValid = false;
	}
	if (pPassw!=""){
		if ((pPasswConfirm=="") || (pPassw!=pPasswConfirm)){
			//$("#err_passw").show();
			$("#err_passw_confirm").show();
			$("#err_passw_confirm").html("Passwords doesn't match");
			$("#passw").val('');
			$("#passw_confirm").val('');
			isValid = false;
		}
		
		var passed = validatePassword(pPassw, {
			length:   [6, Infinity],
			lower:    1,
			upper:    1,
			numeric:  1,
			special:  1,
			badWords: ["password", "vziom"],
			badSequenceLength: 4
		});
		if (passed==false || passed!="success"){
			$("#err_passw").show();
			$("#err_passw").html(passed);
			$("#passw").val('');
			$("#passw_confirm").val('');
			isValid = false;
		}
	}
	if (!toValidateUSPhone(pPhone)){
		$("#err_phone").show();
		$("#err_phone").html('Please enter valid Phone number');
		isValid = false;
	}
	if (isValid==false){
		return false;
	}
	$.post(root_url+"includes/ajax/users.ajax.php", { action: actionType, uid:pUserId, fname:pFname, lname:pLname, passw:pPassw, email:pEmail, phone:pPhone},
		function(data){
			if(data.msg=='success')
			{
				window.location=data.redirect;
				return true;
			} else
			{
				alert('User not modified');
				return false;
			}
		}, 'json'
	);	

	return false;

}
function saveRequisition(){
	//alert("requisition");
	var sData = jQuery("#frm_requisition").serialize();
	var actionType = "saveRequisition";
	var isRequired = true;
	
	var business_name = jQuery("#business_name").val();
	var business_phone = jQuery("#business_phone").val();
	var business_owner_name = jQuery("#business_owner_name").val();
	var business_address = jQuery("#business_address").val();
	var business_city = jQuery("#business_city").val();
	var business_state = jQuery("#business_state").val();
	var business_zip = jQuery("#business_zip").val();
	var off_email_id = jQuery("#off_email_id").val();
	jQuery("form input").removeClass("error");
	if (business_name==""){
		jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter valid Business Name', {type: 'error'});
		jQuery("#business_name").addClass("error");
		isRequired = false;
	}else if (business_phone==""){
		jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter valid Business Phone', {type: 'error'});
		jQuery("#business_phone").addClass("error");
		isRequired = false;
	}else if (business_owner_name==""){
		jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter valid Business Owner Name', {type: 'error'});
		jQuery("#business_owner_name").addClass("error");
		isRequired = false;
	}else if (business_address==""){
		jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter valid Business Address', {type: 'error'});
		jQuery("#business_address").addClass("error");
		isRequired = false;
	}else if (business_city==""){
		jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter valid Business City', {type: 'error'});
		jQuery("#business_city").addClass("error");
		isRequired = false;
	}else if (business_state==""){
		jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter valid Business State', {type: 'error'});
		jQuery("#business_state").addClass("error");
		isRequired = false;
	}else if (business_zip==""){
		jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter valid Business Zip', {type: 'error'});
		jQuery("#business_zip").addClass("error");
		isRequired = false;
	}else if (off_email_id==""){
		jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter valid Official Email Address', {type: 'error'});
		jQuery("#off_email_id").addClass("error");
		isRequired = false;
	}
	if (isRequired==false){
		$("html").scrollTop(0);
		return false;
	}
	jQuery.post(root_url+"includes/ajax/users.ajax.php", { action: actionType, dataVals:sData},
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
function saveUserProfile(){
	//alert("saveUserProfile");
	var sData = jQuery("#frm_users_profile").serialize();
	var actionType = "saveUserProfile";
	var isRequired = true;
	
	var u_first_name = jQuery("#u_first_name").val();
	var u_last_name = jQuery("#u_last_name").val();
	var u_email = jQuery("#u_email").val();
	var u_address_1 = jQuery("#u_address_1").val();
	var u_city = jQuery("#u_city").val();
	var u_state = jQuery("#u_state").val();
	var u_country = jQuery("#u_country").val();
	var u_zip = jQuery("#u_zip").val();
	jQuery("form input").removeClass("error");
	if (u_last_name==""){
		jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter valid First Name', {type: 'error'});
		jQuery("#u_last_name").addClass("error");
		isRequired = false;
	}else if (u_last_name==""){
		jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter valid Last Name', {type: 'error'});
		jQuery("#u_last_name").addClass("error");
		isRequired = false;
	}else if (u_email==""){
		jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter valid Email Address', {type: 'error'});
		jQuery("#u_email").addClass("error");
		isRequired = false;
	}else if (u_address_1==""){
		jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter valid Address 1', {type: 'error'});
		jQuery("#u_address_1").addClass("error");
		isRequired = false;
	}else if (u_city==""){
		jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter valid City', {type: 'error'});
		jQuery("#u_city").addClass("error");
		isRequired = false;
	}else if (u_state==""){
		jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter valid State', {type: 'error'});
		jQuery("#u_state").addClass("error");
		isRequired = false;
	}else if (u_country==""){
		jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter valid Country', {type: 'error'});
		jQuery("#u_country").addClass("error");
		isRequired = false;
	}else if (u_zip==""){
		jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter valid Zip', {type: 'error'});
		jQuery("#u_zip").addClass("error");
		isRequired = false;
	}
	if (isRequired==false){
		$("html").scrollTop(0);
		return false;
	}
	jQuery.post(root_url+"includes/ajax/users.ajax.php", { action: actionType, dataVals:sData},
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
function AddCmsUser(){
	var sData = jQuery("#frm_cms_users_add").serialize();
	var actionType = "addCmsUser";
	var isRequired = true;
	
	var cms_u_uname = jQuery("#cms_u_uname").val();
	var cms_u_email = jQuery("#cms_u_email").val();
	var cms_u_fname = jQuery("#cms_u_fname").val();
	var cms_u_lname = jQuery("#cms_u_lname").val();
	var cms_u_password = jQuery("#cms_u_password").val();
	var cms_u_password_confirm = jQuery("#cms_u_password_confirm").val();
	var cms_u_phone = jQuery("#cms_u_phone").val();
	var cms_u_group = jQuery("#cms_u_group").val();

	jQuery("form input").removeClass("error");
		
	if (cms_u_uname==""){
		jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter User Name', {type: 'error'});
		jQuery("#cms_u_uname").addClass("error");
		isRequired = false;
	}else if( !isValidEmailAddress( cms_u_email ) ) { 
	/* do stuff here */ 
		jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter valid Email Id', {type: 'error'});
		jQuery("#cms_u_email").addClass("error");
		isRequired = false;
	
	}else if (cms_u_fname==""){
		jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter First Name', {type: 'error'});
		jQuery("#cms_u_fname").addClass("error");
		isRequired = false;
	}else if (cms_u_lname==""){
		jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter Last Name', {type: 'error'});
		jQuery("#cms_u_lname").addClass("error");
		isRequired = false;
	}else if (cms_u_password==""){
		jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter Password', {type: 'error'});
		jQuery("#cms_u_password").addClass("error");
		isRequired = false;
	}else if (cms_u_password_confirm==""){
		jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter Confirm Password', {type: 'error'});
		jQuery("#cms_u_password_confirm").addClass("error");
		isRequired = false;
	}else if(!validatePhoneNumber(cms_u_phone)){
		jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter valid Phone number', {type: 'error'});
		jQuery("#cms_u_phone").addClass("error");
		isRequired = false;
	 
	}else if (cms_u_group==""){
		jQuery('#frm_error').removeBlockMessages().blockMessage('Please select Group', {type: 'error'});
		jQuery("#cms_u_group").addClass("error");
		isRequired = false;
	}

	if(cms_u_password != cms_u_password_confirm)
	{
	  //alert('Wrong confirm password !');
	  jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter the same password as above', {type: 'error'});
	  jQuery("#cms_u_password_confirm").addClass("error");
	  isRequired = false;
	}
		
		
	
	if (isRequired==false){
		$("html").scrollTop(0);
		return false;
	}
	jQuery.post(root_url+"includes/ajax/users.ajax.php", { action: actionType, dataVals:sData},
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
function validatePhoneNumber(elementValue){  
var phoneNumberPattern = /^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/;  
return phoneNumberPattern.test(elementValue);  
}  
function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
};
function saveUserPassword(){
	//alert("saveUserPassword");
	var actionType = "saveUserPassword";
	var isRequired = true;
	
	var u_password = jQuery("#u_password").val();
	var u_password_confirm = jQuery("#u_password_confirm").val();
	//jQuery("#frm_error").show();	
	jQuery("form input").removeClass("error");
	if(u_password != '' && u_password != u_password_confirm){
		jQuery('#frm_error').removeBlockMessages().blockMessage('Both passwords are not matching', {type: 'error'});
		jQuery("#u_password_confirm").addClass("error");
		jQuery("#u_password").addClass("error");
		isRequired = false;
	}
	if (isRequired==false){
		$("html").scrollTop(0);
		return false;
	}
	if(u_password != ''){
		jQuery.post(root_url+"includes/ajax/users.ajax.php", { action: actionType, passwd:u_password},
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
	}else{
		jQuery('#frm_users_password').get(0).setAttribute('action', root_url+"users/profile/");
		jQuery('#frm_users_password').submit();
		return true;
	}
}
function checkEmail(){
	var actionType = "checkEmail";
	var u_email = jQuery("#u_email").val();
	$.post(root_url+"includes/ajax/users.ajax.php", { action: actionType, email:u_email},
		function(data){
			//alert(data.msg);
			if(data.msg=='valid')
			{
				jQuery('p.message').hide();
				jQuery("#u_email").removeClass("error");
				return true;
			} else
			{
				jQuery('#frm_error').removeBlockMessages().blockMessage('Email Address already exists', {type: 'error'});
				jQuery("#u_email").addClass("error");
				return false;
			}
		}, 'json'
	);	

	return false;

}
function updateBreadcrumb(){
	//alert('updateBreadcrumb');
	//var newURL = window.location.protocol + "//" + window.location.host + window.location.pathname;
	//alert(newURL);
	var pathArray = window.location.pathname.split( '/' );
	if (pathArray[2]!=""){
		jQuery("div.container_12 ul li").removeClass('current');
		jQuery("div.container_12 ul li#"+pathArray[1]).addClass("current");
		if(pathArray[2]=="clients"){
			if(pathArray[3]=="id"){
				var client_name = jQuery("#client_name").html();
				jQuery("ul#breadcrumb li:nth-child(2)").html('<a title="Clients" href="'+root_url+'clients/">Clients</a>');
				jQuery("ul#breadcrumb").append('<li><a title="'+client_name+'" href="'+root_url+'clients/id/'+pathArray[4]+'">'+client_name+'</a></li>');
				if(pathArray[5]=="products"){
					jQuery("ul#breadcrumb").append('<li><a title="Products" href="'+root_url+'clients/id/'+pathArray[4]+'/products/">Products</a></li>');
				}
			}else{
				jQuery("ul#breadcrumb li:nth-child(2)").html('<a title="clients" href="'+root_url+'clients/">Clients</a>');
			}
		}else if(pathArray[2]=="users"){
			if(pathArray[3]=="requisition"){
				jQuery("ul#breadcrumb li:nth-child(2)").html('<a title="My Packages" href="'+root_url+'packages/">My Packages</a>');
			}else{
				jQuery("ul#breadcrumb li:nth-child(2)").html('<a title="My Profile" href="'+root_url+'users/profile/">My Profile</a>');
			}
		}else if(pathArray[2]=="billing"){
			jQuery("ul#breadcrumb li:nth-child(2)").html('<a title="Billing" href="'+root_url+'billing/current">Billing</a>');
		}else if(pathArray[2]=="invoices"){
			jQuery("ul#breadcrumb li:nth-child(2)").html('<a title="Invoices" href="'+root_url+'invoices/">Invoices</a>');
		}else if(pathArray[2]=="reports"){
			jQuery("ul#breadcrumb li:nth-child(2)").html('<a title="Reports" href="'+root_url+'reports/monthly">Reports</a>');
		}
	}else{
		jQuery("div.container_12 ul li").removeClass('current');
		jQuery("div.container_12 ul li#dashboard").addClass("current");
	}
}
 jQuery(document).ready(function() {
	jQuery("form input").keypress(function (e) {
		if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
			//alert($('form').attr('id'));
			var formID = ($(this.form).attr('id'));
			if (formID=="frm_requisition"){
				saveRequisition();
			}
			if (formID=="login-form"){
				doLogin();
			}
			if (formID=="frm_users_profile"){
				saveUserProfile();
			}
			if (formID=="frm_users_password"){
				saveUserPassword();
			}
			return false;
		} else {
			return true;
		}
	});
});

//appusers scripts

function saveAppUserProfile(){
	
	//alert("saveUserProfile");
	var sData = jQuery("#frm_app_users_profile").serialize();
	var actionType = "saveAppUserProfile";
	var isRequired = true;
	
	var au_first_name = jQuery("#au_first_name").val();
	var au_last_name = jQuery("#au_last_name").val();
	var au_username = jQuery("#au_username").val();
	var au_password = jQuery("#au_password").val();
	var au_groupid = jQuery("#au_grp_id").val();
	var au_id = jQuery("#au_id").val();
	jQuery.post(root_url+"includes/ajax/users.ajax.php", { action: actionType, dataVals:sData},
		function(data){
			if(data.msg=='success')
			{
				alert("Successfully Updated")
				window.location=data.redirect;
			} else
			{
				jQuery('#frm_error').removeBlockMessages().blockMessage('Error Occured: Please contact us', {type: 'error'});
				return false;
			}
		}, 'json'
	);	
}

function updateCmsUserProfile(){
	//alert("saveUserProfile");
	var sData = jQuery("#frm_cms_users_profile_edit").serialize();
	var actionType = "updateCmsUserProfile";
	
	
	var cms_u_uname = jQuery("#cms_u_uname").val();
	var cms_u_email = jQuery("#cms_u_email").val();
	var cms_u_fname = jQuery("#cms_u_fname").val();
	var cms_u_lname = jQuery("#cms_u_lname").val();
	var cms_u_password = jQuery("#cms_u_password").val();
	var cms_u_password_confirm = jQuery("#cms_u_password_confirm").val();
	var cms_u_phone = jQuery("#cms_u_phone").val();
	var cms_u_group = jQuery("#cms_u_group").val();
    var isRequired = true;
	jQuery("form input").removeClass("error");
		
	if( !isValidEmailAddress( cms_u_email ) ) { 
	
		jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter valid Email Id', {type: 'error'});
		jQuery("#cms_u_email").addClass("error");
		isRequired = false;
	
	
	}else if(!validatePhoneNumber(cms_u_phone)){
		jQuery('#frm_error').removeBlockMessages().blockMessage('Please enter valid Phone number', {type: 'error'});
		jQuery("#cms_u_phone").addClass("error");
		isRequired = false;
	 
	}
	if (isRequired==false){
		$("html").scrollTop(0);
		return false;
	}
	
	
	
	/*var cms_u_fname = jQuery("#cms_u_fname").val();
	var cms_u_lname = jQuery("#cms_u_lname").val();
	var cms_u_email = jQuery("#u_email").val();
	var cms_u_address_1 = jQuery("#cms_u_address_1").val();
	var cms_u_city = jQuery("#cms_u_city").val();
	var cms_u_state = jQuery("#cms_u_state").val();
	var cms_u_country = jQuery("#cms_u_country").val();
	var cms_u_zip = jQuery("#cms_u_zip").val();*/
	
	
	jQuery.post(root_url+"includes/ajax/users.ajax.php", { action: actionType, dataVals:sData},
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

function deleteCmsUser(pUserId){
	var r=confirm("Are you sure want to delete?");
if (r==true)
  {
	var actionType = "deleteCmsUser";
	$.post(root_url+"includes/ajax/users.ajax.php", { action: actionType, uid:pUserId},
		function(data){
			if(data.msg=='success')
			{
				window.location=data.redirect;
				return true;
			} else
			{
				alert('User not deleted');
				return false;
			}
		}, 'json'
	);	

	return false;
  }
}
