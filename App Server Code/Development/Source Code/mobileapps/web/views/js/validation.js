function validate(){

	var first_name = $("#first_name").val();
	var last_name = $("#last_name").val();
	var date_of_birth = $("#date_of_birth").val();
	var email = $("#email").val();
	
	var pattern = /^[a-zA-Z0-9\-_]+(\.[a-zA-Z0-9\-_]+)*@[a-z0-9]+(\-[a-z0-9]+)*(\.[a-z0-9]+(\-[a-z0-9]+)*)*\.[a-z]{2,4}$/;
	if(first_name == '' || first_name=='Enter your first name.'){
		//alert('please enter  your first name.');
		$("#spanerr").text("please enter  your first name.");
		return false;
	}
	
	if(last_name == '' || last_name=='Enter your last name.'){
	$("#spanerr").text("Please enter your last name.");
		return false;
	}
	
	if(date_of_birth == '' || date_of_birth=='Enter your birth date.'){
		$("#spanerr").text("Please enter your birth date.");
		return false;
	}
	
	if(email == '' || email=='Enter your email address.'){
		$("#spanerr").text("Please enter your email address.");
		return false;
	}
	
	else{
		 if(!pattern.test(email)) {
			 $("#spanerr").text("Please enter proper email address");
		     return false;
    	}
	
	}

   document.getElementById('form').submit();

/*
    $.ajax({
           type: "POST",
           url: actionUrl,
           data: $("#idForm").serialize(), // serializes the form's elements.
           success: function(data)
           {
               alert(data); // show response from the php script.
			   window.location =thanksUrl;
           }
         });

    		return false; // avoid to execute the actual submit of the form.
*/
}

function clearval(){
	//alert("clear");
	
	$("#first_name").val('Enter your first name.');
	$("#last_name").val('Enter your last name.');
	$("#date_of_birth").val('Enter your birth date.');
	$("#email").val('Enter your email address.');
	$("#comments").val('Enter your comments.');
	$("#spanerr").text("");
	
	$("#first_name").toggleClass('boxes hint');
	$("#last_name").toggleClass('boxes hint');
	$("#date_of_birth").toggleClass('boxes hint');
	$("#email").toggleClass('boxes hint');
	$("#comments").toggleClass('boxes hint');
}

function clearFeedback(){

	$("#name").val('');
	$("#age").val('');
	("#spouse_age").val('');
	$("#email").val('');
	$("#address").val('');
	$("#state").val('');
	$("#city").val('');
	$("#zip_code").val('');
	$("#spanerr").text("");
	

}

function feedbackSubmit()
{
	
	var first_name = $("#name").val();
	var email = $("#email").val();
	
	var pattern = /^[a-zA-Z0-9\-_]+(\.[a-zA-Z0-9\-_]+)*@[a-z0-9]+(\-[a-z0-9]+)*(\.[a-z0-9]+(\-[a-z0-9]+)*)*\.[a-z]{2,4}$/;
	if(first_name == ''){
		//alert('please enter  your first name.');
		$("#spanerr").text("please enter  your  name.");
		return false;
	}
	
	if(email == ''){
		$("#spanerr").text("Please enter your email address.");
		return false;
	}
	
	else{
		 if(!pattern.test(email)) {
			 $("#spanerr").text("Please enter proper email address");
		     return false;
    	}
	
	}

	   document.getElementById('form').submit();
}
