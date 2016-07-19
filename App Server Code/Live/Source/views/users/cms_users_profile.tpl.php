<div style="opacity: 1;" class="grey-bg clearfix" id="control-bar"><div class="container_12">
	
		<div class="float-left">
			<button onclick="history.go(-1);return false;" type="button"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-180.png" width="16" height="16" > Back</button>
		</div>
		
		<div class="float-right"> 
			<button type="button" class="blue" onclick="location.href='<?php echo $config['LIVE_URL']; ?>cmsusers/profile/id/<?php echo isset($outArray[0]['u_id']) ? $outArray[0]['u_id'] : ''?>/edit/'">Edit CMS User</button> 
		</div>
			
	</div>
</div>
<br />
<section class="grid_12">
	<div class="block-border"><form class="block-content form" id="simple_form" method="post" action="">
		
        <h1> CMS User's Profile</h1>
       
		<fieldset>
			<div class="colx2-left" style="width:25%;">
            <?php $fname = isset($outArray[0]['u_first_name']) ? $outArray[0]['u_first_name'] : '';
				$lname = isset($outArray[0]['u_last_name']) ? $outArray[0]['u_last_name'] : ''; 
				?>
              <img src="<?php echo $config['LIVE_URL']; ?>views/images/no-avatar.jpg" width="100%" />
            </div>
			<div class="colx2-right"  style="width:69%;">
            
            <table width="50%" border="1">
              <tr>
                <td height="37"><span><strong>Name: </strong></span></td>
                <td ><?php echo $fname." ".$lname; ?></td>
                
              </tr>
               <tr>
                <td height="37"><span><strong>Address 1: </strong></span><span></td>
                <td><?php echo isset($outArray[0]['u_address_1']) ? $outArray[0]['u_address_1'] : ''; ?></td>
              </tr>
               <tr>
                <td height="37"><span><strong>Address 2: </strong></span><span></td>
                <td><?php echo isset($outArray[0]['u_address_2']) ? $outArray[0]['u_address_2'] : '' ; ?></td>
              </tr>
               <tr>
                <td height="37"><span><strong>City: </strong></span><span></td>
                <td><?php echo isset($outArray[0]['u_city']) ? $outArray[0]['u_city'] : ''; ?></td>
              </tr>
               <tr>
                <td height="37"><span><strong>State: </strong></span><span></td>
                <td><?php echo isset($outArray[0]['u_state']) ? $outArray[0]['u_state'] : ''; ?></td>
              </tr>
               <tr>
                <td height="37"><span><strong>Country: </strong></span><span></td>
                <td><?php echo isset($outArray[0]['u_country']) ? $outArray[0]['u_country'] : ''; ?></td>
              </tr>
               <tr>
                <td height="37"><span><strong>Zip: </strong></span><span></td>
                <td><?php echo isset($outArray[0]['u_zip']) ? $outArray[0]['u_zip'] : ''; ?></td>
              </tr>
               <tr>
                <td height="37"><span><strong>Email Address: </strong></span><span></td>
                <td><?php echo isset($outArray[0]['u_email']) ? $outArray[0]['u_email'] : ''; ?></td>
              </tr>
               <tr>
                <td height="37"><span><strong>Contact Phone: </strong></span><span></td>
                <td><?php echo isset($outArray[0]['phone']) ? $outArray[0]['phone'] : ''; ?></td>
              </tr>
               <tr>
                <td height="37"><span><strong>Group: </strong></span><span></td>
                <td><?php echo isset($outArray[0]['group_name']) ? $outArray[0]['group_name'] : ''; ?></td>
              </tr>
               <tr>
                <td height="37"><span><strong>Name: </strong></span><span></td>
                <td><?php echo $fname." ".$lname; ?></td>
              </tr>
             
            </table>

            </div>
		</fieldset>

			
	</form></div>
</section>
</section>