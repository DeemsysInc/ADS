<div id="control-bar" class="grey-bg clearfix" style="opacity: 1;"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="history.go(-1);return false;"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-180.png" width="16" height="16" > Back</button>
		</div>
        <div style="float:left; padding-top: 6px;text-align: center;width: 80%;"><h1 id="client_name">User tracking Data</h1></div>
		
			
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
		<h1>Offer Info</h1>
		
		<table class="table sortable no-margin" cellspacing="0" width="100%" id="cms_clients">
		
			<thead>
				<tr>
					<!--<th class="black-cell"><span class="loading"></span></th>-->
					<th scope="col">
						Offer Name
					</th>
					
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Offer Image
					</th>
					<th>Views</th>
					<th style="display:none;">&nbsp;</th>
					<th style="display:none;">&nbsp;</th>
					<th style="display:none;">&nbsp;</th>
					<th style="display:none;">&nbsp;</th>
					<th style="display:none;">&nbsp;</th>
					<th style="display:none;">&nbsp;</th>
					<th style="display:none;">&nbsp;</th>
					<th style="display:none;">&nbsp;</th>
					<th style="display:none;">&nbsp;</th>
					
				</tr>
			</thead>
			
			<tbody>
				<?php for($i=0;$i<count($arrData);$i++){
				if(isset($arrData[$i]['offer_image']) && $arrData[$i]['offer_image']!=""){
							
							$dispProductImage=str_replace("{client_id}",$arrData[$i]['client_id'],$config['files']['products']).$arrData[$i]['offer_image'];
						}else{
							$dispProductImage=$config['LIVE_URL']."views/images/no-product.png";
						}
				?>
				<tr>
					<td><?php echo isset($arrData[$i]['offer_name']) ? $arrData[$i]['offer_name'] : ''; ?></td>
					<td style="text-align:center;"><img src="<?php echo $dispProductImage;?>" alt="<?php echo isset($arrData[$i]['offer_name']) ? $arrData[$i]['offer_name'] : ''; ?>" height="100"></td>
					<td><?php echo isset($arrData[$i]['offer_views']) ? $arrData[$i]['offer_views'] : '0'; ?></td>
					<td style="display:none;">&nbsp;</td>
					<td style="display:none;">&nbsp;</td>
					<td style="display:none;">&nbsp;</td>
					<td style="display:none;">&nbsp;</td>
					<td style="display:none;">&nbsp;</td>
					<td style="display:none;">&nbsp;</td>
					<td style="display:none;">&nbsp;</td>
					<td style="display:none;">&nbsp;</td>
					<td style="display:none;">&nbsp;</td>
					
               
				</tr>
				<?php }?>
			</tbody>
		</table>
	</form></div>
</section>


<div class="clear"></div>
	
</article>
<!-- End content -->

