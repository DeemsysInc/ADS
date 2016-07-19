<div id="control-bar" class="grey-bg clearfix" style="opacity: 1;"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="history.go(-1);return false;"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-180.png" width="16" height="16" > Back</button>
		</div>
		
		<?php /*?><div class="float-right"> 
			<button type="button" class="blue" onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $cid; ?>/triggers/<?php echo $tid; ?>/visuals/<?php echo $visual_id; ?>/models/add'">Add Model</button> 
		</div><?php */?>
			
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
	<div class="block-border"><form class="block-content form" id="client_triggers_models" method="post" action="">
		<h1>Models</h1>
        <div align="left">
		<button type="button" class="blue"  onclick="modalAddNew3DModel(<?php echo $cid; ?>,<?php echo $tid; ?>,<?php echo $visual_id;?>); return false;">Add Model</button>
       
		</div>
        <table class="table sortable no-margin" cellspacing="0" width="100%" id="cms_clients_products">
		
			<thead>
				<tr>
					<!--<th class="black-cell"><span class="loading"></span></th>-->
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Model
					</th>
					<th scope="col">
						Texture
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Material
					</th>
					<th scope="col" >Actions</th>
					<th scope="col" style="display:none;">&nbsp;</th>
                    <th scope="col" style="display:none;">&nbsp;</th>
                    <th scope="col" style="display:none;">&nbsp;</th>
                    <th scope="col" style="display:none;">&nbsp;</th>
                    
					
				</tr>
			</thead>
			
			<tbody>
				<?php for($i=0;$i<count($getClientModelsList);$i++) { 
					if($getClientModelsList[$i]['texture']!=""){
							
							$disptextureImage=str_replace("{client_id}",$cid,$config['files']['models']).$getClientModelsList[$i]['texture'];
						}else{
							$disptextureImage=$config['LIVE_URL']."views/images/no-product.png";
						}
						
				?>
					
				<tr>
					<!--<td class="th table-check-cell"><input type="checkbox" name="selected[]" id="table-selected-1" value="1"></td>-->
					<td><?php echo $getClientModelsList[$i]['model']; ?></td>
					<td style="text-align:center;"><img src="<?php echo $disptextureImage; ?>" height="100"/></td>
                    <td><?php echo $getClientModelsList[$i]['material']; ?></td>
                    <td><button type="button" class="blue" onclick="modalUpdate3DModel(<?php echo $cid; ?>,<?php echo $tid; ?>,<?php echo $getClientModelsList[$i]['three_d_model_id']; ?>,<?php echo $getClientModelsList[$i]['id'];?>); return false;">Edit</button><br /><br /><button type="button" class="blue" onclick="delete3DModel(<?php echo $cid; ?>,<?php echo $tid; ?>,<?php echo $getClientModelsList[$i]['three_d_model_id']; ?>,<?php echo $getClientModelsList[$i]['id'];?>);">Delete</button></td>
                    
                    <td style="display:none;">&nbsp;</td>
                    <td style="display:none;">&nbsp;</td>
                    <td style="display:none;">&nbsp;</td>
                    <td style="display:none;">&nbsp;</td>
				</tr>
				<?php }  ?>
			</tbody>
		</table>
	</form></div>
</section>
<div id="tmp_holder"></div>
<div class="clear"></div>
	
</article>
<!-- End content -->