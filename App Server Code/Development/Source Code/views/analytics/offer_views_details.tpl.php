 <style type="text/css">
ul.simple-list li span{
float:right;
}
</style>
<style type="text/css">
@media only screen and (min-device-width : 320px) and (max-device-width : 568px) { 
/* STYLES GO HERE */
.tree{
width:auto;
height: auto;
padding-left: 0px;
}

}

@media only screen and (min-width: 1224px){
.tree{
width:auto;
height: auto;
/*padding-left: 177px;*/
}



}

.tree ul {
  padding-top: 20px; position: relative;
  
  transition: all 0.5s;
  -webkit-transition: all 0.5s;
  -moz-transition: all 0.5s;
}

.tree li {
  float: left; text-align: center;
  list-style-type: none;
  position: relative;
  padding: 20px 0px 0 5px;
  
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
  padding: 15px 40px;
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
                            <a href="<?php echo $config['LIVE_URL'];?>analytics/mobile_users/clients/<?php echo isset($arrData[0]['client_id']) ? $arrData[0]['client_id'] : 0;?>/offers/<?php echo $start_date;?>/<?php echo $end_date;?>">Offers list</a>
                        </li>
                        <li>
                            <a class="current" href="#">Offer Details</a>
                        </li>
                        
                    </ul>
                </div>
</div> 
<div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <div class="panel-body">
                        <form class="form-inline"  method="post"  action="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/offers/flow/<?php echo isset($arrData[0]['offer_id']) ? $arrData[0]['offer_id'] : 0;?>">
                           
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
                        Offer Details
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
                if(isset($arrData[0]['offer_image']) && $arrData[0]['offer_image']!=""){
              
              $dispOfferImage=str_replace("{client_id}",$arrData[0]['client_id'],$config['files']['products']).$arrData[0]['offer_image'];
            }else{
              $dispOfferImage=$config['LIVE_URL']."views/images/no-product.png";
            }

        ?>    
        <img src="<?php echo $dispOfferImage;?>" width="250px" height="250px">
                           </div>
                       </div>
                       <div class="col-md-9">
                           <div class="profile-desk">
                               <h1><?php echo isset($arrData[0]['offer_name']) ? $arrData[0]['offer_name'] : '';?></h1>
                               <!-- <span class="text-muted">Product Manager</span> -->
                               <p>
                                   <div class="prf-box">
                                            <h3 class="prf-border-head">&nbsp;</h3>
                                            <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Users</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php echo isset($arrData[0]['no_of_users']) ? $arrData[0]['no_of_users'] : '0';?></strong>
                                                </div>
                                            </div>
                                            <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Remove</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php echo isset($arrData[0]['removedViews']) ? $arrData[0]['removedViews'] : 0;?></strong>
                                                </div>
                                            </div>
                                            <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Redeem</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php echo isset($arrData[0]['redeemViews']) ? $arrData[0]['redeemViews'] : 0;?></strong>
                                                </div>
                                            </div>
                                             <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Apply</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php echo isset($arrData[0]['applyViews']) ? $arrData[0]['applyViews'] : 0;?></strong>
                                                </div>
                                            </div>
                                             <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Share</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php echo isset($arrData[0]['shareViews']) ? $arrData[0]['shareViews'] : 0;?></strong>
                                                </div>
                                            </div>
                                             <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Scanned</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php echo isset($arrData[0]['scannedViews']) ? $arrData[0]['scannedViews'] : 0;?></strong>
                                                </div>
                                            </div>
                                             <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">My Offers</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php echo isset($arrData[0]['myOfferViews']) ? $arrData[0]['myOfferViews'] : 0;?></strong>
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
                        Offer flow
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
       <!-- <a href="javascript:void(0);" onclick="return getClientOffersFlowInfoDetails('<?php echo $offerId;?>','<?php echo $start_date;?>','<?php echo $end_date;?>','users');"><div><div style="font-size:17px;">Users</div><div><h2><?php echo isset($arrData[0]['no_of_users']) ? $arrData[0]['no_of_users'] : '0';?><h2></div></div></a> -->
      <a href="javascript:void(0);" onclick="return getClientOffersFlowInfoDetails('<?php echo $offerId;?>','<?php echo $start_date;?>','<?php echo $end_date;?>','users');"><div><div style="font-size:17px;">Users</div><div><h2><?php echo isset($arrData[0]['no_of_users']) ? $arrData[0]['no_of_users'] : '0';?><h2></div></div></a>

      <ul>
        <li>
          <a href="javascript:void(0);" onclick="return getClientOffersFlowInfoDetails('<?php echo $offerId;?>','<?php echo $start_date;?>','<?php echo $end_date;?>','myoffers');"><div><div style="font-size:17px;">My Offers</div><div><h2><?php echo isset($arrData[0]['myOfferViews']) ? $arrData[0]['myOfferViews'] : '0';?><h2></div></div></a>
          <ul>
            <!-- <li><a href="#"><div><div style="font-size:17px;">Add</div><div><h2><?php echo isset($arrData[0]['addViews']) ? $arrData[0]['addViews'] : '0';?><h2></div></div></a></li>
             --><li><a href="javascript:void(0);" onclick="return getClientOffersFlowInfoDetails('<?php echo $offerId;?>','<?php echo $start_date;?>','<?php echo $end_date;?>','remove');"><div><div style="font-size:17px;">Remove</div><div><h2><?php echo isset($arrData[0]['removedViews']) ? $arrData[0]['removedViews'] : '0';?><h2></div></div></a></li>
            <li><a href="javascript:void(0);" onclick="return getClientOffersFlowInfoDetails('<?php echo $offerId;?>','<?php echo $start_date;?>','<?php echo $end_date;?>','redeem');"><div><div style="font-size:17px;">Redeem</div><div><h2><?php echo isset($arrData[0]['redeemViews']) ? $arrData[0]['redeemViews'] : '0';?><h2></div></div></a></li>
          </ul>
        </li>
        <li>
           <a href="javascript:void(0);" onclick="return getClientOffersFlowInfoDetails('<?php echo $offerId;?>','<?php echo $start_date;?>','<?php echo $end_date;?>','share');"><div><div style="font-size:17px;">Share</div><div><h2><?php echo isset($arrData[0]['shareViews']) ? $arrData[0]['shareViews'] : '0';?><h2></div></div></a>
          <ul>
            <li><a href="javascript:void(0);" onclick="return getClientOffersFlowInfoDetails('<?php echo $offerId;?>','<?php echo $start_date;?>','<?php echo $end_date;?>','facebook');"><div><div style="font-size:17px;">FB</div><div><h2><?php echo isset($arrData[0]['facebookViews']) ? $arrData[0]['facebookViews'] : '0';?><h2></div></div></a></li>
            <li><a href="javascript:void(0);" onclick="return getClientOffersFlowInfoDetails('<?php echo $offerId;?>','<?php echo $start_date;?>','<?php echo $end_date;?>','twitter');"><div><div style="font-size:17px;">Twitter</div><div><h2><?php echo isset($arrData[0]['twitterViews']) ? $arrData[0]['twitterViews'] : 0;?><h2></div></div></a></li>
            <li><a href="javascript:void(0);" onclick="return getClientOffersFlowInfoDetails('<?php echo $offerId;?>','<?php echo $start_date;?>','<?php echo $end_date;?>','email');"><div><div style="font-size:17px;">Email</div><div><h2><?php echo isset($arrData[0]['emailViews']) ? $arrData[0]['emailViews'] : 0;?><h2></div></div></a></li>
            <li><a href="javascript:void(0);" onclick="return getClientOffersFlowInfoDetails('<?php echo $offerId;?>','<?php echo $start_date;?>','<?php echo $end_date;?>','sms');"><div><div style="font-size:17px;">SMS</div><div><h2><?php echo isset($arrData[0]['smsViews']) ? $arrData[0]['smsViews'] : 0;?><h2></div></div></a></li>
          </ul>
        </li>
        <li>
           <a href="javascript:void(0);" onclick="return getClientOffersFlowInfoDetails('<?php echo $offerId;?>','<?php echo $start_date;?>','<?php echo $end_date;?>','scanned');"><div><div style="font-size:17px;">Scanned</div><div><h2><?php echo isset($arrData[0]['scannedViews']) ? $arrData[0]['scannedViews'] : '0';?><h2></div></div></a>
        </li>
        <!-- <li>
          <a href="#"><div><div style="font-size:17px;">Deleted</div><div><h2><?php echo isset($arrData[0]['removedViews']) ? $arrData[0]['removedViews'] : '0';?><h2></div></div></a>
          
        </li> -->
        
      </ul>
     
    </li>
  </ul>
</div>  

                    </div>
                </section>
            </div>

            <div class="clear"></div>
<div id="displayOfferFlowDet"></div>
 

            
        </div>
        <!-- page end-->
        </section>
    </section>
    <!--main content end-->