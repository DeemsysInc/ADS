<?php /*?><div id="control-bar" class="grey-bg clearfix" style="opacity: 1;"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="history.go(-1);return false;"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-180.png" width="16" height="16" > Back</button>
		</div>
		
		<div class="float-right"> 
			<button type="button" class="red">Delete Client</button> 
		</div>
			
	</div>
</div><?php */?>
<!-- Content -->
	<article class="container_12">
		
<style type="text/css">
ul.simple-list li span{
float:right;
}
</style>

<section class="grid_6">
	<div class="block-border"><div class="block-content" style="background-color:#CCC;">
		<h1>Recent Clients</h1>
		
		<ul class="extended-list no-margin icon-user">
			<?php for($i=0;$i<4;$i++) {?>
			<li>
				<a href="<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClients[$i]['id']; ?>/" style="padding-left: 0px;">
					<?php if($outArrClients[$i]['logo']!=""){
						
						$dispClient='<img src="'.str_replace("{client_id}",$outArrClients[$i]['id'],$config['files']['logo']).$outArrClients[$i]['logo'].'" height="40"/>';
					}else{
						$dispClient='<p style="width: 220px;font-size: 28px;text-align: center;">'.$outArrClients[$i]['name'].'</p>';
					}?>
					<span><?php echo $dispClient; ?></span>
				</a>
				<ul class="extended-options">
					<li>
                        <a href="<?php echo $config['LIVE_URL'];?>clients/id/<?php echo $outArrClients[$i]['id'];?>/products/" >
						<strong>Products Added</strong><br>
						<p style="text-align:center;font-size:20px;color:black;"><?php echo count($outArrProductsTot[$i]);?></p>
                        </a>
                        
					</li>
					<li>
                        <a href="<?php echo $config['LIVE_URL'];?>clients/id/<?php echo $outArrClients[$i]['id'];?>/triggers/" >
						<strong>Triggers Added</strong><br>
						<p style="text-align:center;font-size:20px;color:black;"><?php echo count($outArrTriggersTot[$i]);?></p>
                        </a>
					</li>
				</ul>
			</li>
			<?php } ?>
		</ul>
		
		<ul class="message no-margin">
			<li><a href="<?php echo $config['LIVE_URL']; ?>clients/">More Clients >></a></li>
		</ul>
	</div></div>
</section>
<section class="grid_6">
	<div class="block-border"><div class="block-content">
		<h1>Recent App Users</h1>
		
		<ul class="extended-list no-margin icon-user">
        
		<?php for($i=0;$i<4;$i++) {?>
			<li>
				<a href="<?php echo $config['LIVE_URL']; ?>appusers/profile/id/<?php echo isset($outArrAppUsers[$i]['user_id']) ? $outArrAppUsers[$i]['user_id']: ''; ?>/view/">
					<span class="icon"></span>
					<?php echo $outArrAppUsers[$i]['user_firstname']." ".$outArrAppUsers[$i]['user_lastname']?><br>
					<!--<small><b>Group:</b> <?php echo $outArrAppUsers[$i]['name']; ?></small>-->
				</a>
				
				<ul class="extended-options">
					<?php /*?><li>
						<button type="button" class="blue" onclick="location.href='<?php echo $config['LIVE_URL']; ?>appusers/wishlist/id/<?php echo $outArrAppUsers[$i]['id']; ?>/view/'">View Wishlist</button>
					</li><?php */?>
					<?php /*?><li>
						<button type="button" class="blue" onclick="location.href='<?php echo $config['LIVE_URL']; ?>appusers/profile/id/<?php echo $outArrAppUsers[$i]['id']; ?>/edit/'">Edit</button>
					</li><?php */?>
				</ul>
			</li>
			<?php } ?>
		</ul>
		
		<ul class="message no-margin">
			<li><a href="<?php echo $config['LIVE_URL']; ?>appusers/">more app users >></a></li>
		</ul>
	</div></div>
</section>
<?php /*
<section class="grid_12">
	<div class="block-border"><form class="block-content form" id="table_form" method="post" action="">
		<h1>Billing History</h1>
		
		<table class="table sortable no-margin" cellspacing="0" width="100%" id="ord_history">
		
			<thead>
				<tr>
					<!--<th class="black-cell"><span class="loading"></span></th>-->
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Purchase No.
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Transaction No.
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Item Name
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Purchased On
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Payment method
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Order Amount
					</th>
					<th scope="col">Action</th>
					
				</tr>
			</thead>
			
			<tbody>
				<?php for($i=0;$i<count($arrAllOrderDetails);$i++){ 
					$date = new DateTime($arrAllOrderDetails[$i]['od_payment_date'], new DateTimeZone('PST')); 
					date_default_timezone_set('America/Detroit'); 
				?>
				<tr>
					<!--<td class="th table-check-cell"><input type="checkbox" name="selected[]" id="table-selected-1" value="1"></td>-->
					<td><?php echo $arrAllOrderDetails[$i]['od_details_id']; ?></td>
					<td><?php echo $arrAllOrderDetails[$i]['od_transaction_id']; ?></td>
					<td><?php echo $arrAllOrderDetails[$i]['od_item_name']; ?></td>
					<td><?php echo date("m-d-Y h:i:s A", $date->format('U')); ?> EST</td>
					<td><?php echo $arrAllOrderDetails[$i]['od_payment_type']; ?></td>
					<td>$<?php echo $arrAllOrderDetails[$i]['od_item_price']; ?></td>
					<td><a href="<?php echo $config['LIVE_URL']; ?>invoices/<?php echo $arrAllOrderDetails[$i]['invoice_id'];?>/">View Invoice</a></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</form></div>
</section>
*/ ?>
<div class="clear"></div>
	
</article>
<!-- End content -->