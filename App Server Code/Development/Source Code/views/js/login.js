function doLogin(){
	var actionType = "doLogin";
	var username = $('#username').val();
	var password = $('#password').val();
	if (username==""){
		$('#error_msg').html('Please enter your user name');
		return false;
	}
	if (password==""){
		$('#error_msg').html('Please enter your password');
		return false;
	}
	$('#error_msg').css("color","green");
	$('#error_msg').html('Please wait, checking login...');
	
	$.post(root_url+"includes/ajax/login.ajax.php", { action: actionType, username: username, password: password},
		function(data){
			if(data.msg=='success')
			{
				window.location=root_url+data.redirect;
				return true;
			} else
			{
				$('#error_msg').css("color","red");
				$('#error_msg').html('Please enter valid username and password');
				$("#username").focus();
				return false;
			}
		}, 'json'
	);	
	return false;
}
function doLogout(){
	var actionType = "doLogout";
	$.post(root_url+"includes/ajax/login.ajax.php", { action: actionType},
		function(data){
			if(data.msg=='success')
			{
				window.location=root_url+data.redirect;
				return true;
			} else
			{
				return false;
			}
		}, 'json'
	);	
	return false;
}
function forgotPassword(){
	//alert("forgotPassword");
	var actionType = "forgotPassword";
	var isRequired = true;
	
	var recovery_mail = jQuery("#recovery_mail").val();
	//jQuery("#frm_error").show();	
	jQuery("form input").removeClass("error");
	if(recovery_mail==""){
		jQuery('#frm_error').removeBlockMessages().blockMessage('Both passwords are not matching', {type: 'error'});
		jQuery("#recovery_mail").addClass("error");
		isRequired = false;
	}
	if (isRequired==false){
		$("html").scrollTop(0);
		return false;
	}
	
		jQuery.post(root_url+"includes/ajax/users.ajax.php", { action: actionType, email:recovery_mail},
			function(data){
				if(data.msg=='success')
				{
					$('#login-block').removeBlockMessages().blockMessage('We sent you password', {type: 'success'});
					$("#recovery_mail").focus();
				} else
				{
					if (data.msg!=""){
						$('#login-block').removeBlockMessages().blockMessage(data.msg, {type: 'error'});
					}else{
						$('#login-block').removeBlockMessages().blockMessage('Error Occured: Please contact us', {type: 'error'});
					}
					$("#recovery_mail").focus();
					return false;
				}
			}, 'json'
		);	
	
}
$("form input").keypress(function (e) {
	if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
		var formID = ($(this.form).attr('id'));
		if (formID=="login-form"){
			doLogin();
		}
		return false;
	} else {
		return true;
	}
});
function submitDateRange(prdId){
	var fromdate = $('#from').val();
	var todate = $('#to').val();

				window.location.href=root_url+"analytics/mobile_users/products/flow/"+prdId+"/"+fromdate+"/"+todate;
				return false;

}

function submitDateRangeOffersFlow(offerId){
	var fromdate = $('#from').val();
	var todate = $('#to').val();

				window.location.href=root_url+"analytics/mobile_users/offers/flow/"+offerId+"/"+fromdate+"/"+todate;
				return false;

}
