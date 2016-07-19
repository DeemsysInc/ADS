<div id="control-bar" class="grey-bg clearfix" style="opacity: 1;"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="history.go(-1);return false;"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-180.png" width="16" height="16" > Back</button>
		</div>
        <div style="float:left; padding-top: 6px;text-align: center;width: 80%;"><h1 id="client_name">Clients</h1></div>
		<div class="float-right"> 
			<button type="button" onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/add'">Add Client</button> 
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
		<h1>Clients</h1>
		
		<table class="table sortable no-margin" cellspacing="0" width="100%" id="cms_clients">
		
			<thead>
				<tr>
					<!--<th class="black-cell"><span class="loading"></span></th>-->
					<th scope="col">
						Logo
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
						Prefix
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Website
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Status
					</th>
					<th scope="col">Action</th>
					
				</tr>
			</thead>
			
			<tbody>
				<?php for($i=0;$i<count($outArrAllClients);$i++) { 
					if(isset($outArrAllClients[$i]['active']) && $outArrAllClients[$i]['active']!=2)
					{
						if($outArrAllClients[$i]['logo']!=""){
							 $filepath=str_replace("{client_id}",$outArrAllClients[$i]['id'],$config['files']['logo']).$outArrAllClients[$i]['logo'];
							 $dispClient='<img src="'.$filepath.'" height="40"/>';
						}else{
							$dispClient='<p style="font-size: 28px;text-align: center;">'.$outArrAllClients[$i]['name'].'</p>';
						}
						if ($outArrAllClients[$i]['active']=="1"){
							$dispClientStatus = "Active";
						}else{
							$dispClientStatus = "Not Active";
						}
				?>
					
				<tr>
					<!--<td class="th table-check-cell"><input type="checkbox" name="selected[]" id="table-selected-1" value="1"></td>-->
					<td style="background:#CCC;border-bottom: 1px solid #fff;"><a href="<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrAllClients[$i]['id']; ?>/"><?php echo $dispClient; ?></a></td>
					<td><?php echo $outArrAllClients[$i]['name']; ?></td>
					<td><?php echo $outArrAllClients[$i]['prefix']; ?></td>
					<td><a href="<?php echo $outArrAllClients[$i]['url']; ?>" target="_blank"><?php echo $outArrAllClients[$i]['url']; ?></a></td>
					<td><?php echo $dispClientStatus; ?></td>
					<td>
                    <a href="<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrAllClients[$i]['id']; ?>/" class="big-button">View</a>&nbsp;&nbsp;
                    <a href="<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrAllClients[$i]['id']; ?>/edit/" class="big-button">Edit</a>&nbsp;&nbsp;
                   
                    <?php /*?><button type="button" class="blue" onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrAllClients[$i]['id']; ?>/'">View</button>&nbsp;&nbsp;<button type="button" class="blue"  onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrAllClients[$i]['id']; ?>/edit/'">Edit</button>&nbsp;&nbsp;<?php */?><button type="button" class="blue"  onclick="deleteClient('<?php echo $outArrAllClients[$i]['id']; ?>');">Delete</button></td>
                    
               
				</tr>
				<?php } }?>
			</tbody>
		</table>
	</form></div>
</section>

<div class="clear"></div>
	
</article>
<!-- End content -->