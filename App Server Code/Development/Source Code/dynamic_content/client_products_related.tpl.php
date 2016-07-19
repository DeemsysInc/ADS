<?php
include '../smcfg.php';
$pid = isset($_REQUEST['pid']) ? $_REQUEST['pid'] : 0;
$cid = isset($_REQUEST['cid']) ? $_REQUEST['cid'] : 0;
require_once(SRV_ROOT.'model/clients.model.class.php');
$objClients = new mClients();
$outArrClientProducts=array();
$outArrClientProducts = $objClients->getProductsByCID($cid);
$outArrRelatedProductIds=array();
$outArrRelatedProductIds = $objClients->getRelatedProductId($pid);

$arrRelatedId=array();
for($i=0;$i<count($outArrRelatedProductIds);$i++)
{
	$arrRelatedId[]=isset($outArrRelatedProductIds[$i]['relatedto_id']) ? $outArrRelatedProductIds[$i]['relatedto_id'] :'0';
	
}
 ?>
 <script>
 
 	/* function updateRelatedProducts(){
			alert("saveUserProfile");
			var actionType = "updateRelatedProduct";
			var chk_products = $("input[name=chk_products]:checked").map(function () {return this.value;}).get().join(",");
			var cid= jQuery("#cid").val();
			var pid=jQuery("#pid").val();
			jQuery.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, cid:cid,pid:pid,related_prods:chk_products},
				function(data){
					if(data.msg=='success')
					{
						//alert("Added related products successfully");
						window.location=data.redirect;
					} else
					{
						jQuery('#frm_error').html('Error Occured: Please contact us', {type: 'error'});
						return false;
					}
				}, 'json'
			);	
		} */
		
		
		
 $("form#frm_product_related").submit(function(){
	//alert("saveUserProfile");
			var actionType = "updateRelatedProduct";
			var chk_products = $("input[name=chk_products]:checked").map(function () {return this.value;}).get().join(",");
			var cid= jQuery("#cid").val();
			var pid=jQuery("#pid").val();
			//alert(chk_products);
	$('#loadingmessage').show();  // show the loading message.
    var formData = new FormData($(this)[0]);
        formData.append('action', "updateRelatedProduct");		formData.append('related_prods',chk_products);
    $.ajax({
        url: root_url+"includes/ajax/clients.ajax.php",
        type: 'POST',
        data: formData,
        async: false,
		dataType: 'json',
        success: function (data) {
		 $('#loadingmessage').hide(); // hide the loading message
			
       
            if(data.msg=='success')
			{
				alert("Added related products successfully");
						window.location=data.redirect;
			} else
			{
		jQuery('#frm_error').html('Error Occured: Please contact us', {type: 'error'}).css("color", "red").show();
				return false;
			}
        },
        cache: false,
        contentType: false,
        processData: false
    });

    return false;
}); 				
 </script>
	 <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                       Related Product List
                    </header>
                    <div class="panel-body">
                    <div class="adv-table">
			<form class="block-content form" action="" method="post" name="frm_product_related" id="frm_product_related">
			<input type="hidden" name="cid" id="cid" value="<?php echo $cid;?>" />
             <input type="hidden" name="pid" id="pid" value="<?php echo $pid;?>" />
              <table  class="display table table-bordered table-striped" id="cms_clients_related_products">
                    <thead>
                    <tr>
                        <th>Action</th>
                        <th>Title</th>
                        <th>Products</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php for($i=0;$i<count($outArrClientProducts);$i++) { 
				if (in_array($outArrClientProducts[$i]['pd_id'], $arrRelatedId, true)) {?>
                <tr>
					<!--<td class="th table-check-cell"><input type="checkbox" name="selected[]" id="table-selected-1" value="1"></td>-->
                    <td align="center" valign="middle"><input type="checkbox" name="chk_products" id="chk_products" checked="checked"  value="<?php echo isset($outArrClientProducts[$i]['pd_id']) ? $outArrClientProducts[$i]['pd_id'] : ''; ?>" /></td>
                    <td><?php echo isset($outArrClientProducts[$i]['pd_name']) ? $outArrClientProducts[$i]['pd_name'] :''; ?></td>
					<td>
                    <?php if(isset($outArrClientProducts[$i]['pd_image']) && $outArrClientProducts[$i]['pd_image']!=''){?>
					<img height="100" src="<?php echo str_replace("{client_id}",$outArrClientProducts[$i]['client_id'],$config['files']['products']).$outArrClientProducts[$i]['pd_image']; ?>"  />
                    <?php }else{?>
                    <img id="img_prev" src="<?php echo $config['LIVE_URL'];?>views/images/no-product.png" alt="your image" height="100"/>
                    <?php }?>
                                    
                    </td>
					</tr>
                
                <?php 
				}else{ ?>
                <tr>
					<!--<td class="th table-check-cell"><input type="checkbox" name="selected[]" id="table-selected-1" value="1"></td>-->
                    <td align="center" valign="middle"><input type="checkbox" name="chk_products" id="chk_products"  value="<?php echo $outArrClientProducts[$i]['pd_id']; ?>" /></td>
                    <td><?php echo $outArrClientProducts[$i]['pd_name']; ?></td>
                   <td>
                    <?php if(isset($outArrClientProducts[$i]['pd_image']) && $outArrClientProducts[$i]['pd_image']!=''){?>
					<img height="100" src="<?php echo str_replace("{client_id}",$outArrClientProducts[$i]['client_id'],$config['files']['products']).$outArrClientProducts[$i]['pd_image']; ?>"  />
                    <?php }else{?>
                    <img id="img_prev" src="<?php echo $config['LIVE_URL'];?>views/images/no-product.png" alt="your image" height="100"/>
                    <?php }?>
                    
                    </td>
                    </tr>
					
					
				<?php }?>
					
				
				<?php } ?>
                    </tbody>
                    </table>
				   <div align="right"> 
				    <button type="submit" class="btn btn-success">Submit</button>
                    <button type="reset"class="btn btn-danger">Reset</button>
					</div>
					</form>
                    </div>
                    </div>
                </section>
            </div>
        </div>
        
        <!-- page end-->
      
 

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

