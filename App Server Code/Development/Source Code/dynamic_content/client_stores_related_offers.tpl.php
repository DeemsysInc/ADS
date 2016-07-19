<?php
include '../smcfg.php';
$storeId = isset($_REQUEST['storeId']) ? $_REQUEST['storeId'] : 0;
$cid = isset($_REQUEST['cid']) ? $_REQUEST['cid'] : 0;
require_once(SRV_ROOT.'model/clients.model.class.php');
$objClients = new mClients();
$outArrOffers=array();
$outArrOffers = $objClients->getAllOffersByCID($cid);
$outArrRelatedOfferIds=array();
$outArrRelatedOfferIds = $objClients->getStoreRelatedOfferId($storeId);

$arrRelatedId=array();

$offerId=isset($outArrRelatedOfferIds[0]['store_available_offerids']) ? $outArrRelatedOfferIds[0]['store_available_offerids'] :'0';
$explOfferId=explode(',',$offerId);
//print_r($explOfferId);
for($j=0;$j<count($explOfferId);$j++)
{
	$arrRelatedId[]=$explOfferId[$j];
}
//print_r($outArrOffers);
 ?>
 <section class="">
<div class="block-border" align="center">
<form class="block-content form" action="" method="post" name="frm_users_password" id="frm_visual_addbtn">
<input type="hidden" name="cid" id="cid" value="<?php echo $cid;?>" />
<input type="hidden" name="storeId" id="storeId" value="<?php echo $storeId;?>" />


<table class="table sortable no-margin" cellspacing="0" width="100%" id="cms_clients_related_products">
		
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
						Offer name
					</th>
                    <th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Image
					</th>
					<th scope="col" style="display:none;">&nbsp;</th>
					<th scope="col" style="display:none;">&nbsp;</th>
                    <th scope="col" style="display:none;">&nbsp;</th>
					<th scope="col" style="display:none;">&nbsp;</th>
				</tr>
			</thead>
			
			<tbody>
				<?php for($i=0;$i<count($outArrOffers);$i++) { 
				if (in_array($outArrOffers[$i]['offer_id'], $arrRelatedId, true)) {?>
                <tr>
					<!--<td class="th table-check-cell"><input type="checkbox" name="selected[]" id="table-selected-1" value="1"></td>-->
                    <td align="center" valign="middle"><input type="checkbox" name="chk_offers" id="chk_offers" checked="checked"  value="<?php echo isset($outArrOffers[$i]['offer_id']) ? $outArrOffers[$i]['offer_id'] : ''; ?>" /></td>
                    <td><?php echo isset($outArrOffers[$i]['offer_name']) ? $outArrOffers[$i]['offer_name'] :''; ?></td>
					<td>
                    <?php if(isset($outArrOffers[$i]['offer_image']) && $outArrOffers[$i]['offer_image']!=''){?>
					<img height="100" src="<?php echo str_replace("{client_id}",$outArrOffers[$i]['client_id'],$config['files']['products']).$outArrOffers[$i]['offer_image']; ?>"  />
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
				}else{ ?>
                <tr>
					<!--<td class="th table-check-cell"><input type="checkbox" name="selected[]" id="table-selected-1" value="1"></td>-->
                    <td align="center" valign="middle"><input type="checkbox" name="chk_offers" id="chk_offers"  value="<?php echo $outArrOffers[$i]['offer_id']; ?>" /></td>
                    <td><?php echo $outArrOffers[$i]['offer_name']; ?></td>
					<td>
                    <?php if(isset($outArrOffers[$i]['offer_image']) && $outArrOffers[$i]['offer_image']!=''){?>
					<img height="100" src="<?php echo str_replace("{client_id}",$outArrOffers[$i]['client_id'],$config['files']['products']).$outArrOffers[$i]['offer_image']; ?>"  />
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



<?php /*?>
$arrRelatedSearch = array();
for($i=0;$i<count($outArrRelatedProductIds);$i++){
	array_push($arrRelatedSearch, isset($outArrRelatedProductIds[$i][0]) ? $outArrRelatedProductIds[$i][0] : '');
} ?>
<table cellspacing="0" cellpadding="5" border="1" width="100%"><?php

for($i=0;$i<count($outArrClientProducts);$i++){ 
	$relatedProduct = "";
	if (in_array($outArrClientProducts[$i]['id'],$arrRelatedSearch)){ $relatedProduct = "checked"; }
?>
	<tr style="border:1px solid #CCC">
		<td width="50%" style="vertical-align:middle;padding-left:20px;"><input type="checkbox" name="chk_products" id="chk_products" <?php echo $relatedProduct; ?> /><span><?php echo $outArrClientProducts[$i]['title']; ?></span></td>
		<td style="text-align:center;"><img src="<?php echo $outArrClientProducts[$i]['image']; ?>" height="150"/></td>
	</tr>
<?php  } ?>
</table>
<?php */?>

