<?php /*?><article class="container_12">
<div style="opacity: 1;" class="grey-bg clearfix" id="control-bar"><div class="container_12">
	
		<div class="float-left">
			<button onclick="history.go(-1);return false;" type="button"><img width="16" height="16" src="http://arapps.vziom.com/views/images/icons/fugue/navigation-180.png"> Back</button>
		</div>
		<div style="float:left; padding-top: 6px;text-align: center;width: 80%;"><h1 id="client_name"><?php echo isset($outArray[0]['u_first_name']) ? $outArray[0]['u_first_name'] : '';?></h1></div>
		<div class="float-right"> 
			<button disabled="disabled" class="red" type="button">Edit</button> 
		</div>
			
	</div>
</div>
<br /><?php */?>
<section class="grid_12">
	<div class="block-border"><form class="block-content form" id="simple_form" method="post" action="">
		<h1><?php echo isset($outArray[0]['username']) ? $outArray[0]['username'] : 'App User';?>'s Profile</h1>
     
		<fieldset>
			<div class="colx2-left">
				<?php $fname = isset($outArray[0]['first_name']) ? $outArray[0]['first_name'] : '';
				$lname = isset($outArray[0]['last_name']) ? $outArray[0]['last_name'] : ''; 
				?>
                <p><span><strong>First Name: </strong></span><span><?php echo $fname; ?></span></p>
                <p><span><strong>Last Name: </strong></span><span><?php echo $lname; ?></span></p>
				<?php /*?><p><span><strong>Name: </strong></span><span><?php echo $fname." ".$lname; ?></span></p><?php */?>
                <p><span><strong>User Name: </strong></span><span><?php echo isset($outArray[0]['username']) ? $outArray[0]['username'] : ''; ?></span></p>
                <p><span><strong>Group Name: </strong></span><span><?php echo isset($outArray[0]['name']) ? $outArray[0]['name'] : ''; ?></span></p>
			</div>
			<div class="colx2-right"><a href="<?php echo $config['LIVE_URL']; ?>appusers/profile/id/<?php echo isset($outArray[0]['id']) ? $outArray[0]['id'] : '';?>/edit"><button type="button" style="margin-left:40px;">Edit</button></a></div>
		</fieldset>

			
	</form></div>
</section>
</section>