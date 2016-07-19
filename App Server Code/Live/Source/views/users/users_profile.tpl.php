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
		<?php if($_SESSION['user_id']==isset($outArray[0]['u_id'])){?>
        <h1>My Profile</h1>
        <?php }else{?>
        <h1> User Profile</h1>
        <?php }?>
		<fieldset>
			<div class="colx2-left">
				<?php $fname = isset($outArray[0]['u_first_name']) ? $outArray[0]['u_first_name'] : '';
				$lname = isset($outArray[0]['u_last_name']) ? $outArray[0]['u_last_name'] : ''; 
				?>
				<p><span><strong>Name: </strong></span><span><?php echo $fname." ".$lname; ?></span></p>
				<p><span><strong>Address 1: </strong></span><span><?php echo isset($outArray[0]['u_address_1']) ? $outArray[0]['u_address_1'] : ''; ?></span></p>
				<p><span><strong>Address 2: </strong></span><span><?php echo isset($outArray[0]['u_address_2']) ? $outArray[0]['u_address_2'] : '' ; ?></span></p>
				<p><span><strong>City: </strong></span><span><?php echo isset($outArray[0]['u_city']) ? $outArray[0]['u_city'] : ''; ?></span></p>
				<p><span><strong>State: </strong></span><span><?php echo isset($outArray[0]['u_state']) ? $outArray[0]['u_state'] : ''; ?></span></p>
				<p><span><strong>Country: </strong></span><span><?php echo isset($outArray[0]['u_country']) ? $outArray[0]['u_country'] : ''; ?></span></p>
				<p><span><strong>Zip: </strong></span><span><?php echo isset($outArray[0]['u_zip']) ? $outArray[0]['u_zip'] : ''; ?></span></p>
				<p><span><strong>Email Address: </strong></span><span><?php echo isset($outArray[0]['u_email']) ? $outArray[0]['u_email'] : ''; ?></span></p>
				<p><span><strong>Contact Phone: </strong></span><span><?php echo isset($outArray[0]['phone']) ? $outArray[0]['phone'] : ''; ?></span></p>
				
			</div>
			<div class="colx2-right"><a href="<?php echo $config['LIVE_URL']; ?>users/profile/edit"><button type="button" style="margin-left:40px;">Edit</button></a></div>
		</fieldset>

			
	</form></div>
</section>
</section>