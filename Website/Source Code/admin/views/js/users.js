// JavaScript Document



function updateUserDetails()
{
	$('#error').html('');
	var user_name = $("#user_name").val();
	var password = $("#password").val();
	var passwordConfirm = $("#confirm_password").val();
	var email_address = $("#user_email").val();
	var phone = $("#user_phone").val();
	var user_id = $("#user_id").val();
	var user_type = $("#user_type").val();
	var action   = "updateUserDetails";
	if (email_address=="") {
			$('#error_email').html('Please enter  Email Address');
		        return false;
	}
	if (email_address!="") {
          
		if( !isValidEmailAddress( email_address ) )
		{
			$('#error_email').html('Please enter valid Email Address');
		        return false;
		}
		
	}
	if (password!=""){
		if ((passwordConfirm=="") || (password!=passwordConfirm)){
			$("#retypemsg").hide();
			$("#error_pass_confirm").html("Passwords doesn't match");
			$("#password").val('');
			$("#confirm_password").val('');
			return false;
		}
		/*
		var passed = validatePassword(password, {
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
			return false;
		}*/
	}
	$.post(rootUrl+"includes/ajax/users.ajax.php",{user_name: user_name,password:password, email_address:email_address,phone: phone,user_id: user_id,user_type: user_type,action: action},
		function(data){
			//alert(data);
			if(data==1)
			{
			
				$('#success_msg').show();
				//window.location = rootUrl+'index.php';
				//document.form1.action = rootUrl+'index.php';
				//document.forms["form1"].submit();
			}
			else
			{
				$('#error_msg').show();			
				//$('#error').html('The username or password you entered is incorrect.');
				return false;
			}
		}
	);
	return false;
}
function addNewUser()
{
	$('#error').html('');
	var user_name = $("#user_name").val();
	var password = $("#password").val();
	var passwordConfirm = $("#confirm_password").val();
	var email_address = $("#user_email").val();
	var phone = $("#user_phone").val();
	var user_type = $("#user_type").val();
	
	var action   = "addNewUser";
	if (user_name=="") {
		$('#error_username').html('Please enter Username');
	        return false;
	}
	if (password=="") {
		$('#error_pass').html('Please enter password');
	        return false;
	}
	if (passwordConfirm=="") {
		$('#error_pass_confirm').html('Please enter verify password');
	        return false;
	}
	
	if (email_address!="") {
          
		if( !isValidEmailAddress( email_address ) )
		{
			$('#error_email').html('Please enter valid Email Address');
		        return false;
		}
		
	}
	if (password!=""){
		if ((passwordConfirm=="") || (password!=passwordConfirm)){
			$("#retypemsg").hide();
			$("#error_pass").html("Passwords doesn't match");
			$("#password").val('');
			$("#confirm_password").val('');
			return false;
		}
		/*
		var passed = validatePassword(password, {
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
			return false;
		}*/
	}
	$.post(rootUrl+"includes/ajax/users.ajax.php",{user_name: user_name,password:password, email_address:email_address,phone: phone,user_type:user_type,action: action},
		function(data){
			//alert(data);
			if(data==1)
			{
			
				$('#success_msg').show();
				//window.location = rootUrl+'index.php';
				//document.form1.action = rootUrl+'index.php';
				//document.forms["form1"].submit();
			}
			else
			{
				$('#error_msg').show();			
				//$('#error').html('The username or password you entered is incorrect.');
				return false;
			}
		}
	);
	return false;
}

function validateUSPhone(f,evt){
	evt = (evt) ? evt : window.event;
	var key = (evt.which) ? evt.which : evt.keyCode;
	var strphone = f.value;
	if(key>47 && key<58){
		//alert(key);
		if ((strphone.length==3) || (strphone.length==7)){
			f.value = strphone+"-";
			
		} else if (strphone.length > 12){
			//alert(strphone.length+input.val());
			f.value = strphone.substring(0,12);
		}
		
	}else{
		strphone = strphone.replace(/[^0-9-]/,'');
		strphone = strphone.replace('--','-');
		f.value= strphone;
	}
	if (strphone.length <= 10){
		
		var numString = strCount(strphone);
		//alert(numString);
		if (numString==0){
			//alert('have hyphens');
			if (strphone.length==10){
				var appendPhone = strphone.substring(0,3)+"-"+strphone.substring(3,6)+"-"+strphone.substring(6,10);
				f.value = appendPhone;
			}
		}
	}
}
function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
};