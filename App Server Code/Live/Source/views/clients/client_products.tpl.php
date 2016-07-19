<div id="control-bar" class="grey-bg clearfix" style="opacity: 1;"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="history.go(-1);return false;"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-180.png" width="16" height="16" > Back</button>
		</div>
        	<div style="float:left; padding-top: 6px;text-align: center;width: 80%;"><h1 id="client_name"><?php echo isset($outArrClientProducts[0]['pd_name']) ? $outArrClientProducts[0]['pd_name'] : ''; ?></h1></div>
		
		<div class="float-right"> 
			<button type="button" class="blue" onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $cid; ?>/products/add/'">Add Product</button> 
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
		<h1>Products</h1>
		
		<table class="table sortable no-margin" cellspacing="0" width="100%" id="cms_clients_products">
		
			<thead>
				<tr>
					<!--<th class="black-cell"><span class="loading"></span></th>-->
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Title
					</th>
					<th scope="col">
						Image
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Description
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Price
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Status
					</th>
					<th scope="col">Others</th>
					<th scope="col">Action</th>
					
				</tr>
			</thead>
			
			<tbody>
				<?php for($i=0;$i<count($outArrClientProducts);$i++) { 
					if(isset($outArrClientProducts[$i]['pd_status']) && $outArrClientProducts[$i]['pd_status']!=2)
					{
						if(isset($outArrClientProducts[$i]['pd_image']) && $outArrClientProducts[$i]['pd_image']!=""){
							
							$dispProductImage=str_replace("{client_id}",$outArrClientProducts[$i]['client_id'],$config['files']['products']).$outArrClientProducts[$i]['pd_image'];
						}else{
							$dispProductImage=$config['LIVE_URL']."views/images/no-product.png";
						}
						if ($outArrClientProducts[$i]['pd_status']=="1"){
							$dispClientProductStatus = "Active";
						}else{
							$dispClientProductStatus = "In active";
						}
						
						$price = isset($outArrClientProducts[$i]['pd_price']) ? $outArrClientProducts[$i]['pd_price']: '0'; 



				?>
					
				<tr>
					<!--<td class="th table-check-cell"><input type="checkbox" name="selected[]" id="table-selected-1" value="1"></td>-->
					<td><?php echo isset($outArrClientProducts[$i]['pd_name']) ? $outArrClientProducts[$i]['pd_name'] :''; ?></td>
					<td style="text-align:center;"><img src="<?php echo $dispProductImage; ?>" height="100"/></td>
					<td><?php echo isset($outArrClientProducts[$i]['pd_description']) ?$outArrClientProducts[$i]['pd_description'] : ''; ?></td>
					<td><?php echo "$".number_format((float)($price),2,'.','');?></td>
					<td><?php echo $dispClientProductStatus; ?></td>
					<td style="width:190px;">
						<button type="button" class="blue" onclick="modalRelatedProducts(<?php echo $outArrClientProducts[$i]['pd_id']; ?>,<?php echo $outArrClientProducts[$i]['client_id']; ?>); return false;">Related Products</button><br /><br />
						<!-- <button type="button" class="blue" onclick="modalViewOffers(<?php echo $outArrClientProducts[$i]['pd_id']; ?>,<?php echo $outArrClientProducts[$i]['client_id']; ?>); return false;">View Offers</button><br /><br /> -->
						<button type="button" class="blue"  onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientProducts[$i]['client_id']; ?>/products/<?php echo $outArrClientProducts[$i]['pd_id']; ?>/additional/'">Additional Media</button>
					</td>
					<td>
                    <a href="<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientProducts[$i]['client_id']; ?>/products/<?php echo $outArrClientProducts[$i]['pd_id']; ?>/view/" class="big-button" >View</a><br /><br />
                     <a href="<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientProducts[$i]['client_id']; ?>/products/<?php echo $outArrClientProducts[$i]['pd_id']; ?>/edit/" class="big-button" >Edit</a><br /><br />
                      
                      
                    <?php /*?><button type="button" class="blue" onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientProducts[$i]['client_id']; ?>/products/<?php echo $outArrClientProducts[$i]['id']; ?>/view/'">View</button><br /><br /><button type="button" class="blue" onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientProducts[$i]['client_id']; ?>/products/<?php echo $outArrClientProducts[$i]['id']; ?>/edit/'">Edit</button><br /><br /><?php */?><button type="button" class="blue" onclick="deleteProduct(<?php echo $outArrClientProducts[$i]['client_id']; ?>,<?php echo $outArrClientProducts[$i]['pd_id']; ?>)">Delete</button></td>
				</tr>
				<?php } } ?>
			</tbody>
		</table>
	</form></div>
</section>
<div id="tmp_holder"></div>
<div class="clear"></div>
	
</article>
<!-- End content -->