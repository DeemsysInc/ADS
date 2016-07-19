<div id="control-bar" class="grey-bg clearfix" style="opacity: 1;"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="history.go(-1);return false;"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-180.png" width="16" height="16" > Back</button>
		</div>
		
		<?php /*?><div class="float-right"> 
			<button type="button" class="blue" onclick="location.href='<?php echo $config['LIVE_URL']; ?>cmsusers/add/'">Add CMS User</button> 
		</div><?php */?>
			
	</div>
</div>
	<!-- Content -->
	<article class="container_12">
		
<style type="text/css">
ul.simple-list li span{
float:right;
}
</style>

<section class="grid_12">
	<div class="block-border"><form class="block-content form" id="table_form" method="post" action="">
		<h1>App Users</h1>
		
		<table class="table sortable no-margin" cellspacing="0" width="100%" id="cms_clients_products">
		    <thead>
				<tr>
					<!--<th class="black-cell"><span class="loading"></span></th>-->
					<th scope="col">
						First Name
					</th>   
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Last Name
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						User Name
					</th>
                    <th scope="col">Email Id</th>
                    <th scope="col" style="display:none;">&nbsp;</th> 
                    <th scope="col" style="display:none;">&nbsp;</th>  
					<th scope="col" style="display:none;">&nbsp;</th>
				</tr>
			</thead>
			
			<tbody>
				<?php for($i=0;$i<count($outArray);$i++) { ?>
					
				<tr>
					<!--<td class="th table-check-cell"><input type="checkbox" name="selected[]" id="table-selected-1" value="1"></td>-->
					<td><?php echo isset($outArray[$i]['user_firstname']) ? $outArray[$i]['user_firstname'] :''; ?></td>
					<td><?php echo isset($outArray[$i]['user_lastname']) ? $outArray[$i]['user_lastname'] : ''; ?></td>
					<td><?php echo isset($outArray[$i]['user_username']) ? $outArray[$i]['user_username'] : ''; ?></td>
                    <td><?php echo isset($outArray[$i]['user_email_id']) ? $outArray[$i]['user_email_id'] : ''; ?></td>
                    <td style="display:none;">&nbsp;</td>
                    <td style="display:none;">&nbsp;</td>
                    <td style="display:none;">&nbsp;</td>
                   <?php /*?>  <td><button type="button" class="blue" onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArray[$i]['id']; ?>/'">View</button>&nbsp;&nbsp;<button type="button" class="blue"  onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArray[$i]['id']; ?>/edit/'">Edit</button></td><?php */?>
                    
               
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</form></div>
</section>

<div class="clear"></div>
	
</article>
<!-- End content -->