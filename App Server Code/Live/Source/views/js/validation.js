function loadStates(){
	var actionType = "loadStates";
	var defaultVal = $("#hdn_def_val").val();
	$.post(root_url+"includes/ajax/common.ajax.php", { action: actionType},
		function(data){
			//alert(data[1].state_name);
			var putOptionsValues = "<option value='"+defaultVal+"'>--select state--</option>";
			for (i=0;i<data.length;i++){
				putOptionsValues += "<option value='"+data[i].state_2_code+"'>"+data[i].state_name+"</option>"
			}
			$("#state_select").html(putOptionsValues);
			if(data.msg=='success')
			{
				return true;
			} else
			{
				return false;
			}
		}, 'json'
	);	
	return false;
}

function toValidateUSPhone(pPhone){
	var phoneNumberPattern = /^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/;  
	return phoneNumberPattern.test(pPhone);  
}

function whichKey(e) {
	var code;
	if (!e) var e = window.event;
	if (e.keyCode) code = e.keyCode;
	else if (e.which) code = e.which;
	return code
//	return String.fromCharCode(code);
}

function validateUSNumber(e,f){
	var input = $(f).find('input');
    var strphone = input.val();
	var key = whichKey(e);
	if(key>47 && key<58){
		//alert(key);
		if ((strphone.length==3) || (strphone.length==7)){
			input.val(strphone+"-");
			
		} else if (strphone.length > 12){
			//alert(strphone.length+input.val());
			input.val(strphone.substring(0,12));
		}
		
	}else{
		strphone = strphone.replace(/[^0-9-]/,'');
		strphone = strphone.replace('--','-');
		input.val(strphone);
	}
	if (strphone.length <= 10){
			
			var numString = strCount(strphone);
			//alert(numString);
			if (numString==0){
				//alert('have hyphens');
				if (strphone.length==10){
					var appendPhone = strphone.substring(0,3)+"-"+strphone.substring(3,6)+"-"+strphone.substring(6,10);
					input.val(appendPhone);
				}
			}
		}
	
}
function checkIt(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
       status = "This field accepts numbers only.";
        return false;
    }
   status = "";
var txtPhone = document.getElementById('cms_u_phone').value;
	validateUSNumber(txtPhone,evt);
    return true;
}
function strCount(strphone){
	var pos = 0;
	var num = -1;
	var i = -1;
	var graf = strphone;

	while (pos != -1) {
	pos = graf.indexOf("-", i + 1);
	num += 1;
	i = pos;
	}
	return num;
}
function isEmtpy(pCheckField){
	if ($.trim(pCheckField)=="")
		return false;
	else 
		return true;
}
function validateEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}
function validatePassword (pw, options) {
	// default options (allows any password)
	var o = {
		lower:    0,
		upper:    0,
		alpha:    0, /* lower + upper */
		numeric:  0,
		special:  0,
		length:   [0, Infinity],
		custom:   [ /* regexes and/or functions */ ],
		badWords: [],
		badSequenceLength: 0,
		noQwertySequences: false,
		noSequential:      false
	};

	for (var property in options)
		o[property] = options[property];

	var	re = {
			lower:   /[a-z]/g,
			upper:   /[A-Z]/g,
			alpha:   /[A-Z]/gi,
			numeric: /[0-9]/g,
			special: /[\W_]/g
		},
		rule, i;

	// enforce min/max length
	if (pw.length < o.length[0] || pw.length > o.length[1])
		return "Password should be minimum of "+length[0]+" characters";

	// enforce lower/upper/alpha/numeric/special rules
	for (rule in re) {
		if ((pw.match(re[rule]) || []).length < o[rule])
			return "Password should contain atleast one Lower, Upper, Numeric and Special character";
	}

	// enforce word ban (case insensitive)
	for (i = 0; i < o.badWords.length; i++) {
		if (pw.toLowerCase().indexOf(o.badWords[i].toLowerCase()) > -1)
			return "This is not allowed";
	}

	// enforce the no sequential, identical characters rule
	if (o.noSequential && /([\S\s])\1/.test(pw))
		return "Password doesn't accept sequential or identical characters.";

	// enforce alphanumeric/qwerty sequence ban rules
	if (o.badSequenceLength) {
		var	lower   = "abcdefghijklmnopqrstuvwxyz",
			upper   = lower.toUpperCase(),
			numbers = "0123456789",
			qwerty  = "qwertyuiopasdfghjklzxcvbnm",
			start   = o.badSequenceLength - 1,
			seq     = "_" + pw.slice(0, start);
		for (i = start; i < pw.length; i++) {
			seq = seq.slice(1) + pw.charAt(i);
			if (
				lower.indexOf(seq)   > -1 ||
				upper.indexOf(seq)   > -1 ||
				numbers.indexOf(seq) > -1 ||
				(o.noQwertySequences && qwerty.indexOf(seq) > -1)
			) {
				return "Password should not contain sequential, Qwerty sequence";
			}
		}
	}

	// enforce custom regex/function rules
	for (i = 0; i < o.custom.length; i++) {
		rule = o.custom[i];
		if (rule instanceof RegExp) {
			if (!rule.test(pw))
				return false;
		} else if (rule instanceof Function) {
			if (!rule(pw))
				return false;
		}
	}

	// great success!
	return "success";
}
function checkUsername(pUname){
	re = /^\w+$/;
    if(!re.test(pUname)) {
      alert("Error: Username must contain only letters, numbers and underscores!");
      return false;
    }
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