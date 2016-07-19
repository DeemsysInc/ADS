<div id="control-bar" class="grey-bg clearfix" style="opacity: 1;"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="history.go(-1);return false;"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-180.png" width="16" height="16" > Back</button>
		</div>
		<?php /*?><div style="float:left; padding-top: 6px;text-align: center;width: 80%;"><h1 id="client_name"><?php echo isset($outArrClient[0]['name']) ? $outArrClient[0]['name'] : ''; ?></h1></div><?php */?>
		<div class="float-right"> 
			<button type="button" class="blue" onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $cid; ?>/triggers/add/'">Add Trigger</button> 
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
<div style="text-align: center;width: 100%;font-size:20px;"><h1 id="client_name"><?php echo isset($outArrClientTriggers[0]['name']) ? $outArrClientTriggers[0]['name'] : ''; ?></h1></div>
<section class="grid_12">
	<div class="block-border"><form class="block-content form" id="table_form" method="post" action="">
		<h1>Triggers</h1>
		
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
						Height
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Width
					</th>
                    <th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Instruction
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Status
					</th>
					<?php /*?><th scope="col">Others</th><?php */?>
					<th scope="col">Action</th>
					
				</tr>
			</thead>
			
			<tbody>
				<?php for($i=0;$i<count($outArrClientTriggers);$i++) { 
					if(isset($outArrClientTriggers[$i]['active']) && $outArrClientTriggers[$i]['active']!=2)
					{
						if($outArrClientTriggers[$i]['url']!=""){
							
							$dispTriggerImage=str_replace("{client_id}",$outArrClientTriggers[$i]['client_id'],$config['files']['triggers']).$outArrClientTriggers[$i]['url'];
						}else{
							$dispTriggerImage=$config['LIVE_URL']."views/images/no-product.png";
						}
						if ($outArrClientTriggers[$i]['active']=="1"){
							$dispClientTriggerStatus = "Active";
						}else{
							$dispClientTriggerStatus = "In active";
						}
				?>
					
				<tr>
					<!--<td class="th table-check-cell"><input type="checkbox" name="selected[]" id="table-selected-1" value="1"></td>-->
					<td><?php echo $outArrClientTriggers[$i]['title']; ?></td>
					<td style="text-align:center;"><img src="<?php echo $dispTriggerImage; ?>" height="100"/></td>
					<td><?php echo $outArrClientTriggers[$i]['height']; ?></td>
					<td><?php echo $outArrClientTriggers[$i]['width']; ?></td>
                    <td><?php echo $outArrClientTriggers[$i]['instruction']; ?></td>
					<td><?php echo $dispClientTriggerStatus; ?></td>
					
					<td>
                                       
                    <a href="<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientTriggers[$i]['client_id']; ?>/triggers/<?php echo $outArrClientTriggers[$i]['id']; ?>/view/" class="big-button">View</a>&nbsp;&nbsp;
                    <a href="<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientTriggers[$i]['client_id']; ?>/triggers/<?php echo $outArrClientTriggers[$i]['id']; ?>/edit/" class="big-button">Edit</a><br /><br />
                    <a href="<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientTriggers[$i]['client_id']; ?>/triggers/<?php echo $outArrClientTriggers[$i]['id']; ?>/visuals/" class="big-button">Visuals</a>&nbsp;&nbsp;
                    <button type="button" class="blue" onclick="deleteTrigger(<?php echo $outArrClientTriggers[$i]['client_id']; ?>,<?php echo $outArrClientTriggers[$i]['id']; ?>);">Delete</button></td>
                    <?php /*?><button type="button" class="blue" onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientTriggers[$i]['client_id']; ?>/triggers/<?php echo $outArrClientTriggers[$i]['id']; ?>/view/'">View</button><br /><br /><button type="button" class="blue" onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientTriggers[$i]['client_id']; ?>/triggers/<?php echo $outArrClientTriggers[$i]['id']; ?>/edit/'">Edit</button><br /><br /><button type="button" class="blue" onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientTriggers[$i]['client_id']; ?>/triggers/<?php echo $outArrClientTriggers[$i]['id']; ?>/visuals/'">Visuals</button><br /><br /><button type="button" class="blue" onclick="deleteTrigger(<?php echo $outArrClientTriggers[$i]['client_id']; ?>,<?php echo $outArrClientTriggers[$i]['id']; ?>);">Delete</button><?php */?></td>
				</tr>
				<?php } }?>
			</tbody>
		</table>
	</form></div>
</section>
<div id="tmp_holder"></div>
<div class="clear"></div>
	
</article>
<!-- End content -->