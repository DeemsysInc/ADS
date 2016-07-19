<!DOCTYPE html>
<html lang="en">
<head>
	
	<meta charset="utf-8">
	<title>Seemore Interactive</title>
	
	<!-- The styles -->
	<link id="bs-css" href="<?php echo $config['LIVE_ADMIN_URL']?>views/css/bootstrap-cerulean.css" rel="stylesheet">
	<style type="text/css">
	  body {
		padding-bottom: 40px;
	  }
	  .sidebar-nav {
		padding: 9px 0;
	  }
	</style>
	<script type="text/javascript">var rootUrl = "<?php echo $config['LIVE_ADMIN_URL']?>";</script>

<script>
	var root_url = "<?php echo $config['LIVE_URL']?>";
	
</script>

	<link href="<?php echo $config['LIVE_ADMIN_URL']?>views/css/bootstrap-responsive.css" rel="stylesheet">
	<link href="<?php echo $config['LIVE_ADMIN_URL']?>views/css/charisma-app.css" rel="stylesheet">
	<link href="<?php echo $config['LIVE_ADMIN_URL']?>views/css/jquery-ui-1.8.21.custom.css" rel="stylesheet">
	<link href='<?php echo $config['LIVE_ADMIN_URL']?>views/css/fullcalendar.css' rel='stylesheet'>
	<link href='<?php echo $config['LIVE_ADMIN_URL']?>views/css/fullcalendar.print.css' rel='stylesheet'  media='print'>
	<link href='<?php echo $config['LIVE_ADMIN_URL']?>views/css/chosen.css' rel='stylesheet'>
	<link href='<?php echo $config['LIVE_ADMIN_URL']?>views/css/uniform.default.css' rel='stylesheet'>
	<link href='<?php echo $config['LIVE_ADMIN_URL']?>views/css/colorbox.css' rel='stylesheet'>
	<link href='<?php echo $config['LIVE_ADMIN_URL']?>views/css/jquery.cleditor.css' rel='stylesheet'>
	<link href='<?php echo $config['LIVE_ADMIN_URL']?>views/css/jquery.noty.css' rel='stylesheet'>
	<link href='<?php echo $config['LIVE_ADMIN_URL']?>views/css/noty_theme_default.css' rel='stylesheet'>
	<link href='<?php echo $config['LIVE_ADMIN_URL']?>views/css/elfinder.min.css' rel='stylesheet'>
	<link href='<?php echo $config['LIVE_ADMIN_URL']?>views/css/elfinder.theme.css' rel='stylesheet'>
	<link href='<?php echo $config['LIVE_ADMIN_URL']?>views/css/jquery.iphone.toggle.css' rel='stylesheet'>
	<link href='<?php echo $config['LIVE_ADMIN_URL']?>views/css/opa-icons.css' rel='stylesheet'>
	<link href='<?php echo $config['LIVE_ADMIN_URL']?>views/css/uploadify.css' rel='stylesheet'>

	<!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- The fav icon -->
	<link rel="shortcut icon" href="<?php echo $config['LIVE_ADMIN_URL']?>favicon.ico">
	<script src="<?php echo $config['LIVE_ADMIN_URL']?>views/js/jquery-1.7.2.min.js"></script>
		<script src="<?php echo $config['LIVE_ADMIN_URL']?>views/js/common.js"></script>
		
		
</head>

<body>
		<div class="container-fluid">
		<div class="row-fluid">
		
			<div class="row-fluid">
				<div class="span12 center login-header">
					<h2>Welcome to Seemoreinteractive</h2>
				</div><!--/span-->
			</div><!--/row-->
			
			<div class="row-fluid">
				<div class="well span5 center login-box">
					<div class="alert alert-info">
						Admin Panel
					</div>
					<div class="err" id="error" style="margin-left:30px;color:red;"></div>
					<form class="form-horizontal" action="<?php echo $config['LIVE_ADMIN_URL']?>index.php" method="post">
						<fieldset>
							<div class="input-prepend" title="Username" data-rel="tooltip">
								<span class="add-on"><i class="icon-user"></i></span><input autofocus class="input-large span10" name="user_name" id="user_name" type="text" value="admin" />
							</div>
							<div class="clearfix"></div>

							<div class="input-prepend" title="Password" data-rel="tooltip">
								<span class="add-on"><i class="icon-lock"></i></span><input class="input-large span10" name="password" id="password" type="password" value="admin123456" />
							</div>
							<div class="clearfix"></div>

						<!-- 
	<div class="input-prepend">
							<label class="remember" for="remember"><input type="checkbox" id="remember" />Remember me</label>
							</div>
							<div class="clearfix"></div>
 -->

							<p class="center span5">
							<button type="submit" class="btn btn-primary" onClick="return login();">Login</button>
							</p>
						</fieldset>
					</form>
				</div><!--/span-->
			</div><!--/row-->
				</div><!--/fluid-row-->
		
	</div><!--/.fluid-container-->
		
</body>
</html>
  
