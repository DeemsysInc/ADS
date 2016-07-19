// JavaScript Document


function login()
{
	$('#error').html('');
	var username = $("#user_name").val();
	var password = $("#password").val();
	var remember = "";
	
	/*if($('#interests').is(':checked'))
	{	
		remember = $("#interests").val();
		
	}*/
	var action   = "login";
	
	if(username == "")
	{
		$('#error').html('Please enter username');
		return false;
	}
	if(password == "")
	{
		$('#error').html('Please enter password');
		return false;
	}
	$.post(rootUrl+"includes/ajax.php",{username:username, password:password,action: action},
		function(data){
			if(data==1)
			{
			
				$('#error').html('');
				window.location = rootUrl+'index.php';
				//document.form1.action = rootUrl+'index.php';
				//document.forms["form1"].submit();
			}
			else
			{				
				$('#error').html('The username or password you entered is incorrect.');
				return false;
			}
		}
	);
	return false;
}

function savePressReleases()
{
	
	$('#error').html('');
	var press_title = $("#press_title").val();
	var press_date = $("#press_date").val();
	var press_url = $("#press_url").val();
	//var press_publication = $("#press_publication").val();
	var press_publication = $('input:radio[id=press_publication]:checked').val();
	var action   = "savepressrelease";
	
	if(press_title == "")
	{
		$('#error').html('Please enter Title');
		return false;
	}
	
	$.post(rootUrl+"includes/ajax.php",{press_title:press_title, press_date:press_date,press_url: press_url,press_publication: press_publication,action: action},
		function(data){
			if(data==1)
			{
			
				$('#success_msg').show();
				//document.form1.action = rootUrl+'index.php';
				//document.forms["form1"].submit();
			}
			else
			{				
				$('#error_msg').show();	
				return false;
			}
		}
	);
	return false;
}

function updatePressReleases()
{
	
	$('#error').html('');
	var press_title = $("#press_title").val();
	var press_date = $("#press_date").val();
	var press_url = $("#press_url").val();
	var press_publication = $("#press_publication").val();
	var press_id = $("#press_id").val();
	
	var action   = "updatepressrelease";
	
	
	$.post(rootUrl+"includes/ajax.php",{press_id: press_id,press_title:press_title, press_date:press_date,press_url: press_url,press_publication: press_publication,action: action},
		function(data){
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
function saveNews()
{
	
	$('#error').html('');
	var news_title = $("#news_title").val();
	var news_subtitle = $("#news_subtitle").val();
	var news_location = $("#news_location").val();
	var news_date = $("#news_date").val();
	if ($('#news_featured:checked').val()=='1') {
	     var news_featured = $('#news_featured:checked').val();
	}
	else
	{
	     var news_featured = "0";
	}
	if ($('#news_include_about:checked').val()=='1') {
	     var news_include_about = $('#news_include_about:checked').val();
	}
	else
	{
	     var news_include_about = "0";
	}
	
	var excerpt = $("#news_excerpt").val();
	var content = $("#news_content").val();
	
	
	
	var action   = "savenews";
	
	
	
	$.post(rootUrl+"includes/ajax.php",{news_title:news_title, news_subtitle:news_subtitle,news_location: news_location,news_date: news_date,news_featured: news_featured,news_include_about: news_include_about,excerpt: excerpt,content:content,action: action},
		function(data){
			if(data==1)
			{
			
				$('#success_msg').show();
				
			}
			else
			{
				$('#error_msg').show();			
				return false;
			}
		}
	);
	return false;
}

function updateNews()
{
	
	$('#error').html('');
	var news_title = $("#news_title").val();
	var news_subtitle = $("#news_subtitle").val();
	var news_location = $("#news_location").val();
	var news_date = $("#news_date").val();
	if ($('#news_featured:checked').val()=='1') {
	     var news_featured = $('#news_featured:checked').val();
	}
	else
	{
	     var news_featured = "0";
	}
	if ($('#news_include_about:checked').val()=='1') {
	     var news_include_about = $('#news_include_about:checked').val();
	}
	else
	{
	     var news_include_about = "0";
	}
	
	var excerpt = $("#news_excerpt").val();
	var content = $("#news_content").val();
	var news_id = $("#news_id").val();
	
	var action   = "updatenews";
	
	
	$.post(rootUrl+"includes/ajax.php",{news_id: news_id,news_title:news_title, news_subtitle:news_subtitle,news_location: news_location,news_date: news_date,news_featured: news_featured,news_include_about: news_include_about,excerpt: excerpt,content:content,action: action},
		function(data){
			if(data==1)
			{
			
				$('#success_msg').show();
				
			}
			else
			{
				$('#error_msg').show();			
				return false;
			}
		}
	);
	return false;
}

function deletePressRelease(press_id){
	var r=confirm("Are you sure want to delete?");
if (r==true)
  {
	var action   = "deletePressRelease";
	$.post(rootUrl+"includes/ajax.php",{press_id: press_id,action: action},
		function(data){
			if(data.msg=='success')
			{
				window.location=data.redirect;
				return true;
			} else
			{
				alert('Deletion Failed');
				return false;
			}
		}, 'json'
	);	
	

	return false;
  }
}

function deleteNews(news_id){
	var r=confirm("Are you sure want to delete?");
if (r==true)
  {
	var action   = "deleteNews";
	$.post(rootUrl+"includes/ajax.php",{news_id: news_id,action: action},
		function(data){
			if(data.msg=='success')
			{
				window.location=data.redirect;
				return true;
			} else
			{
				alert('Deletion Failed');
				return false;
			}
		}, 'json'
	);	
	

	return false;
  }
}

