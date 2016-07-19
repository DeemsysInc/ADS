 <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
        <!-- page start-->
<!-- <div align="right">
    <form  id="table_form" method="post" action="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/clients/<?php echo $client_id;?>/offers/">
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
                            <a class="current" href="#">Offers List</a>
                        </li>
                        
                    </ul>
                </div>
</div>
<div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <div class="panel-body">
                        <form class="form-inline"  method="post"  action="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/clients/<?php echo $client_id;?>/offers/">
                           
                            <div class="form-group">
                                <label class="col-lg-2 col-sm-2 control-label">From </label>
                                <div class="col-lg-2">
                                  <input type="text" class="form-control" id="from" name="from" value="<?php echo $start_date;?>">
                                </div>
                            </div>
                        
                            <div class="form-group">
                                <label class="col-lg-2 col-sm-2 control-label" value="">To </label>
                                <div class="col-lg-2">
                                  <input type="text" class="form-control" id="to" name="to" value="<?php echo $end_date;?>">
                                </div>
                            </div>
                            <input type="submit" class="btn btn-success" value="Apply">
                        
                        </form>
                    </div>
                </section>
            </div>
        </div>

<div style="clear: both;"></div>

       <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Offer Info
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
                        <th>Offer Name</th>
                        <th>Offer image</th>
                        <th>No. of Users Viewed</th>
                        <th>Total Views</th>
                        <th>Share</th>
                        <th>Remove</th>
                        <th>Redeem</th>
                        <th>Apply</th>
                        <th>Scanned</th>
                        <th>My Offers</th>
                    </tr>
                    </thead>
                    <tbody>
                       
	                <?php for($i=0;$i<count($arrData)-1;$i++){
						if(isset($arrData[$i]['offer_image']) && $arrData[$i]['offer_image']!=""){
								
								$dispProductImage=str_replace("{client_id}",$arrData[$i]['client_id'],$config['files']['products']).$arrData[$i]['offer_image'];
							}else{
								$dispProductImage=$config['LIVE_URL']."views/images/no-product.png";
							}
					?>
	                <tr  class="gradeX" style="cursor:pointer;" onclick="document.location = '<?php echo $config['LIVE_URL'];?>analytics/mobile_users/offers/<?php echo isset($arrData[$i]['offer_id']) ? $arrData[$i]['offer_id'] : ''; ?>/details';">
						<td><?php echo isset($arrData[$i]['offer_name']) ? $arrData[$i]['offer_name'] : ''; ?></td>
						<td style="text-align:center;"><img src="<?php echo $dispProductImage;?>" alt="<?php echo isset($arrData[$i]['offer_name']) ? $arrData[$i]['offer_name'] : ''; ?>" height="100"></td>
						<td><?php echo isset($arrData[$i]['no_of_users']) ? $arrData[$i]['no_of_users'] : '0'; ?></td>
						<td><?php echo isset($arrData[$i]['totalViews']) ? $arrData[$i]['totalViews'] : '0'; ?></td>
						<td><?php echo isset($arrData[$i]['shareViews']) ? $arrData[$i]['shareViews'] : '0'; ?></td>
						<!-- <td><?php echo isset($arrData[$i]['addViews']) ? $arrData[$i]['addViews'] : 'N/A'; ?></td> -->
						<td><?php echo isset($arrData[$i]['removedViews']) ? $arrData[$i]['removedViews'] : '0'; ?></td>
						<td><?php echo isset($arrData[$i]['redeemViews']) ? $arrData[$i]['redeemViews'] : '0'; ?></td>
						<td><?php echo isset($arrData[$i]['applyViews']) ? $arrData[$i]['applyViews'] : '0'; ?></td>
                        <td><?php echo isset($arrData[$i]['scannedViews']) ? $arrData[$i]['scannedViews'] : '0'; ?></td>
						<td><?php echo isset($arrData[$i]['myOfferViews']) ? $arrData[$i]['myOfferViews'] : '0'; ?></td>
						
	               
					</tr>
                
               <?php }?>
                    </tbody>
                     <tfoot>
                    <tr>
                       <th>Offer Name</th>
                        <th>Offer image</th>
                        <th>No. of Users Viewed</th>
                        <th>Total Views</th>
                        <th>Share</th>
                        <th>Remove</th>
                        <th>Redeem</th>
                        <th>Apply</th>
                        <th>Scanned</th>
                        <th>My Offers</th>
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






