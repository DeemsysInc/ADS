	<div id="control-bar" class="grey-bg clearfix" style="opacity: 1;"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="history.go(-1);return false;"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-180.png" width="16" height="16" > Back</button>
		</div>
		
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

<section class="grid_12">
	<div class="block-border"><form class="block-content form" id="table_form" method="post" action="">
		<h1>Additional Media</h1>
		<div align="left"><button type="button" class="blue" onclick="modalAddAdditionalMedia(<?php echo $cid;?>,<?php echo $pid;?>); return false;" >Add Additional Media</button></div>
        <br />
		<table class="table sortable no-margin" cellspacing="0" width="100%" id="cms_clients_products">
		
			<thead>
				<tr>
					<!--<th class="black-cell"><span class="loading"></span></th>-->
					
					<th scope="col">
						Image
					</th>
					<th scope="col">Action</th>
                    <th scope="col" style="display:none;">&nbsp;</th>
                    <th scope="col" style="display:none;">&nbsp;</th>
                    <th scope="col" style="display:none;">&nbsp;</th>
                    <th scope="col" style="display:none;">&nbsp;</th>
                    <th scope="col" style="display:none;">&nbsp;</th>
                    
				</tr>
			</thead>
			
			<tbody>
				<?php for($i=0;$i<count($outArrClientProductAdditional);$i++) { 
					
						if($outArrClientProductAdditional[$i]['media']!=""){
							
							$dispProductImage=str_replace("{client_id}",$cid,$config['files']['additional']).$outArrClientProductAdditional[$i]['media'];
							
						}else{
							$dispProductImage=$config['LIVE_URL']."views/images/no-product.png";
						}
						
				?>
					
				<tr>
					<!--<td class="th table-check-cell"><input type="checkbox" name="selected[]" id="table-selected-1" value="1"></td>-->
					
					<td style="text-align:center;">
                    <?php if(pathinfo($dispProductImage, PATHINFO_EXTENSION)=='mp4'){?>
                    <div align="center">
                    <video width="300" height="200" controls >
                      <source src="<?php echo $dispProductImage;?>" type="video/mp4">
                      
                    </video>
                    </div>
					<?php }else{?>
                    <img src="<?php echo $dispProductImage; ?>" width="300" height="200"/>
                    <?php }?>
                    </td>
					<td><button type="button" class="blue" onclick="modalEditAdditionalMedia(<?php echo $cid; ?>,<?php echo $pid; ?>,<?php echo $outArrClientProductAdditional[$i]['id']; ?>); return false;" >Edit</button><br /><br /><button type="button" class="blue" onclick="deleteAdditionalMedia(<?php echo $cid;?>,<?php echo $pid;?>,<?php echo $outArrClientProductAdditional[$i]['id'];?>)" >Delete</button><br /><br /></td>
                    <td style="display:none;">&nbsp;</td>
                    <td style="display:none;">&nbsp;</td>
                    <td style="display:none;">&nbsp;</td>
                    <td style="display:none;">&nbsp;</td>
                    <td style="display:none;">&nbsp;</td>
					
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
