<div id="control-bar" class="grey-bg clearfix" style="opacity: 1;"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="history.go(-1);return false;"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-180.png" width="16" height="16" > Back</button>
		</div>
        <div style="float:left; padding-top: 6px;text-align: center;width: 80%;"><h1 id="client_name">User tracking</h1></div>
		
			
	</div>
</div>

<div align="right">
	<form  id="table_form" method="post" action="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/products">
	From:<input type="text" id="from" name="from" value="<?php echo $start_date;?>">to:<input type="text" id="to" name="to" value="<?php echo $end_date;?>">&nbsp;&nbsp;&nbsp;<input type="submit" value="Apply">
	</form>
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
		<h1>Product Info</h1>
		
		<table class="table sortable no-margin" cellspacing="0" width="100%" id="cms_clients">
		
			<thead>
				<tr>
					<!--<th class="black-cell"><span class="loading"></span></th>-->
					<th scope="col">
						Client Name
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						No.of products 
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						No.of users 
					</th>
					<th style="display:none;">&nbsp;</th>
					<th style="display:none;">&nbsp;</th>
					
					
				</tr>
			</thead>
			
			<tbody>
				<?php for($i=0;$i<count($arrData);$i++){
					if(isset($arrData[$i]['client_image']) && $arrData[$i]['client_image']!=""){
							
							$dispProductImage=str_replace("{client_id}",$arrData[$i]['client_id'],$config['files']['logo']).$arrData[$i]['client_image'];
						}else{
							$dispProductImage=$config['LIVE_URL']."views/images/no-product.png";
						}
				?>
				<tr onclick="document.location = '<?php echo $config['LIVE_URL'];?>analytics/mobile_users/clients/<?php echo isset($arrData[$i]['client_id']) ? $arrData[$i]['client_id'] : ''; ?>/products/<?php echo $start_date;?>/<?php echo $end_date;?>';">
					<td><?php echo isset($arrData[$i]['client_name']) ? $arrData[$i]['client_name'] : ''; ?></td>
					<!-- <td style="text-align:center;"><img src="<?php echo $dispProductImage;?>" alt="<?php echo isset($arrData[$i]['product_name']) ? $arrData[$i]['product_name'] : ''; ?>" height="100"></td> -->
					<td><?php echo isset($arrData[$i]['no_of_products']) ? $arrData[$i]['no_of_products'] : ''; ?></td>
					<td><?php echo isset($arrData[$i]['no_of_users']) ? $arrData[$i]['no_of_users'] : 'N/A'; ?></td>
					
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

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script type="text/javascript">
  jQuery(document).ready(function($) {

   $( "#from" ).datepicker({
      defaultDate: "-2m",
	  maxDate: +0,
      dateFormat:"yy-mm-dd",
      changeMonth: true,
      numberOfMonths: 3
      
    });
    $( "#to" ).datepicker({
      //defaultDate: "+1w",
	  maxDate: +0,
        dateFormat:"yy-mm-dd",
      changeMonth: true,
      numberOfMonths: 3
      
    });
});
</script>