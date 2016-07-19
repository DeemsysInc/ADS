<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pl" xml:lang="pl">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Seemore CMS | Control Panel</title>

	<script type="text/javascript">var root_url = "<?php echo $config['LIVE_URL']; ?>" </script>
	
	<!-- Global stylesheets -->
	<link href="<?php echo $config['LIVE_URL']; ?>views/css/reset.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $config['LIVE_URL']; ?>views/css/common.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $config['LIVE_URL']; ?>views/css/form.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $config['LIVE_URL']; ?>views/css/standard-alt.css" rel="stylesheet" type="text/css">
	
	<!-- Comment/uncomment one of these files to toggle between fixed and fluid layout -->
	<!--<link href="<?php echo $config['LIVE_URL']; ?>views/css/960.gs.css" rel="stylesheet" type="text/css">-->
	<link href="<?php echo $config['LIVE_URL']; ?>views/css/960.gs.fluid.css" rel="stylesheet" type="text/css">
	
	<!-- Custom styles -->
	<link href="<?php echo $config['LIVE_URL']; ?>views/css/simple-lists.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $config['LIVE_URL']; ?>views/css/block-lists.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $config['LIVE_URL']; ?>views/css/planning.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $config['LIVE_URL']; ?>views/css/table.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $config['LIVE_URL']; ?>views/css/calendars.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $config['LIVE_URL']; ?>views/css/wizard.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $config['LIVE_URL']; ?>views/css/gallery.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $config['LIVE_URL']; ?>views/css/jquery.minicolors.css" rel="stylesheet" type="text/css">

	
	<!-- Favicon -->
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
	<link rel="icon" type="image/png" href="favicon-large.png">
	
	<!-- Modernizr for support detection, all javascript libs are moved right above </body> for better performance -->
	<script src="<?php echo $config['LIVE_URL']; ?>views/js/libs/modernizr.custom.min.js"></script>
    <!-- Jcaraousel css-->
    <link rel="stylesheet" type="text/css" href="<?php echo $config['LIVE_URL']; ?>plugins/jsor-jcarousel-8e3df57/skins/tango/skin.css" />

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">

	
</head>

