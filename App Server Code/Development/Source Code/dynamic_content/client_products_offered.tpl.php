<?php
include '../smcfg.php';
$pid = isset($_REQUEST['pid']) ? $_REQUEST['pid'] : 0;
$cid = isset($_REQUEST['cid']) ? $_REQUEST['cid'] : 0;
require_once(SRV_ROOT.'model/clients.model.class.php');
$objClients = new mClients();

$outArrClientProducts=array();
$outArrClientProducts = $objClients->getOfferedProductsByCID($cid);

$outArrOfferedProductIds=array();
$outArrOfferedProductIds = $objClients->getOfferedProductId($pid);

$arrOfferIds=isset($outArrOfferedProductIds[0]['offerto_id']) ? $outArrOfferedProductIds[0]['offerto_id'] : '';
/*$arrOfferIds=array();
for($i=0;$i<count($outArrOfferedProductIds);$i++)
{
	$arrOfferIds[]=$outArrOfferedProductIds[$i]['offerto_id'];
	
}*/

 ?>
 <section>
<div class="block-border" align="center">
<form class="block-content form" action="" method="post" name="frm_offer_products" id="frm_offer_products">
<input type="hidden" name="cid" id="cid" value="<?php echo $cid;?>" />
<input type="hidden" name="pid" id="pid" value="<?php echo $pid;?>" />
<input type="radio" name="rd_products" id="rd_products"  value="0" checked="checked"  /><span>None</span>
<br />
<br />
<table class="table sortable no-margin" cellspacing="0" width="100%" id="cms_clients_offer_products">
		
			<thead>
				<tr>
					<!--<th class="black-cell"><span class="loading"></span></th>-->
					<th scope="col">
						
						Action
					</th>
                    <th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Title
					</th>
                    <th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Products
					</th>
					<th scope="col" style="display:none;">&nbsp;</th>
                    <th scope="col" style="display:none;">&nbsp;</th>
                    <th scope="col" style="display:none;">&nbsp;</th>
                    <th scope="col" style="display:none;">&nbsp;</th>
					
					
				</tr>
			</thead>
			
			<tbody>
				<?php for($i=0;$i<count($outArrClientProducts);$i++) { 
				if ($arrOfferIds==$outArrClientProducts[$i]['id']) { ?>
                <tr>
					<!--<td class="th table-check-cell"><input type="checkbox" name="selected[]" id="table-selected-1" value="1"></td>-->
                    <td align="center" valign="middle"><input type="radio" name="rd_products" id="rd_products" checked="checked"  value="<?php echo $outArrClientProducts[$i]['id']; ?>" /></td>
                    <td><?php echo $outArrClientProducts[$i]['title']; ?></td>
					<td> <?php if(isset($outArrClientProducts[$i]['image']) && $outArrClientProducts[$i]['image']!=''){?>
					<img height="100" src="<?php echo str_replace("{client_id}",$outArrClientProducts[$i]['client_id'],$config['files']['products']).$outArrClientProducts[$i]['image']; ?>" alt="<?php echo $outArrClientProducts[$i]['title']; ?>" />
                    <?php }else{?>
                    <img id="img_prev" src="<?php echo $config['LIVE_URL'];?>views/images/no-product.png" alt="your image" height="100"/>
                    <?php }?>
                    </td>
                    <td style="display:none;">&nbsp;</td>
                    <td style="display:none;">&nbsp;</td>
                    <td style="display:none;">&nbsp;</td>
                    <td style="display:none;">&nbsp;</td>
				
                   
				</tr>
                
                <?php 
				}else{?>
                <tr>
					<!--<td class="th table-check-cell"><input type="checkbox" name="selected[]" id="table-selected-1" value="1"></td>-->
                    <td align="center" valign="middle"><input type="radio" name="rd_products" id="rd_products"  value="<?php echo $outArrClientProducts[$i]['id']; ?>" /></td>
                    <td><?php echo $outArrClientProducts[$i]['title']; ?></td>
					<td>
                     <?php if(isset($outArrClientProducts[$i]['image']) && $outArrClientProducts[$i]['image']!=''){?>
					<img height="100" src="<?php echo str_replace("{client_id}",$outArrClientProducts[$i]['client_id'],$config['files']['products']).$outArrClientProducts[$i]['image']; ?>" alt="<?php echo $outArrClientProducts[$i]['title']; ?>" />
                    <?php }else{?>
                    <img id="img_prev" src="<?php echo $config['LIVE_URL'];?>views/images/no-product.png" alt="your image" height="100"/>
                    <?php }?>
                    
                    
                    </td>
                    <td style="display:none;">&nbsp;</td>
                    <td style="display:none;">&nbsp;</td>
                    <td style="display:none;">&nbsp;</td>
                    <td style="display:none;">&nbsp;</td>
				</tr>
					
					
				<?php }?>
					
				
				<?php } ?>
			</tbody>
		</table>
</form>
</div>

</section>









