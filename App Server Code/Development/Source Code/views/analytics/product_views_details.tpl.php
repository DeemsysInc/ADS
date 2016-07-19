 <style type="text/css">
ul.simple-list li span{
float:right;
}
</style>
<style type="text/css">
/*Only iPhone 5 (portrait mode)*/
@media only screen and (min-device-width : 320px) and (max-device-width : 568px) { 
/* STYLES GO HERE */
.tree{
width:auto;
height: auto;
padding-left: 0px;
}

}
/* Desktops and laptops ----------- */
@media only screen and (min-width: 1224px){

.tree{
width:auto;
height: auto;
padding-left: 0px;
}


}

.tree ul {
  padding-top: 20px; position: relative;
  padding-left: 0px;
  transition: all 0.5s;
  -webkit-transition: all 0.5s;
  -moz-transition: all 0.5s;
}

.tree li {
  float: left; text-align: center;
  list-style-type: none;
  position: relative;
  padding: 20px 5px 0 5px;
  transition: all 0.5s;
  -webkit-transition: all 0.5s;
  -moz-transition: all 0.5s;
}

/*We will use ::before and ::after to draw the connectors*/

.tree li::before, .tree li::after{
  content: '';
  position: absolute; top: 0; right: 50%;
  border-top: 1px solid #ccc;
  width: 50%; height: 20px;
}
.tree li::after{
  right: auto; left: 50%;
  border-left: 1px solid #ccc;
}

/*We need to remove left-right connectors from elements without 
any siblings*/
.tree li:only-child::after, .tree li:only-child::before {
  display: none;
}

/*Remove space from the top of single children*/
.tree li:only-child{ padding-top: 0;}

/*Remove left connector from first child and 
right connector from last child*/
.tree li:first-child::before, .tree li:last-child::after{
  border: 0 none;
}
/*Adding back the vertical connector to the last nodes*/
.tree li:last-child::before{
  border-right: 1px solid #ccc;
  border-radius: 0 5px 0 0;
  -webkit-border-radius: 0 5px 0 0;
  -moz-border-radius: 0 5px 0 0;
}
.tree li:first-child::after{
  border-radius: 5px 0 0 0;
  -webkit-border-radius: 5px 0 0 0;
  -moz-border-radius: 5px 0 0 0;
}

/*Time to add downward connectors from parents*/
.tree ul ul::before{
  content: '';
  position: absolute; top: 0; left: 50%;
  border-left: 1px solid #ccc;
  width: 0; height: 20px;
}

.tree li a{
  border: 1px solid #ccc;
  padding: 15px 30px;
  text-decoration: none;
  color: #666;
  font-family: arial, verdana, tahoma;
  font-size: 11px;
  display: inline-block;
  
  border-radius: 5px;
  -webkit-border-radius: 5px;
  -moz-border-radius: 5px;
  
  transition: all 0.5s;
  -webkit-transition: all 0.5s;
  -moz-transition: all 0.5s;
}

/*Time for some hover effects*/
/*We will apply the hover effect the the lineage of the element also*/
.tree li a:hover, .tree li a:hover+ul li a {
  background: #c8e4f8; color: #000; border: 1px solid #94a0b4;
}
/*Connector styles on hover*/
.tree li a:hover+ul li::after, 
.tree li a:hover+ul li::before, 
.tree li a:hover+ul::before, 
.tree li a:hover+ul ul::before{
  border-color:  #94a0b4;
}
/*Thats all. I hope you enjoyed it.
Thanks :)*/


