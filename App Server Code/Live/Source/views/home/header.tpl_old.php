<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pl" xml:lang="pl">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Vziom | Sales Admin Panel</title>
<link rel="stylesheet" type="text/css" href="<?php echo $config['LIVE_URL']; ?>views/css/style.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo $config['LIVE_URL']; ?>views/css/navi.css" media="screen" />
<script type="text/javascript">var root_url = "<?php echo $config['LIVE_URL']; ?>" </script>
<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/common.js"></script>
<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/saleslogin.js"></script>
<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/rates.js"></script>
<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/users.js"></script>
<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/calc.js"></script>
<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/jquery-1.7.2.min.js"></script>
<style type="text/css" title="currentStyle">
	@import "<?php echo $config['LIVE_URL']; ?>plugins/data_tables/media/css/demo_table.css";
	@import "<?php echo $config['LIVE_URL']; ?>plugins/data_tables/editable-1.3/media/css/demo_validation.css";
	@import "<?php echo $config['LIVE_URL']; ?>plugins/data_tables/editable-1.3/media/css/themes/base/jquery-ui.css";
	@import "<?php echo $config['LIVE_URL']; ?>plugins/data_tables/editable-1.3/media/css/themes/smoothness/jquery-ui-1.7.2.custom.css";
</style>
<script type="text/javascript" language="javascript" src="<?php echo $config['LIVE_URL']; ?>plugins/data_tables/media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo $config['LIVE_URL']; ?>plugins/data_tables/examples/examples_support/jquery.jeditable.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo $config['LIVE_URL']; ?>plugins/data_tables/media/js/jquery.dataTables.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>plugins/data_tables/editable-1.3/media/js/jquery-ui.js" type="text/javascript"></script>
<script src="<?php echo $config['LIVE_URL']; ?>plugins/data_tables/editable-1.3/media/js/jquery.validate.js" type="text/javascript"></script>
<script src="<?php echo $config['LIVE_URL']; ?>plugins/data_tables/editable-1.3/media/js/jquery.dataTables.editable.js" type="text/javascript"></script>

		
<script type="text/javascript">
$(function(){
	$(".box .h_title").not(this).next("ul").hide("normal");
	$(".box .h_title").not(this).next("#home").show("normal");
	$(".box").children(".h_title").click( function() { $(this).next("ul").slideToggle(); });
	
});

</script>
</head>
<body>
<div class="wrap">
	<div id="header">
		<div id="top">
			<div class="left">
				<p>Welcome, <strong><?php echo $outUserInfo['fname']." ".$outUserInfo['lname'];?>.</strong> [ <a href="javascript:void(0);" onclick="doLogout();">logout</a> ]</p>
			</div>
			<div class="right">
				<div class="align-right">
					<p>Last login: <strong><?php echo $outUserInfo['last_login'];?></strong></p>
				</div>
			</div>
		</div>
		<div id="nav">
			<ul>
			<?php if ($outUserInfo['user_group']==3){ ?>
				<li class="upp"><a href="<?php echo $config['LIVE_URL']; ?>">Dashboard</a>
				</li>
				<li class="upp"><a href="<?php echo $config['LIVE_URL']; ?>packages/">My Packages</a>
				</li>
			<?php } ?>
				<?php if (($outUserInfo['user_group']==1) || ($outUserInfo['user_group']==2)){ ?>
				<li class="upp"><a href="#">Manage Users</a>
					<ul>
						<li>&#8250; <a href="<?php echo $config['LIVE_URL']; ?>users/">All Users</a></li>
						<li>&#8250; <a href="<?php echo $config['LIVE_URL']; ?>users/add">Add User</a></li>
					</ul>
				</li>
				<?php } ?>
				<?php /*
				<li class="upp"><a href="#">Users</a>
					<ul>
						<li>&#8250; <a href="">Show all uses</a></li>
						<li>&#8250; <a href="">Add new user</a></li>
						<li>&#8250; <a href="">Lock users</a></li>
					</ul>
				</li>
				<li class="upp"><a href="#">Settings</a>
					<ul>
						<li>&#8250; <a href="">Site configuration</a></li>
						<li>&#8250; <a href="">Contact Form</a></li>
					</ul>
				</li>
				*/ ?>
			</ul>
		</div>
	</div>
	<div id="content">