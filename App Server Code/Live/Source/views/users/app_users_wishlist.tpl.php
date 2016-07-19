	<!-- Content -->
	<article class="container_12">
		
<style type="text/css">
ul.simple-list li span{
float:right;
}
</style>

<section class="grid_12">
	<div class="block-border"><form class="block-content form" id="table_form" method="post" action="">
		<h1><?php echo isset($outArray[0]['username']) ? $outArray[0]['username'] : 'App User'; ?>'s Wishlists</h1>
		
		<table class="table sortable no-margin" cellspacing="0" width="100%" id="cms_clients">
		
			<thead>
				<tr>
					<!--<th class="black-cell"><span class="loading"></span></th>-->
					<th scope="col">
						Product Name
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Product Image
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Name
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Shared
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Clinent
					</th>
					<th scope="col">Action</th>
					
				</tr>
			</thead>
			
			<tbody>
				<?php for($i=0;$i<count($outArray);$i++) { ?>
					
				<tr>
					<!--<td class="th table-check-cell"><input type="checkbox" name="selected[]" id="table-selected-1" value="1"></td>-->
					<td><?php echo $outArray[$i]['title']; ?></td>
					<td style="background:#CCC;border-bottom: 1px solid #fff;text-align:center"><img width="250px" src="<?php echo str_replace("{client_id}",$outArray[$i]['client_id'],$config['files']['products']).$outArray[$i]['image']; ?>" alt="<?php echo $outArray[$i]['image']; ?>"  /></td>
					<td><?php echo $outArray[$i]['name']; ?></td>
					<td><?php echo $outArray[$i]['shared']; ?></td>
					<td><?php echo $outArray[$i]['client_name']; ?></td>
					<td><button type="button" class="blue">View</button>&nbsp;&nbsp;<button type="button" class="blue" >Edit</button></td>
                    
               
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</form></div>
</section>

<div class="clear"></div>
	
</article>
<!-- End content -->