</style>
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
                            <a href="<?php echo $config['LIVE_URL'];?>analytics/mobile_users/clients/<?php echo isset($arrData['client_id']) ? $arrData['client_id'] : 0;?>/products/<?php echo $_SESSION['start_date'];?>/<?php echo $_SESSION['end_date'];?>">Product list</a>
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
				<form class="form-inline"  method="post"  action="<?php echo $config['LIVE_URL']; ?>">
				 <?php if(isset($_SESSION['user_group']) && $_SESSION['user_group'] !=3){?>
				    <div class="form-group">
						<label class="col-lg-2 col-sm-2 control-label">Clients </label>
						<div class="col-lg-2">
							<select id="byClient" name="byClient" class="populate "  style="width: 203px" Onchange="getCampaigns();">
							<?php //echo "jamuna".$clientSearch?>
								<?php for($i=0;$i<count($outArrClients);$i++){?>
								<?php if($_SESSION['search_client_id']==$outArrClients[$i]['id']){?>
								 <option selected="selected" value="<?php echo isset($outArrClients[$i]['id']) ? $outArrClients[$i]['id'] : 0;?>"><?php echo isset($outArrClients[$i]['name']) ? $outArrClients[$i]['name'] : '';?></option>
								<?php }else{?>
								 <option value="<?php echo isset($outArrClients[$i]['id']) ? $outArrClients[$i]['id'] : 0;?>"><?php echo isset($outArrClients[$i]['name']) ? $outArrClients[$i]['name'] : '';?></option>
								<?php }?>
								<?php }?>                                        
							</select>
						</div>
					</div>
				 
				<?php }?>
				<?php if(isset($_SESSION['user_group']) && $_SESSION['user_group'] !=3){?>
					<div class="form-group">
						<label class="col-sm-3 control-label col-lg-3">Campaigns </label>
						<div class="col-lg-2">
							<select  data-placeholder="Select..." id="byCampaign" name="byCampaign" class="populate "  style="width: 203px" Onchange="getclientCampaigns();">
							<?php// echo "jamuna".$campaignSearch?>
								<option value="0">--Select--</option>
								
								<?php for($i=0;$i<count($arrData[0]['total_campaigns']);$i++){?>
								 <?php if($campaignSearch==$arrData[0]['total_campaigns'][$i]['campaign_id']){?>
								 <option selected="selected" value="<?php echo isset($arrData[0]['total_campaigns'][$i]['campaign_id']) ? $arrData[0]['total_campaigns'][$i]['campaign_id'] : 0;?>"><?php echo isset($arrData[0]['total_campaigns'][$i]['campaign_name']) ? $arrData[0]['total_campaigns'][$i]['campaign_name'] : '';?></option>
								<?php }else{?>
								 <option value="<?php echo isset($arrData[0]['total_campaigns'][$i]['campaign_id']) ? $arrData[0]['total_campaigns'][$i]['campaign_id'] : 0;?>"><?php echo isset($arrData[0]['total_campaigns'][$i]['campaign_name']) ? $arrData[0]['total_campaigns'][$i]['campaign_name'] : '';?></option>
								<?php }?>  
								<?php }?>     										
							</select>
						</div>
					</div>
					<?php }?>
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
                               <!-- <img src="images/lock_thumb.jpg" alt=""/> -->
                                <?php 
                if(isset($arrData['pd_image']) && $arrData['pd_image']!=""){
              
              $dispProductImage=str_replace("{client_id}",$arrData['client_id'],$config['files']['products']).$arrData['pd_image'];
            }else{
              $dispProductImage=$config['LIVE_URL']."views/images/no-product.png";
            }

        ?>    
        <img src="<?php echo $dispProductImage;?>" width="250px" height="250px">
                           </div>
                       </div>
                       <div class="col-md-9">
                           <div class="profile-desk">
                               <h1><?php echo isset($arrData['pd_name']) ? $arrData['pd_name'] : '';?></h1>
                               <!-- <span class="text-muted">Product Manager</span> -->
                               <p>
                                   <div class="prf-box">
                                            <h3 class="prf-border-head">performance status</h3>
                                            <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Users</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php echo isset($arrData['no_of_users']) ? $arrData['no_of_users'] : 0;?></strong>
                                                </div>
                                            </div>
                                            <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Cart</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php echo isset($arrData['cartViews']) ? $arrData['cartViews'] : 0;?></strong>
                                                </div>
                                            </div>
                                            <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Closet</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php echo isset($arrData['closetViews']) ? $arrData['closetViews'] : 0;?></strong>
                                                </div>
                                            </div>
                                             <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Wishlists</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php echo isset($arrData['wishlistViews']) ? $arrData['wishlistViews'] : 0;?></strong>
                                                </div>
                                            </div>
                                             <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Shared</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php echo isset($arrData['shareViews']) ? $arrData['shareViews'] : 0;?></strong>
                                                </div>
                                            </div>
                                             <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Details Page</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php echo isset($arrData['product_detail_views']) ? $arrData['product_detail_views'] : 0;?></strong>
                                                </div>
                                            </div>
                                            <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Scanned</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php echo isset($arrData['scannedViews']) ? $arrData['scannedViews'] : 0;?></strong>
                                                </div>
                                            </div>
                                            <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Total Views</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php echo isset($arrData['totalViews']) ? $arrData['totalViews'] : 0;?></strong>
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
      <a href="javascript:void(0);" onclick="return getClientProductUserInfo('<?php echo $arrData['pd_id'];?>','<?php echo $_SESSION['start_date'];?>','<?php echo $_SESSION['end_date'];?>');"><div><div>Users</div><br><div><h2><?php echo isset($arrData['no_of_users']) ? $arrData['no_of_users'] : 0;?></h2></div></div></a>
      <ul>
        <li> <a href="#" ><div><div>Total views</div><br><div><h2><?php echo isset($arrData['totalViews']) ? $arrData['totalViews'] : 0;?></h2></div></div></a>
         <ul>
        <li>
          <a href="javascript:void(0);" onclick="return getClientProductCartInfo('<?php echo $arrData['pd_id'];?>','<?php echo $_SESSION['start_date'];?>','<?php echo $_SESSION['end_date'];?>');"><div><div>Cart</div><div><h2><?php echo isset($arrData['cartViews']) ? $arrData['cartViews'] : 0;?></h2></div></div></a>
          
        </li>
         <li>
          <a href="javascript:void(0);" onclick="return getClientProductDetailsInfo('<?php echo $arrData['pd_id'];?>','<?php echo $_SESSION['start_date'];?>','<?php echo $_SESSION['end_date'];?>');"><div><div>Details Page</div><div><h2><?php echo isset($arrData['product_detail_views']) ? $arrData['product_detail_views'] : 0;?></h2></div></div></a>
          
        </li>
        <li>
           <a href="javascript:void(0);" onclick="return getClientProductShareInfo('<?php echo $arrData['pd_id'];?>','<?php echo $_SESSION['start_date'];?>','<?php echo $_SESSION['end_date'];?>');"><div><div>Share</div><div><h2><?php echo isset($arrData['shareViews']) ? $arrData['shareViews'] : 0;?></h2></div></div></a>
          <ul>
            <li>
              <a href="javascript:void(0);" onclick="return getClientProductShareFBInfo('<?php echo $arrData['pd_id'];?>','<?php echo $_SESSION['start_date'];?>','<?php echo $_SESSION['end_date'];?>');"><div><div>FB</div><div><h2><?php echo isset($arrData['facebookViews']) ? $arrData['facebookViews'] : 0;?></h2></div></div></a>
            </li>
            <li>
               <a href="javascript:void(0);" onclick="return getClientProductShareTwitterInfo('<?php echo $arrData['pd_id'];?>','<?php echo $_SESSION['start_date'];?>','<?php echo $_SESSION['end_date'];?>');"><div><div>Twitter</div><div><h2><?php echo isset($arrData['twitterViews']) ? $arrData['twitterViews'] : 0;?></h2></div></div></a>
            </li>
            <li>
               <a href="javascript:void(0);" onclick="return getClientProductShareEmailInfo('<?php echo $arrData['pd_id'];?>','<?php echo $_SESSION['start_date'];?>','<?php echo $_SESSION['end_date'];?>');"><div><div>Email</div><div><h2><?php echo isset($arrData['emailViews']) ? $arrData['emailViews'] : 0;?></h2></div></div></a>
            </li>
             <li>
               <a href="javascript:void(0);" onclick="return getClientProductShareSmsInfo('<?php echo $arrData['pd_id'];?>','<?php echo $_SESSION['start_date'];?>','<?php echo $_SESSION['end_date'];?>');"><div><div>SMS</div><div><h2><?php echo isset($arrData['smsViews']) ? $arrData['smsViews'] : 0;?></h2></div></div></a>
            </li>
          </ul>
        </li>
        <li>
           <a href="javascript:void(0);" onclick="return getClientProductClosetInfo('<?php echo $arrData['pd_id'];?>','<?php echo $_SESSION['start_date'];?>','<?php echo $_SESSION['end_date'];?>');"><div><div>Closet</div><div><h2><?php echo isset($arrData['closetViews']) ? $arrData['closetViews'] : 0;?></h2></div></div></a>
        </li>
        <li> 
             <a href="javascript:void(0);" onclick="return getClientProductScannedInfo('<?php echo $arrData['pd_id'];?>','<?php echo $_SESSION['start_date'];?>','<?php echo $_SESSION['end_date'];?>');"><div><div>Scanned</div><div><h2><?php echo isset($arrData['scannedViews']) ? $arrData['scannedViews'] : 0;?></h2></div></div></a>
        </li>
        <li>
            <a href="javascript:void(0);" onclick="return getClientProductWishlistInfo('<?php echo $arrData['pd_id'];?>','<?php echo $_SESSION['start_date'];?>','<?php echo $_SESSION['end_date'];?>');"><div><div>Wishlists</div><div><h2><?php echo isset($arrData['wishlistViews']) ? $arrData['wishlistViews'] : 0;?></h2></div></div></a>
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