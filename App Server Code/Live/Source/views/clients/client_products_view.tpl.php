
<div id="control-bar" class="grey-bg clearfix" style="opacity: 1;"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="history.go(-1);return false;"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-180.png" width="16" height="16" > Back</button>
		</div>
		<div style="float:left; padding-top: 6px;text-align: center;width: 80%;"><h1 id="client_name"><?php echo isset($outArrClientProductView[0]['title']) ? $outArrClientProductView[0]['title'] : ''; ?></h1></div>
		<div class="float-right"> 
			<button type="button" onclick="deleteProduct('<?php echo $cid;?>','<?php echo $pid;?>');" class="red">Delete Product</button> 
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
	<div class="block-border">
	<form class="block-content form" id="complex_form" method="post" action="">
		<h1>Product Details</h1>
		<?php 
			
			if (isset($outArrClientProductView[0]['pd_image']) && $outArrClientProductView[0]['pd_image']!=""){
				
				$productImage = str_replace("{client_id}",$outArrClientProductView[0]['client_id'],$config['files']['products']).$outArrClientProductView[0]['pd_image']; 
			}else{
				$productImage = $config['LIVE_URL']."views/images/no-product.png";
			}
			if (isset($outArrClientProductView[0]['pd_status']) && $outArrClientProductView[0]['pd_status']=="1"){
				$dispClientStatus = "YES";
			}else{
				$dispClientStatus = "NO";
			}
		?>
		 <input type="hidden" name="c_id" id="c_id" value="<?php echo $cid;?>" />
        
		<div style="border:1px solid #CCC;padding: 10px;height:auto;">
			<div style="float:right;"><button onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientProductView[0]['client_id']; ?>/products/<?php echo $outArrClientProductView[0]['pd_id']; ?>/edit/'" type="button" class="blue">Edit</button></div>
			<div class="clear"></div>
			<hr style="border-top: 1px dotted #afafaf;"/>
		  <div class="columns">
				<div class="colx2-left">
					<span class="label">Product Image</span>
					<ul class="checkable-list">
						<li><img src="<?php echo $productImage; ?>" alt="client background image"/  width="400" height="300"></li>
					</ul>
				</div>
			<p class="colx2-right">
              <table width="386">
                <tr>
                  <td width="125" height="35"><b>Title</b></td>
                  <td width="249"><span style="line-height: 2.3em;"><?php echo isset($outArrClientProductView[0]['pd_name']) ? $outArrClientProductView[0]['pd_name'] : ''; ?></span></td>
                </tr>
                <tr>
                  <td height="38"><b>Description</b></td>
                  <td><span style="line-height: 2.3em;"><?php echo isset($outArrClientProductView[0]['pd_description']) ? $outArrClientProductView[0]['pd_description'] : ''; ?></span></td>
                </tr>
                <tr>
                  <td height="32"><b>Barcode</b></td>
                  <td><span style="line-height: 2.3em;"><?php echo isset($outArrClientProductView[0]['pd_barcode']) ? $outArrClientProductView[0]['pd_barcode'] :''; ?></span></td>
                </tr>
                <tr>
                  <td height="43"><b>Price</b></td>
                  <td><span style="line-height: 2.3em;"><?php echo isset($outArrClientProductView[0]['pd_price']) ? $outArrClientProductView[0]['pd_price'] : ''; ?></span></td>
                </tr>
                <tr>
                  <td height="43"><b>Short Description</b></td>
                  <td><span style="line-height: 2.3em;"><?php echo isset($outArrClientProductView[0]['pd_short_description']) ? $outArrClientProductView[0]['pd_short_description'] : ''; ?></span></td>
                </tr>
                <tr>
                  <td height="39"><b>Website</b></td>
                  <td>
                  <span style="line-height: 2.3em;">
                   <a href="<?php echo isset($outArrClientProductView[0]['pd_url']) ? $outArrClientProductView[0]['pd_url'] : ''; ?>" target="_blank"><?php echo isset($outArrClientProductView[0]['pd_url']) ? $outArrClientProductView[0]['pd_url'] : ''; ?></a></span></td>
                </tr>
                <!-- <tr>
                  <td height="39"><b>HTML</b></td>
                  <td><span style="line-height: 2.3em;"><?php echo isset($outArrClientProductView[0]['html']) ? $outArrClientProductView[0]['html'] : ''; ?></span></td>
                </tr> -->
                <!-- <tr>
                  <td height="39"><b>Background</b></td>
                  <td><span style="line-height: 2.3em;"><?php if(isset($outArrClientProductView[0]['hide_background']) && $outArrClientProductView[0]['hide_background']==1){echo "Show";}else{ echo "Hide"; } ?></span></td>
                </tr> -->
                <!-- <tr>
                  <td height="39"><b>Offer</b></td>
                  <td><span style="line-height: 2.3em;"><?php if(isset($outArrClientProductView[0]['offer']) && $outArrClientProductView[0]['offer']==1){echo "Yes";}else{ echo "No"; } ?></span></td>
                </tr> -->
                <tr>
                  <td height="39"><b>Catagory</b></td>
                  <td><span style="line-height: 2.3em;"><?php echo isset($getCatagoriesById[0]['pd_category_name']) ? $getCatagoriesById[0]['pd_category_name']:''; ?></span></td>
                </tr>
                <!-- <tr>
                  <td height="39"><b>Style</b></td>
                  <td><span style="line-height: 2.3em;"><?php echo isset($getStylesById[0]['description']) ? $getStylesById[0]['description']:''; ?></span></td>
                </tr> -->
                
              </table>

			  </p>
			</div>
		</div>
	</form>
	</div>
	
</section>


<div class="clear"></div>
<script type="text/javascript">
	
</script>
</article>
<!-- End content -->