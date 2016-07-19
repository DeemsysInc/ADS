// JavaScript Document


function saveEnquiry()
{
	
	$('#error').html('');
	var firstname = $("#firstname").val();
	var lastname = $("#lastname").val();
	var company = $("#company").val();
	var email = $("#email").val();
	var phone = $("#phone").val();
	var reason = $("input[name='reason']").val();
	var addinfo = $("#addinfo").val();
	var position = $("#position").val();
	var action   = "saveenquiry";
	
	if(firstname == "")
	{
		$('#error').html('Please enter first name');
		return false;
	}
	if(lastname == "")
	{
		$('#error').html('Please enter last name');
		return false;
	}
	if(company == "")
	{
		$('#error').html('Please enter company');
		return false;
	}
	if(email == "")
	{
		$('#error').html('Please enter Email Address');
		return false;
	}
	else
	{
		if( !isValidEmailAddress( email ) )
		{
			$('#error').html('Please enter valid Email Address');
		        return false;
		}
		
	}


	 
	if(phone == "")
	{
		$('#error').html('Please enter phone');
		return false;
	}
	$.post(root_url+"includes/common.ajax.php",{firstname:firstname, lastname:lastname,company: company,email: email,phone: phone,reason: reason,addinfo: addinfo,position:position,action: action},
		function(data){
		//	alert(data);
			if(data==1)
			{
			
				//$('#error').html('');
				$('#pre').hide();
				$('#post').show();
			}
			else
			{				
				$('#error').html('There is problem has occured.');
				return false;
			}
		}
	);
	return false;
}
function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
};