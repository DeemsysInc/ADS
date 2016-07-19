<section id="main-content">
    <section class="wrapper">
		<div class="row">
            <div class="col-md-12">
                <ul class="breadcrumbs-alt">
                    <li>
                        <a href="<?php echo $config['LIVE_URL'];?>">Dashboard</a>
                    </li>
                    <li>					    <a class="current" href="#">Products List</a>
                    </li>
                </ul>
            </div>
        </div>
	    <div style="clear: both;"></div>
            <div class="row">
                <div class="col-sm-12">
                    <section class="panel">
                        <header class="panel-heading">
                           Product Info
                        <span class="tools pull-right">
                         <a href="javascript:;" class="fa fa-chevron-down"></a>
                         <a href="javascript:;" class="fa fa-cog"></a>
                         <a href="javascript:;" class="fa fa-times"></a>
                        </span>
                        </header>
                <div class="panel-body">
                    <div class="adv-table">
					    <div align="right">
		                  <a href="<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $cid;?>/products/add/" class="btn btn-success"> Add Product </a>&nbsp;&nbsp;
	                    </div>
                <table  class="display table table-bordered table-striped" id="dynamic-table">                    <thead>
                       <tr>
                       <th>Title</th>
                       <th>Image</th>
                       <th>Description</th>
                       <th class="hidden-phone">Price($)</th>
                       <th class="hidden-phone">Status</th>
                       <th>Others</th>
                       <th>Action</th>
                       </tr>
                    </thead>
                    <tbody>
					<?php //echo $outArrClientProducts?>
				    <?php for($i=0;$i<count($outArrClientProducts);$i++) { 
					if(isset($outArrClientProducts[$i]['pd_status']) && $outArrClientProducts[$i]['pd_status']!=2)
					{
						if(isset($outArrClientProducts[$i]['pd_image']) && $outArrClientProducts[$i]['pd_image']!=""){
							$dispProductImage=str_replace("{client_id}",$outArrClientProducts[$i]['client_id'],$config['files']['products']).$outArrClientProducts[$i]['pd_image'];
						}else{
							$dispProductImage=$config['LIVE_URL']."views/images/no-product.png";
						}
						if ($outArrClientProducts[$i]['pd_status']=="1"){
							$dispClientProductStatus = "Active";
						}else{
							$dispClientProductStatus = "In active";
						}
						$price = isset($outArrClientProducts[$i]['pd_price']) ? $outArrClientProducts[$i]['pd_price']: '0'; 
				    ?>
				    <tr>
					<td><?php echo isset($outArrClientProducts[$i]['pd_name']) ? $outArrClientProducts[$i]['pd_name'] :''; ?></td>
					<td style="text-align:center;"><img src="<?php echo $dispProductImage; ?>" height="100"/></td>
					<td><?php echo isset($outArrClientProducts[$i]['pd_description']) ?$outArrClientProducts[$i]['pd_description'] : ''; ?></td>
					<td><?php echo "$".number_format((float)($price),2,'.','');?></td>
					<td><?php echo $dispClientProductStatus; ?></td>
					<td style="width:190px;">
					<a href="<?php echo $config['LIVE_URL']; ?>dynamic_content/client_products_related.tpl.php?pid=<?php echo $outArrClientProducts[$i]['pd_id']; ?>&cid=<?php echo $outArrClientProducts[$i]['client_id']; ?>" class="btn btn-lg btn-primary" data-toggle="modal" data-target="#relatedProducts<?php echo $outArrClientProducts[$i]['pd_id']; ?>" class="btn btn-success">Related Products</a>
    				<!-- Modal HTML -->
					<div id="relatedProducts<?php echo $outArrClientProducts[$i]['pd_id']; ?>" class="modal fade">
						<div class="modal-dialog">
							<div class="modal-content">
								<!-- Content will be loaded here from "remote.php" file -->
							</div>
						</div>
					</div>
					<br/><br/>      
					<button type="button" class="btn btn-success"  onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientProducts[$i]['client_id']; ?>/products/<?php echo $outArrClientProducts[$i]['pd_id']; ?>/additional/'">Additional Media</button>
					</td>
					<td>
                    <a href="<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientProducts[$i]['client_id']; ?>/products/<?php echo $outArrClientProducts[$i]['pd_id']; ?>/view/" class="btn btn-success" >View</a><br /><br />
                    <a href="<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientProducts[$i]['client_id']; ?>/products/<?php echo $outArrClientProducts[$i]['pd_id']; ?>/edit/" class="btn btn-warning cancel" >Edit</a><br /><br />
                    <button type="button" class="btn btn-danger" onclick="deleteProduct(<?php echo $outArrClientProducts[$i]['client_id']; ?>,<?php echo $outArrClientProducts[$i]['pd_id']; ?>)">Delete</button></td>
				    </tr>
				    <?php } } ?>
			      </tbody>
			      <tfoot>
                    <tr>
                        <th>Title</th>
                        <th>Image</th>
                        <th>Description</th>
                        <th class="hidden-phone">Price($)</th>
                        <th class="hidden-phone">Status</th>
                        <th>Others</th>
                        <th>Action</th>
                     </tr>
                    </tfoot>
                    </table>
                    </div>
                </div>
                </section>
            </div>
        </div>
        <!-- page end-->
    </section>
 </section>
				
				
				
				
				
				
				
				
				
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					