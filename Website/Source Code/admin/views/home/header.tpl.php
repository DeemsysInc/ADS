<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Seemore admin</title>
<script type="text/javascript">var rootUrl = "<?php echo $config['LIVE_ADMIN_URL']?>";</script>

<script>
	var root_url = "<?php echo $config['LIVE_URL']?>";
	
</script>


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
	<link rel="shortcut icon" href="<?php echo $config['LIVE_ADMIN_URL']?>views/img/favicon.ico">

</head>
<body>
<div class="navbar">
		<div class="navbar-inner">
			<div class="container-fluid">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".top-nav.nav-collapse,.sidebar-nav.nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<a class="brand" href="<?php echo $config['LIVE_ADMIN_URL']?>"><img alt="Seemore Logo" src="<?php echo $config['LIVE_ADMIN_URL']?>views/img/seemore-logo-hi.png" /></a>
				
				<!-- user dropdown starts -->
				<div class="btn-group pull-right" >
					<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
						<i class="icon-user"></i><span class="hidden-phone"> <?php echo $_SESSION['admin_user_name']; ?></span>
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li><a href="<?php echo $config['LIVE_ADMIN_URL']?>users/edit/<?php echo $_SESSION['admin_user_id']; ?>">Edit My Profile</a></li>
						<li class="divider"></li>
						<li><a href="<?php echo $config['LIVE_ADMIN_URL']?>includes/ajax.php?action=logout" class="logout">Logout</a></li>
					</ul>
					
   
				</div>
				<!-- user dropdown ends -->
				
				<div class="top-nav nav-collapse">
					<ul class="nav">
						<li><a href="<?php echo $config['LIVE_URL']?>" target="_blank">Visit Site</a></li>
						<li>
							<form class="navbar-search pull-left">
								<input placeholder="Search" class="search-query span2" name="query" type="text">
							</form>
						</li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</div>
	</div>
	<!-- topbar ends -->
		<div class="container-fluid">
		<div class="row-fluid">
				
			<!-- left menu starts -->
			<div class="span2 main-menu-span">
				<div class="well nav-collapse sidebar-nav">
					<ul class="nav nav-tabs nav-stacked main-menu">
						<li class="nav-header hidden-tablet">Main</li>
						<li><a class="ajax-link" href="<?php echo $config['LIVE_ADMIN_URL']?>"><i class="icon-home"></i><span class="hidden-tablet"> Dashboard</span></a></li>
					        <li class="nav-header hidden-tablet">Users</li>
						<li><a class="ajax-link" href="<?php echo $config['LIVE_ADMIN_URL']?>users"><i class="icon-user"></i><span class="hidden-tablet"> All users</span></a></li>
						<li><a class="ajax-link" href="<?php echo $config['LIVE_ADMIN_URL']?>users/add"><i class="icon-user"></i><span class="hidden-tablet"> Add New</span></a></li>
						
						<li class="nav-header hidden-tablet">Press Releases</li>
					        <li><a class="ajax-link" href="<?php echo $config['LIVE_ADMIN_URL']?>press"><i class="icon-edit"></i><span class="hidden-tablet"> Press List</span></a></li>
						<li><a class="ajax-link" href="<?php echo $config['LIVE_ADMIN_URL']?>press/create"><i class="icon-edit"></i><span class="hidden-tablet"> Create Press</span></a></li>
						<li class="nav-header hidden-tablet">News</li>
						<li><a class="ajax-link" href="<?php echo $config['LIVE_ADMIN_URL']?>news"><i class="icon-edit"></i><span class="hidden-tablet"> News List</span></a></li>
						<li><a class="ajax-link" href="<?php echo $config['LIVE_ADMIN_URL']?>news/create"><i class="icon-edit"></i><span class="hidden-tablet"> Create News</span></a></li>
						
						
					</ul>
					
				</div><!--/.well -->
			</div><!--/span-->
			<!-- left menu ends -->
			
			<noscript>
				<div class="alert alert-block span10">
					<h4 class="alert-heading">Warning!</h4>
					<p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a> enabled to use this site.</p>
				</div>
			</noscript>
			
                  