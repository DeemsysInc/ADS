<div id="control-bar" class="grey-bg clearfix" style="opacity: 1;"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="history.go(-1);return false;"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-180.png" width="16" height="16" > Back</button>
		</div>
        <div style="float:left; padding-top: 6px;text-align: center;width: 80%;"><h1 id="client_name">Client groups</h1></div>
		<div class="float-right"> 
			<button type="button" >Add Client group</button> 
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
		<h1>Client groups</h1>
		<table class="table sortable no-margin" cellspacing="0" width="100%" id="cms_clients">
		
			<thead>
				<tr>
					<!--<th class="black-cell"><span class="loading"></span></th>-->
					<th scope="col">
						Image
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Name
					</th>
					<th scope="col">Clinets</th>
					
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Status
					</th>
					<th scope="col">Action</th>
					<th style="display:none;">Website</th>
					
				</tr>
			</thead>
			
			<tbody>
				<?php for($i=0;$i<count($outArrAllClientGroups);$i++) { 
					if(isset($outArrAllClientGroups[$i]['active']) && $outArrAllClientGroups[$i]['active']!=2)
					{
						if($outArrAllClientGroups[$i]['image']!=""){
							 $filepath=$config['files']['all_clients_markers'].$outArrAllClientGroups[$i]['image'];
							 $dispClient='<img src="'.$filepath.'" height="100"/>';
						}else{
							$dispClient='<p style="font-size: 28px;text-align: center;">'.$outArrAllClientGroups[$i]['group_name'].'</p>';
						}
						if ($outArrAllClientGroups[$i]['active']=="1"){
							$dispClientStatus = "Active";
						}else{
							$dispClientStatus = "Not Active";
						}
				?>
					
				<tr>
					<!--<td class="th table-check-cell"><input type="checkbox" name="selected[]" id="table-selected-1" value="1"></td>-->
					<td style="background:#CCC;border-bottom: 1px solid #fff;text-align:center;"><a href="<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrAllClients[$i]['id']; ?>/"><?php echo $dispClient; ?></a></td>
					<td><?php echo $outArrAllClientGroups[$i]['group_name']; ?></td>
					<td><?php echo $outArrAllClientGroups[$i]['client_id']; ?></td>
					<td><?php echo $dispClientStatus; ?></td>
					<td>
                    <a href="#" class="big-button">View</a>&nbsp;&nbsp;
                    <a href="#" class="big-button">Edit</a>&nbsp;&nbsp;
                   
                    <?php /*?><button type="button" class="blue" onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrAllClients[$i]['id']; ?>/'">View</button>&nbsp;&nbsp;<button type="button" class="blue"  onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrAllClients[$i]['id']; ?>/edit/'">Edit</button>&nbsp;&nbsp;<?php */?>
                    <button type="button" class="blue" >Delete</button></td>
                    <td style="display:none;">&nbsp;</td>
					
               
				</tr>
				<?php } }?>
			</tbody>
		</table>
		
	</form></div>
</section>

<div class="clear"></div>
	
</article>
<!-- End content -->