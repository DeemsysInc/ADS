<div id="control-bar" class="grey-bg clearfix" style="opacity: 1;"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="history.go(-1);return false;"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-180.png" width="16" height="16" > Back</button>
		</div>
		
		<div class="float-right"> 
			<button type="button" class="blue" onclick="location.href='<?php echo $config['LIVE_URL']; ?>cmsusers/add/'">Add CMS User</button> 
		</div>
			
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
		<h1>CMS Users</h1>
		
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
                    <th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Email
					</th>
                    <th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Group
					</th>
                    <th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Phone
					</th>
                    
					<th scope="col">Action</th>
                    <th scope="col" style="display:none;">&nbsp;</th> 
                    <th scope="col" style="display:none;">&nbsp;</th>  
					
				</tr>
			</thead>
			
			<tbody>
				<?php for($i=0;$i<count($outArray);$i++) { ?>
					
				<tr>
					<!--<td class="th table-check-cell"><input type="checkbox" name="selected[]" id="table-selected-1" value="1"></td>-->
					<td><?php echo $outArray[$i]['u_first_name']; ?></td>
					<td><?php echo $outArray[$i]['u_last_name']; ?></td>
					<td><?php echo $outArray[$i]['u_uname']; ?></td>
                    <td><?php echo $outArray[$i]['u_email']; ?></td>
                    <td><?php echo $outArray[$i]['group_name']; ?></td>
                    <td><?php echo $outArray[$i]['phone']; ?></td>
					<td><button type="button" class="blue" onclick="location.href='<?php echo $config['LIVE_URL']; ?>cmsusers/profile/id/<?php echo $outArray[$i]['u_id']; ?>/view/'" >View</button>&nbsp;&nbsp;<button type="button" class="blue" onclick="location.href='<?php echo $config['LIVE_URL']; ?>cmsusers/profile/id/<?php echo $outArray[$i]['u_id']; ?>/edit/'" >Edit</button>&nbsp;&nbsp;<button type="button" class="blue" onclick="deleteCmsUser(<?php echo $outArray[$i]['u_id']; ?>);" >Delete</button></td>
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