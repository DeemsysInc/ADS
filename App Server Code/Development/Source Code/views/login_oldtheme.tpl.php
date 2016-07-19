<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en" class="no-js">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Seemore | Admin Control Panel</title>

	<script type="text/javascript">var root_url = "<?php echo $config['LIVE_URL']; ?>" </script>
	

	<!-- Global stylesheets -->
	<link href="<?php echo $config['LIVE_URL']; ?>views/css/reset.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $config['LIVE_URL']; ?>views/css/common.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $config['LIVE_URL']; ?>views/css/form.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $config['LIVE_URL']; ?>views/css/standard.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $config['LIVE_URL']; ?>views/css/special-pages.css" rel="stylesheet" type="text/css">
	
	<!-- Favicon -->
	<!-- <link rel="shortcut icon" type="image/x-icon" href="<?php echo $config['LIVE_URL']; ?>views/favicon.ico"> -->
	<!-- <link rel="icon" type="image/png" href="<?php echo $config['LIVE_URL']; ?>views/favicon-large.png"> -->
	
	<!-- Modernizr for support detection, all javascript libs are moved right above </body> for better performance -->
	<script src="<?php echo $config['LIVE_URL']; ?>views/js/libs/modernizr.custom.min.js"></script>
</head>

<!-- the 'special-page' class is only an identifier for scripts -->
<body class="special-page login-bg dark">
	<!-- Logo section -->
	
	
	
	<section id="login-block">
		<p style="text-align:center"><img src="<?php echo $config['LIVE_URL']; ?>views/images/logo.png"></p>
		<div class="block-border">
			<div class="block-content">
			
			<!--
			IE7 compatibility: if you want to remove the <h1>, 
			add style="zoom:1" to the above .block-content div
			-->
			<h1>Admin</h1>
			<div class="block-header">Please login</div>
				
			<p class="no-margin">&nbsp;</p>
			
			<form class="form with-margin" name="login-form" id="login-form" method="post" action="">
				<input type="hidden" name="a" id="a" value="send">
				<p class="inline-small-label">
					<label for="login"><span class="big">User name</span></label>
					<input type="text" name="username" id="username" class="full-width" value="">
				</p>
				<p class="inline-small-label">
					<label for="pass"><span class="big">Password</span></label>
					<input type="password" name="password" id="password" class="full-width" value="">
				</p>
				
				<button type="button" class="float-right" onclick="doLogin();">Login</button>
				<p class="input-height">
					<input type="checkbox" name="keep-logged" id="keep-logged" value="1" class="mini-switch" checked="checked">
					<label for="keep-logged" class="inline">Keep me logged in</label>
				</p>
			</form>
			
			<form class="form" id="password-recovery" method="post" action="">
				<fieldset class="grey-bg no-margin collapse">
					<legend><a href="#">Lost password?</a></legend>
					<p class="input-with-button">
						<label for="recovery_mail">Enter your e-mail address</label>
						<input type="text" name="recovery_mail" id="recovery_mail" value="">
						<button type="button" onclick="forgotPassword();">Send</button>
					</p>
				</fieldset>
			</form>
		</div>
		<div class="footer" style="margin-top:10px;">&copy; <a href="http://www.seemoreinteractive.com">Seemore Interactive</a> | CMS Control Panel</div>
		</div>
	</section>
	
	<!--
	
	Updated as v1.5:
	Libs are moved here to improve performance
	
	-->
	
	<!-- Generic libs -->
	<script src="<?php echo $config['LIVE_URL']; ?>views/js/libs/jquery-1.6.3.min.js"></script>
	<script src="<?php echo $config['LIVE_URL']; ?>views/js/old-browsers.js"></script>		<!-- remove if you do not need older browsers detection -->
	
	<!-- Template libs -->
	<script src="<?php echo $config['LIVE_URL']; ?>views/js/common.js"></script>
	<script src="<?php echo $config['LIVE_URL']; ?>views/js/standard.js"></script>
	<!--[if lte IE 8]><script src="js/standard.ie.js"></script><![endif]-->
	<script src="<?php echo $config['LIVE_URL']; ?>views/js/jquery.tip.js"></script>
	
	<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/login.js"></script>
	
<script type="text/javascript">
</script>
			
</body>
</html>
