<div id="control-bar" class="grey-bg clearfix" style="opacity: 1;"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="history.go(-1);return false;"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-180.png" width="16" height="16" > Back</button>
		</div>
		<?php /*?><div style="float:left; padding-top: 6px;text-align: center;width: 80%;"><h1 id="client_name"><?php echo isset($outArrClient[0]['name']) ? $outArrClient[0]['name'] : ''; ?></h1></div><?php */?>
		<?php /*?><div class="float-right"> 
			<button type="button" class="red">Delete Client</button> 
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
<?php /*?><div style="text-align: center;width: 100%;font-size:20px;"><h1 id="client_name"><?php echo $outArrClientTriggerVisuals[0]['name']; ?></h1></div><?php */?>
<section class="grid_12">
	<div class="block-border"><form class="block-content form" id="table_form" method="post" action="">
		<h1>Visuals</h1>
       
        
        <div align="left" style="margin:0px;">
		<button type="button" class="blue" onclick="modalAddButton(<?php echo $cid; ?>,<?php echo $tid; ?>,''); return false;" >Add Button</button>&nbsp;&nbsp; <button type="button" class="blue" onclick="modalAdd3DModel(<?php echo $cid; ?>,<?php echo $tid; ?>,''); return false;">Add 3D Model</button>&nbsp;&nbsp; <button type="button" class="blue"  onclick="modalAddVideo(<?php echo $cid; ?>,<?php echo $tid; ?>,''); return false;">Add Video</button>&nbsp;&nbsp; <button type="button" class="blue" onclick="modalAddUrl(<?php echo $cid; ?>,<?php echo $tid; ?>,''); return false;">Add Url</button>
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
						X
					</th>
					<th scope="col">
						Y
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Scale
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Type
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Url
					</th>
                    <th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						In 3D
					</th>
                    <th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Ignore Tracking
					</th>
                   <th scope="col">Action</th>
					
				</tr>
			</thead>
			
			<tbody>
				<?php for($i=0;$i<count($outArrClientTriggerVisuals);$i++) { ?>
					
				<tr>
					<!--<td class="th table-check-cell"><input type="checkbox" name="selected[]" id="table-selected-1" value="1"></td>-->
					<td><?php echo $outArrClientTriggerVisuals[$i]['x']; ?></td>
					<td><?php echo $outArrClientTriggerVisuals[$i]['y']; ?></td>
					<td><?php echo $outArrClientTriggerVisuals[$i]['scale']; ?></td>
					<td><?php echo $outArrClientTriggerVisuals[$i]['discriminator']; ?></td>
                     <?php if(pathinfo($outArrClientTriggerVisuals[$i]['url'], PATHINFO_EXTENSION)=='mp4'){?>
                    <td>
                    <div align="center">
                    <video width="300" height="200" controls >
                      <source src="<?php echo str_replace("{client_id}",$cid,$config['files']['videos']).$outArrClientTriggerVisuals[$i]['url'];?>" type="video/mp4">
                      
                    </video>
                    </div>
                    </td>
					<?php }else{?>
                    <td><?php echo $outArrClientTriggerVisuals[$i]['url']; ?></td>
                    <?php }?>
                    
                    
					
                    
                    <td><?php echo $outArrClientTriggerVisuals[$i]['video_in_metaio']; ?></td>
                    <td><?php echo $outArrClientTriggerVisuals[$i]['ignore_tracking']; ?></td>
                   <td>
                   
                   <?php if($outArrClientTriggerVisuals[$i]['discriminator']=='VIDEO'){?>
                   <button type="button" class="blue"  onclick="modalAddVideo(<?php echo $cid; ?>,<?php echo $tid; ?>,<?php echo $outArrClientTriggerVisuals[$i]['id'];?>); return false;">Edit</button><br /><br />
                   
                   <?php }else if($outArrClientTriggerVisuals[$i]['discriminator']=='BUTTON'){?>
                     <button type="button" class="blue" onclick="modalAddButton(<?php echo $cid; ?>,<?php echo $tid; ?>,<?php echo $outArrClientTriggerVisuals[$i]['id'];?>); return false;" >Edit</button><br /><br />
                   <?php }else if($outArrClientTriggerVisuals[$i]['discriminator']=='URL'){?>
                   <button type="button" class="blue" onclick="modalAddUrl(<?php echo $cid; ?>,<?php echo $tid; ?>,<?php echo $outArrClientTriggerVisuals[$i]['id'];?>); return false;">Edit</button><br /><br />
                   <?php }else if($outArrClientTriggerVisuals[$i]['discriminator']=='3DMODEL'){?>
                   <button type="button" class="blue" onclick="modalAdd3DModel(<?php echo $cid; ?>,<?php echo $tid; ?>,<?php echo $outArrClientTriggerVisuals[$i]['id'];?>); return false;">Edit</button><br /><br />
                    
                    <button type="button" class="blue" onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $cid; ?>/triggers/<?php echo $tid; ?>/visuals/<?php echo $outArrClientTriggerVisuals[$i]['id'];?>/models/'">View</button>
                   <?php }?>
              
                  <button type="button" class="blue" onclick="deleteTriggerVisual(<?php echo $outArrClientTriggerVisuals[$i]['id'];?>,<?php echo $cid;?>,<?php echo $tid;?>);">Delete</button>
                   
                  
                   
                   </td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</form></div>
</section>
<div id="tmp_holder"></div>
<div class="clear"></div>
	
</article>
<!-- End content -->