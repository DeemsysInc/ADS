 <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
        <!-- page start-->
<!-- <div align="right">
    <form  id="table_form" method="post" action="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/clients/<?php echo $client_id;?>/products/<?php echo $start_date;?>/<?php echo $end_date;?>">
    From:<input type="text" id="from" name="from" value="<?php echo $start_date;?>">to:<input type="text" id="to" name="to" value="<?php echo $end_date;?>">&nbsp;&nbsp;&nbsp;<input type="submit" value="Apply">
    </form>
</div> -->
<div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumbs-alt">
                        <li>
                            <a href="<?php echo $config['LIVE_URL'];?>">Dashboard</a>
                        </li>
                        <li>
                            <a class="current" href="#">Product List</a>
                        </li>
                        
                    </ul>
                </div>
</div>
<div class="row">
	<div class="col-lg-12">
		<section class="panel">
			<div class="panel-body">
				<form class="form-inline"  method="post"  action="<?php echo $config['LIVE_URL']; ?>">
				 	<div class="form-group">
						<label class="col-lg-2 col-sm-2 control-label">Dates </label>
						<div class="col-lg-2">
						  <!--<input type="text"  class="form-control" id="from" name="from" value="<?php echo $start_date;?>" >-->
						  <input id="from-to-date-range" name="from-to-date-range" class="form-control"  value="<?php echo $_SESSION['start_date'];?> to <?php echo $_SESSION['end_date'];?>">
						</div>
					</div>
				
					<!--<div class="form-group">
						<label class="col-lg-2 col-sm-2 control-label" value="">To </label>
						<div class="col-lg-2">
						  <input type="text" class="form-control" id="to" name="to" value="<?php echo $end_date;?>" >
						</div>
					</div>-->
					<input type="submit" class="btn btn-success" value="Apply">
				
				</form>
			</div>
		</section>
	</div>
</div>

<div style="clear: both;"></div>



<br>
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
                    <table  class="display table table-bordered table-striped" id="dynamic-table">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Users Viewed</th>
                        <th class="hidden-phone">Total Views</th>
                        <th class="hidden-phone">Closet</th>
                        <th>Wishlist</th>
                        <th>Share</th>
                        <th>Cart</th>
                        <th>Scanned</th>
                        <th>Product details</th>


                    </tr>
                    </thead>
                    <tbody>
					
                        <?php
						for($i=0;$i<count($arrData);$i++){
                    if(isset($arrData[$i]['pd_image']) && $arrData[$i]['pd_image']!=""){
                            
                            $dispProductImage=str_replace("{client_id}",$arrData[$i]['client_id'],$config['files']['products']).$arrData[$i]['pd_image'];
                        }else{
                            $dispProductImage=$config['LIVE_URL']."views/images/no-product.png";
                        }
                ?>

                <tr class="gradeX" style="cursor:pointer;" onclick="document.location = '<?php echo $config['LIVE_URL'];?>analytics/mobile_users/clients/products/views/<?php echo isset($arrData[$i]['pd_id']) ? $arrData[$i]['pd_id'] : ''; ?>/details';">
                    <td><?php echo isset($arrData[$i]['pd_name']) ? $arrData[$i]['pd_name'] : ''; ?></td>
                    <td style="text-align:center;"><img src="<?php echo $dispProductImage;?>" alt="<?php echo isset($arrData[$i]['pd_name']) ? $arrData[$i]['pd_name'] : ''; ?>" height="100"></td>
                    <td><?php echo isset($arrData[$i]['no_of_users']) ? $arrData[$i]['no_of_users'] : 0; ?></td>
                    <td><?php echo isset($arrData[$i]['totalViews']) ? $arrData[$i]['totalViews'] : 0; ?></td>
                    <td><?php echo isset($arrData[$i]['closetViews']) ? $arrData[$i]['closetViews'] : 0; ?></td>
                    <td><?php echo isset($arrData[$i]['wishlistViews']) ? $arrData[$i]['wishlistViews'] : 0; ?></td>
                    <td><?php echo isset($arrData[$i]['shareViews']) ? $arrData[$i]['shareViews'] : 0; ?></td>
                    <td><?php echo isset($arrData[$i]['cartViews']) ? $arrData[$i]['cartViews'] : 0; ?></td>
                    <td><?php echo isset($arrData[$i]['scannedViews']) ? $arrData[$i]['scannedViews'] : 0; ?></td>
                    <td><?php echo isset($arrData[$i]['product_detail_views']) ? $arrData[$i]['product_detail_views'] : 0; ?></td> 
                  
                    
               </tr>
               <?php }?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Users Viewed</th>
                        <th class="hidden-phone">Total Views</th>
                        <th class="hidden-phone">Closet</th>
                        <th>Wishlist</th>
                        <th>Share</th>
                        <th>Cart</th>
                        <th>Scanned</th>
                        <th>Product details</th>
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
    <!--main content end-->