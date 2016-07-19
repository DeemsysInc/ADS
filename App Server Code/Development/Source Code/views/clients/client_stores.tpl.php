<div id="control-bar" class="grey-bg clearfix" style="opacity: 1;"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="history.go(-1);return false;"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-180.png" width="16" height="16" > Back</button>
		</div>
        	<div style="float:left; padding-top: 6px;text-align: center;width: 80%;"><h1 id="client_name"><?php echo isset($outArrClientStores[0]['pd_name']) ? $outArrClientStores[0]['pd_name'] : ''; ?></h1></div>
		
		<div class="float-right"> 
			<button type="button" class="blue" onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $cid; ?>/stores/add/'">Add Store</button> 
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
<?php /*?><div style="text-align: center;width: 100%;font-size:20px;"><h1 id="client_name"><?php echo isset($outArrClientProducts[0]['pd_name']) ? $outArrClientProducts[0]['pd_name'] : ''; ?></h1></div><?php */?>
<section class="grid_12">
	<div class="block-border"><form class="block-content form" id="table_form" method="post" action="">
		<table class="table sortable no-margin" cellspacing="0" width="100%" id="cms_clients_products">
		
			<thead>
				<tr>
					<!--<th class="black-cell"><span class="loading"></span></th>-->
					<th scope="col">Store name</th>
					<th scope="col">Store Code</th>
					<th scope="col">Latitude</th>
					<th scope="col">Longitude</th>
					<th scope="col">Search type</th>
					<th scope="col">Address</th>
					<th scope="col">Phone</th>
					<th scope="col">City</th>
					<th scope="col">State</th>
					<th scope="col">Zip</th>
					<th scope="col">Trigger threshold</th>
					<th scope="col">Update threshold</th>
					<th scope="col">Actions</th>
				</tr>
			</thead>
			
			<tbody>
				<?php for($i=0;$i<count($outArrClientStores);$i++) { ?>
					
				<tr>
					<td><?php echo isset($outArrClientStores[$i]['store_name']) ? $outArrClientStores[$i]['store_name'] :''; ?></td>
					<td><?php echo isset($outArrClientStores[$i]['store_code']) ? $outArrClientStores[$i]['store_code'] :''; ?></td>
					<td><?php echo isset($outArrClientStores[$i]['latitude']) ? $outArrClientStores[$i]['latitude'] :''; ?></td>
					<td><?php echo isset($outArrClientStores[$i]['longitude']) ? $outArrClientStores[$i]['longitude'] :''; ?></td>
					<td><?php echo isset($outArrClientStores[$i]['store_search_type']) ? $outArrClientStores[$i]['store_search_type'] :''; ?></td>
					<td><?php echo isset($outArrClientStores[$i]['address_1']) ? $outArrClientStores[$i]['address_1'] :''; ?></td>
					<td><?php echo isset($outArrClientStores[$i]['phone']) ? $outArrClientStores[$i]['phone'] :''; ?></td>
					<td><?php echo isset($outArrClientStores[$i]['city']) ? $outArrClientStores[$i]['city'] :''; ?></td>
					<td><?php echo isset($outArrClientStores[$i]['state']) ? $outArrClientStores[$i]['state'] :''; ?></td>
					<td><?php echo isset($outArrClientStores[$i]['zip']) ? $outArrClientStores[$i]['zip'] :''; ?></td>
					<td><?php echo isset($outArrClientStores[$i]['store_trigger_threshold']) ? $outArrClientStores[$i]['store_trigger_threshold'] :''; ?></td>
					<td><?php echo isset($outArrClientStores[$i]['store_update_threshold']) ? $outArrClientStores[$i]['store_update_threshold'] :''; ?></td>
					
					
					<td>
                    <button type="button" class="blue" onclick="modalRelatedStoreOffers(<?php echo $outArrClientStores[$i]['store_id']; ?>,<?php echo $outArrClientStores[$i]['client_id']; ?>); return false;">Related Offers</button><br /><br />                  
                    <a href="<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientStores[$i]['client_id']; ?>/stores/<?php echo $outArrClientStores[$i]['store_id']; ?>/edit/" class="big-button">Edit</a><br /><br />
                    <button type="button" class="blue" onclick="deleteStore(<?php echo $outArrClientStores[$i]['client_id']; ?>,<?php echo $outArrClientStores[$i]['store_id']; ?>);">Delete</button></td>
                    </td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</form></div>
</section>
<div id="tmp_holder"></div>
<div class="clear"></div>
	
</article>
<!-- End content -->