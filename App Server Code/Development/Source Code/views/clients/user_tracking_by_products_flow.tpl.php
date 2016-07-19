<!--main content start-->
    <section id="main-content">
        <section class="wrapper">
        <!-- page start-->
            <div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumbs-alt">
                        <li>
                            <a href="<?php echo $config['LIVE_URL'];?>">Dashboard</a>
                        </li>
                        <li>
                            <a href="<?php echo $config['LIVE_URL'];?>analytics/mobile_users/clients/<?php echo isset($arrData[0]['client_id']) ? $arrData[0]['client_id'] : 0;?>/products/<?php echo $start_date;?>/<?php echo $end_date;?>">Product List</a>
                        </li>
                        <li>
                            <a class="current" href="#">Product Details</a>
                        </li>                      
                    </ul>
                </div>
            </div>       
         <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <div class="panel-body">
                        <form class="form-inline"  method="post"  action="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/products/flow/<?php echo isset($arrData[0]['productId']) ? $arrData[0]['productId'] : 0;?>">                          
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
            <div class="col-md-12">
                <section class="panel">
                  <header class="panel-heading">
                        Product Details
                        <span class="tools pull-right">
                            <a href="javascript:;" class="fa fa-chevron-down"></a>
                            <a href="javascript:;" class="fa fa-cog"></a>
                            <a href="javascript:;" class="fa fa-times"></a>
                         </span>
                    </header>
                    <div class="panel-body profile-information">
                       <div class="col-md-3">
                           <div class="profile-pic text-center">                       
                           <?php if(isset($arrData[0]['pd_image']) && $arrData[0]['pd_image']!=""){
                                    $dispProductImage=str_replace("{client_id}",$arrData[0]['client_id'],$config['files']['products']).$arrData[0]['pd_image'];
                                   }else{
                                       $dispProductImage=$config['LIVE_URL']."views/images/no-product.png";
                                     } 
                            ?>    
                                <img src="<?php echo $dispProductImage;?>" width="250px" height="250px">
                           </div>
                       </div>
                       <div class="col-md-9">
                            <div class="profile-desk">
                                <h1><?php echo isset($arrData[0]['productName']) ? $arrData[0]['productName'] : '';?></h1>
                                <!-- <span class="text-muted">Product Manager</span> -->
                                <p>
                                   <div class="prf-box">
                                            <h3 class="prf-border-head">performance status</h3>
                                            <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Users</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php echo isset($arrData[0]['no_of_users']) ? $arrData[0]['no_of_users'] : 0;?></strong>
                                                </div>
                                            </div>
                                            <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Cart</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php echo isset($arrData[0]['cartViews']) ? $arrData[0]['cartViews'] : 0;?></strong>
                                                </div>
                                            </div>
                                            <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Closet</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php echo isset($arrData[0]['closetViews']) ? $arrData[0]['closetViews'] : 0;?></strong>
                                                </div>
                                            </div>
                                             <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Wishlists</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php echo isset($arrData[0]['wishlistViews']) ? $arrData[0]['wishlistViews'] : 0;?></strong>
                                                </div>
                                            </div>
                                             <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Shared</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php echo isset($arrData[0]['shareViews']) ? $arrData[0]['shareViews'] : 0;?></strong>
                                                </div>
                                            </div>
                                             <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Details Page</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php echo isset($arrData[0]['product_detail_views']) ? $arrData[0]['product_detail_views'] : 0;?></strong>
                                                </div>
                                            </div>
                                            <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Scanned</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php echo isset($arrData[0]['scannedViews']) ? $arrData[0]['scannedViews'] : 0;?></strong>
                                                </div>
                                            </div>
                                            <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Total Views</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php echo isset($arrData[0]['totalViews']) ? $arrData[0]['totalViews'] : 0;?></strong>
                                                </div>
                                            </div>
                                        </div>
                               </p>
                               
                           </div>
                       </div>
                      
                    </div>
                </section>
            </div>
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Product Over flow
                        <span class="tools pull-right">
                            <a href="javascript:;" class="fa fa-chevron-down"></a>
                            <a href="javascript:;" class="fa fa-cog"></a>
                            <a href="javascript:;" class="fa fa-times"></a>
                         </span>
                    </header>
            <div class="panel-body">
                <div class="tree">
                    <ul>
                         <li>
                             <a href="javascript:void(0);" onclick="return getClientProductUserInfo('<?php echo $prdId;?>','<?php echo $start_date;?>','<?php echo $end_date;?>');"><div><div>Users</div><br><div><h2><?php echo isset($arrData[0]['no_of_users']) ? $arrData[0]['no_of_users'] : 0;?></h2></div></div></a>
                    <ul>
                         <li> <a href="#" ><div><div>Total views</div><br><div><h2><?php echo isset($arrData[0]['totalViews']) ? $arrData[0]['totalViews'] : 0;?></h2></div></div></a>
                    <ul>
                        <li>
                              <a href="javascript:void(0);" onclick="return getClientProductCartInfo('<?php echo $prdId;?>','<?php echo $start_date;?>','<?php echo $end_date;?>');"><div><div>Cart</div><div><h2><?php echo isset($arrData[0]['cartViews']) ? $arrData[0]['cartViews'] : 0;?></h2></div></div></a>       
        </li>
         <li>
          <a href="javascript:void(0);" onclick="return getClientProductDetailsInfo('<?php echo $prdId;?>','<?php echo $start_date;?>','<?php echo $end_date;?>');"><div><div>Details Page</div><div><h2><?php echo isset($arrData[0]['product_detail_views']) ? $arrData[0]['product_detail_views'] : 0;?></h2></div></div></a>
          
        </li>
        <li>
           <a href="javascript:void(0);" onclick="return getClientProductShareInfo('<?php echo $prdId;?>','<?php echo $start_date;?>','<?php echo $end_date;?>');"><div><div>Share</div><div><h2><?php echo isset($arrData[0]['shareViews']) ? $arrData[0]['shareViews'] : 0;?></h2></div></div></a>
          <ul>
            <li>
              <a href="javascript:void(0);" onclick="return getClientProductShareFBInfo('<?php echo $prdId;?>','<?php echo $start_date;?>','<?php echo $end_date;?>');"><div><div>FB</div><div><h2><?php echo isset($arrData[0]['facebookViews']) ? $arrData[0]['facebookViews'] : 0;?></h2></div></div></a>
            </li>
            <li>
               <a href="javascript:void(0);" onclick="return getClientProductShareTwitterInfo('<?php echo $prdId;?>','<?php echo $start_date;?>','<?php echo $end_date;?>');"><div><div>Twitter</div><div><h2><?php echo isset($arrData[0]['twitterViews']) ? $arrData[0]['twitterViews'] : 0;?></h2></div></div></a>
            </li>
            <li>
               <a href="javascript:void(0);" onclick="return getClientProductShareEmailInfo('<?php echo $prdId;?>','<?php echo $start_date;?>','<?php echo $end_date;?>');"><div><div>Email</div><div><h2><?php echo isset($arrData[0]['emailViews']) ? $arrData[0]['emailViews'] : 0;?></h2></div></div></a>
            </li>
             <li>
               <a href="javascript:void(0);" onclick="return getClientProductShareSmsInfo('<?php echo $prdId;?>','<?php echo $start_date;?>','<?php echo $end_date;?>');"><div><div>SMS</div><div><h2><?php echo isset($arrData[0]['smsViews']) ? $arrData[0]['smsViews'] : 0;?></h2></div></div></a>
            </li>
          </ul>
        </li>
        <li>
           <a href="javascript:void(0);" onclick="return getClientProductClosetInfo('<?php echo $prdId;?>','<?php echo $start_date;?>','<?php echo $end_date;?>');"><div><div>Closet</div><div><h2><?php echo isset($arrData[0]['closetViews']) ? $arrData[0]['closetViews'] : 0;?></h2></div></div></a>
        </li>
        <li> 
             <a href="javascript:void(0);" onclick="return getClientProductScannedInfo('<?php echo $prdId;?>','<?php echo $start_date;?>','<?php echo $end_date;?>');"><div><div>Scanned</div><div><h2><?php echo isset($arrData[0]['scannedViews']) ? $arrData[0]['scannedViews'] : 0;?></h2></div></div></a>
        </li>
        <li>
            <a href="javascript:void(0);" onclick="return getClientProductWishlistInfo('<?php echo $prdId;?>','<?php echo $start_date;?>','<?php echo $end_date;?>');"><div><div>Wishlists</div><div><h2><?php echo isset($arrData[0]['wishlistViews']) ? $arrData[0]['wishlistViews'] : 0;?></h2></div></div></a>
        </li>
        
      </ul>

        </li>
        
      </ul>


     
    </li>
  </ul>
</div>
                    </div>
                </section>
            </div>

            <div class="clear"></div>
  <div id="displayProductUserLevel"></div>
 

            
        </div>
        <!-- page end-->
        </section>
    </section>
    <!--main content end-->