<body>
	
	<!-- Header -->
	
	<!-- Server status -->
	<header><div class="container_12">
		
		<div class="server-info"><a href="#">Main site</a></div>
		
	</div></header>
	<!-- End server status -->
	
	
	<!-- Logo section -->
	<div id="header-bg">
		<a href="<?php echo $config['LIVE_URL']; ?>" alt="Seemore CMS Control Panel" border="none"><img src="<?php echo $config['LIVE_URL']; ?>views/images/logo.png" ></a>
	</div>
	
	<!-- Sub nav -->
	<div id="sub-nav"><div class="container_12">
		
		<ul>
		<?php if(isset($_SESSION['user_group']) && $_SESSION['user_group']=='3'){?>
		
		<li id="dashboard"><a href="<?php echo $config['LIVE_URL']; ?>" title="Dashboard">Dashboard</a></li>
			
		<!--<li  id="analytics"><a href="<?php echo $config['LIVE_URL']; ?>analytics/dashboard" title="Analytics">Analytics</a></li>-->
		 
		
		<li class="menu-opener" id="analytics"><a href="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/" title="Analytics">Mobile Analytics</a>
			<div class="menu-arrow">
					<img src="<?php echo $config['LIVE_URL']; ?>views/images/menu-open-arrow.png" width="16" height="16">
				</div>
				<div class="menu">
					<ul>
						<li class="icon_newspaper "><a href="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/client_products">By Product</a></li>
						<li class="icon_newspaper "><a href="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/client_offers">By Offer</a></li>
					</ul>
				</div>
			</li> 
		<?php }else {?>	
		   <!-- <li id="dashboard"><a href="<?php echo $config['LIVE_URL']; ?>" title="Dashboard">Dashboard</a></li>-->
			<!--<li class="menu-opener" id="analytics"><a href="<?php echo $config['LIVE_URL']; ?>analytics/dashboard" title="Analytics">Analytics</a>
			<div class="menu-arrow">
					<img src="<?php echo $config['LIVE_URL']; ?>views/images/menu-open-arrow.png" width="16" height="16">
				</div>
				<div class="menu">
					<ul>
						<li class="icon_newspaper "><a href="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/products">By Product</a></li>
						<li class="icon_newspaper "><a href="#">By Offer</a></li>
					</ul>
				</div>
			</li>-->
			<li class="menu-opener" id="analytics"><a href="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/" title="Analytics">Mobile Analytics</a>
			<div class="menu-arrow">
					<img src="<?php echo $config['LIVE_URL']; ?>views/images/menu-open-arrow.png" width="16" height="16">
				</div>
				<div class="menu">
					<ul>
						<li class="icon_newspaper "><a href="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/client_products">By Product</a></li>
						<li class="icon_newspaper "><a href="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/client_offers">By Offer</a></li>
					</ul>
				</div>
			</li>
			<li id="clients"><a href="<?php echo $config['LIVE_URL']; ?>clients/" title="Clients">Clients</a></li>
			<li id="clientgroups"><a href="<?php echo $config['LIVE_URL']; ?>client_groups/" title="Client groups">Client Groups</a></li>
			<li class="menu-opener" id="users"><a href="<?php echo $config['LIVE_URL']; ?>cmsusers/profile/" title="My Profile">My Profile</a>
				<div class="menu-arrow">
					<img src="<?php echo $config['LIVE_URL']; ?>views/images/menu-open-arrow.png" width="16" height="16">
				</div>
				<div class="menu">
					<ul>
						<li class="icon_newspaper "><a href="<?php echo $config['LIVE_URL']; ?>cmsusers/profile/password">Change Password</a></li>
					</ul>
				</div>
			</li>
			<!--<li class="menu-opener" id="cmsusers"><a href="<?php echo $config['LIVE_URL']; ?>cmsusers/" title="CMS Users">CMS User Management</a>
				<div class="menu-arrow">
					<img src="<?php echo $config['LIVE_URL']; ?>views/images/menu-open-arrow.png" width="16" height="16">
				</div>
				<div class="menu">
					<ul>
						<li class="icon_newspaper "><a href="<?php echo $config['LIVE_URL']; ?>cmsusers/">View All</a></li>
                        <li class="icon_newspaper "><a href="<?php echo $config['LIVE_URL']; ?>cmsusers/add/">Add New</a></li>
						<?php /*?><li class="icon_newspaper "><a href="<?php echo $config['LIVE_URL']; ?>cmsusers/cms/wishlist">Wishlist</a></li><?php */?>
					</ul>
				</div>
			</li>
            
            <li class="menu-opener" id="appusers"><a href="<?php echo $config['LIVE_URL']; ?>appusers/" title="App Users">App User Management</a>
				<div class="menu-arrow">
					<img src="<?php echo $config['LIVE_URL']; ?>views/images/menu-open-arrow.png" width="16" height="16">
				</div>
				<div class="menu">
					<ul>
						<li class="icon_newspaper "><a href="<?php echo $config['LIVE_URL']; ?>appusers/">View All</a></li>
                        <?php /*?><li class="icon_newspaper "><a href="<?php echo $config['LIVE_URL']; ?>appusers/add/">Add New</a></li><?php */?>
					<?php /*?>	<li class="icon_newspaper "><a href="<?php echo $config['LIVE_URL']; ?>appusers/wishlist">Wishlist</a></li><?php */?>
					</ul>
				</div>
			</li>
            <li id="Creator"><a href="<?php echo $config['LIVE_URL']; ?>creator/" title="Creator">Creator</a></li>
			-->
            <?php }?>
		</ul>
		
	</div></div>
	<!-- End sub nav -->	
	
	
	<!-- Status bar -->
	<div id="status-bar"><div class="container_12">
	
		<ul id="status-infos">
			<li class="spaced">Logged as: <strong><?php echo $outUserInfo['fname']." ".$outUserInfo['lname'];?></strong></li>
			<li><a href="javascript:void(0);" class="button red" title="Logout" onclick="doLogout();"><span class="smaller">LOGOUT</span></a></li>
		</ul>
		
		<!-- v1.5: you can now add class red to the breadcrumb -->
		<ul id="breadcrumb">
			<li><a href="<?php echo $config['LIVE_URL']; ?>" title="Home">Home</a></li>
			<li><a href="<?php echo $config['LIVE_URL']; ?>" title="Dashboard">Dashboard</a></li>
		</ul>
	
	</div></div>
	<!-- End status bar -->
	
	<div id="header-shadow"></div>
	<!-- End header -->
	<?php /*
	<!-- Always visible control bar -->
	<div id="control-bar" class="grey-bg clearfix"><div class="container_12">
	
		<div class="float-left">
			<button type="button"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-180.png" width="16" height="16"> Back to list</button>
		</div>
		
		<div class="float-right"> 
			<button type="button" disabled="disabled">Disabled</button>
			<button type="button" class="red">Cancel</button> 
			<button type="button" class="grey">Reset</button> 
			<button type="button"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/tick-circle.png" width="16" height="16"> Save</button>
		</div>
			
	</div></div>
	<!-- End control bar -->
	*/ ?>
		<!-- Content -->
	<article class="container_12">
		
