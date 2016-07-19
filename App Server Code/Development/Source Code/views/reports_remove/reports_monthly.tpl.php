<!-- Content -->
<article class="container_12">

<section class="grid_12">
	<div class="block-border"><form class="block-content form" id="table_form" method="post" action="">
		<h1>Monthly & Ranking Reports</h1>
		
		<table class="table sortable no-margin" cellspacing="0" width="100%" id="reports_ranking">
		
			<thead>
				<tr>
					<!--<th class="black-cell"><span class="loading"></span></th>-->
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Sl. No.
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Month & Year
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Scanned On
					</th>
					<th scope="col">Monthly Report</th>
					<th scope="col">Ranking Report</th>
					
				</tr>
			</thead>
			
			<tbody>
			<?php if (count($outArray)>0){ ?>
				<?php for($i=0;$i<count($outArray);$i++){ 
					date_default_timezone_set('America/Detroit');
					$tempDate = new DateTime($outArray[$i]['rep_created_date']);
					$createdDate = date("m/d/Y", $tempDate->format('U'));
					$monthYear = date("M-Y", $tempDate->format('U'));
					$tempPrevouisDate = new DateTime($outArray[$i]['rep_previous_scanned_date']);
					$previousDate = date("m/d/Y", $tempPrevouisDate->format('U'));
				?>
				<tr>
					<!--<td class="th table-check-cell"><input type="checkbox" name="selected[]" id="table-selected-1" value="1"></td>-->
					<td><?php echo ($i+1) ?></td>
					<td><?php echo $monthYear; ?></td>
					<td><?php echo $createdDate; ?></td>
					<td><a href="<?php echo $outArray[$i]['monthly_link']; ?>">View/Download</a></td>
					<td><a href="<?php echo $outArray[$i]['ranking_link']; ?>">View/Download</a></td>
				</tr>
				<?php } ?>
				<?php } ?>
			</tbody>
		</table>
	</form></div>
</section>

<div class="clear"></div>
	
</article>
<!-- End content -->