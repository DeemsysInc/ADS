<!--main content start-->
<section id="main-content">
<section class="wrapper">
<div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumbs-alt">
                        <li>
                            <a class="current" href="<?php echo $config['LIVE_URL'];?>">Dashboard</a>
                        </li>                        
                    </ul>
                </div>
</div>
 <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <div class="panel-body">
                        <form class="form-inline"  method="post"  action="<?php echo $config['LIVE_URL']; ?>">
                            <!-- <div class="form-group">
                                <label class="col-lg-2 col-sm-2 control-label">Campaigns</label>
                                <div class="col-lg-2">
                                    <select id="byCampaign" name="byCampaign" class="populate "  style="width: 203px" Onchange="return getDateranges();">
                                        <option value="">--Select--</option>
                                        
                                        <?php for($i=0;$i<count($arrData[0]['client_campaigns']);$i++){?>
                                         <option value="<?php echo isset($arrData[0]['client_campaigns'][$i]['campaign_id']) ? $arrData[0]['client_campaigns'][$i]['campaign_id'] : 0;?>"><?php echo isset($arrData[0]['client_campaigns'][$i]['campaign_name']) ? $arrData[0]['client_campaigns'][$i]['campaign_name'] : '';?></option>
                                        <?php }?>                                        
                                    </select>
                                </div>
                            </div> -->
                            <?php if(isset($_SESSION['user_group']) && $_SESSION['user_group'] !=3){?>
                            <div class="form-group">
                                <label class="col-lg-2 col-sm-2 control-label">Clients </label>
                                <div class="col-lg-2">
                                    <select id="byClient" name="byClient" class="populate "  style="width: 203px">
                                        <?php for($i=0;$i<count($arrData[0]['total_clients']);$i++){?>
                                        <?php if($clientSearch==$arrData[0]['total_clients'][$i]['id']){?>
                                         <option selected="selected" value="<?php echo isset($arrData[0]['total_clients'][$i]['id']) ? $arrData[0]['total_clients'][$i]['id'] : 0;?>"><?php echo isset($arrData[0]['total_clients'][$i]['name']) ? $arrData[0]['total_clients'][$i]['name'] : '';?></option>
                                        <?php }else{?>
                                         <option value="<?php echo isset($arrData[0]['total_clients'][$i]['id']) ? $arrData[0]['total_clients'][$i]['id'] : 0;?>"><?php echo isset($arrData[0]['total_clients'][$i]['name']) ? $arrData[0]['total_clients'][$i]['name'] : '';?></option>
                                        <?php }?>
                                        <?php }?>                                        
                                    </select>
                                </div>
                            </div>
                            <?php }?>
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


<!--mini statistics start-->
<div class="row">
    <div class="col-md-3">
        <section class="panel">
            <div class="panel-body">
                <div class="top-stats-panel">
                    <h4 class="widget-h">Product views</h4>
                   <div id="chart_products_views" style="width: auto;height: 250px"><div align="center"><img src="<?php echo $config['LIVE_URL']; ?>views/images/ajax-loader.gif" width="100" height="100"></div></div>
       
                </div>
            </div>
        </section>
    </div>
    <div class="col-md-3">
        <section class="panel">
            <div class="panel-body">
                <div class="top-stats-panel">
                    <h4 class="widget-h">Offer views</h4>
                   <div id="chart_offers_views" style="width: auto;height: 250px"><div align="center"><img src="<?php echo $config['LIVE_URL']; ?>views/images/ajax-loader.gif" width="100" height="100"></div></div>
                </div>
            </div>
        </section>
    </div>
    <div class="col-md-3">
        <section class="panel">
            <div class="panel-body">
                 <h4 class="widget-h">Product Shared</h4>
               <div id="chart_products_shared" style="width: auto;height: 250px"><div align="center"><img src="<?php echo $config['LIVE_URL']; ?>views/images/ajax-loader.gif" width="100" height="100"></div></div>
            </div>
        </section>
    </div>
    <div class="col-md-3">
        <section class="panel">
            <div class="panel-body">
                 <h4 class="widget-h">Offer Shared</h4>
               <div id="chart_offers_shared" style="width: auto;height: 250px" ><div align="center"><img src="<?php echo $config['LIVE_URL']; ?>views/images/ajax-loader.gif" width="100" height="100"></div></div>
            </div>
        </section>
    </div>
</div>
<!--mini statistics end-->

<!--mini statistics start-->
<div class="row">
    <div class="col-md-3">
        <a href="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/clients/<?php echo $arrData[0]['client_info'][0]['id'];?>/products/<?php echo $start_date;?>/<?php echo $end_date;?>">
        <div class="mini-stat clearfix">
            <span class="mini-stat-icon orange"><i class="fa fa-gavel"></i></span>
            <div class="mini-stat-info">
                <span><?php echo isset($arrData[0]['productViews']) ? $arrData[0]['productViews'] : 0;?></span>
                Product Views
            </div>
        </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/clients/<?php echo $arrData[0]['client_info'][0]['id'];?>/offers/<?php echo $start_date;?>/<?php echo $end_date;?>">
        <div class="mini-stat clearfix">
            <span class="mini-stat-icon tar"><i class="fa fa-tag"></i></span>
            <div class="mini-stat-info">
                <span><?php echo isset($arrData[0]['offerViews']) ? $arrData[0]['offerViews'] : 0;?></span>
                Offer Views
            </div>
        </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/clients/<?php echo $arrData[0]['client_info'][0]['id'];?>/products/<?php echo $start_date;?>/<?php echo $end_date;?>">
        <div class="mini-stat clearfix">
            <span class="mini-stat-icon pink"><i class="fa fa-money"></i></span>
            <div class="mini-stat-info">
                <span><?php echo isset($arrData[0]['product_shareViews']) ? $arrData[0]['product_shareViews'] : 0;?></span>
                Products Shared
            </div>
        </div>
        </a>
    </div>
    <div class="col-md-3">
         <a href="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/clients/<?php echo $arrData[0]['client_info'][0]['id'];?>/offers/<?php echo $start_date;?>/<?php echo $end_date;?>">
        <div class="mini-stat clearfix">
            <span class="mini-stat-icon green"><i class="fa fa-eye"></i></span>
            <div class="mini-stat-info">
                <span><?php echo isset($arrData[0]['offer_shareViews']) ? $arrData[0]['offer_shareViews'] : 0;?></span>
                Offers Shared
            </div>
        </div>
        </a>
    </div>
</div>
<!--mini statistics end-->

<?php if(isset($_SESSION['user_group']) && $_SESSION['user_group']=='2'){//ADMINISTRATOR?>
<div class="row">
<div class="col-md-6">
    <!--notification start-->
    <section class="panel">
        <header class="panel-heading">
            Recent Clients <span class="tools pull-right">
            <a href="javascript:;" class="fa fa-chevron-down"></a>
            <a href="javascript:;" class="fa fa-cog"></a>
            <a href="javascript:;" class="fa fa-times"></a>
            </span>
        </header>
        <div class="panel-body">
           
             <table class="table">
                                <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Products Added</th>
                                    <th>Triggers Added</th>
                                </tr>
                                </thead>
                                <tbody>
                                 <?php for($i=0;$i<4;$i++) {?>
            <?php if($outArrClients[$i]['logo']!=""){
                        
                        $dispClient='<img src="'.str_replace("{client_id}",$outArrClients[$i]['id'],$config['files']['logo']).$outArrClients[$i]['logo'].'" height="40"/>';
                    }else{
                        $dispClient='<p style="width: 220px;font-size: 28px;text-align: center;">'.$outArrClients[$i]['name'].'</p>';
                    }?>
                                <tr>
                                   <!--  <td style="cursor: pointer; cursor: hand;" onclick="document.location = '<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClients[$i]['id']; ?>/';"><?php echo $dispClient; ?></td>
                                    <td style="cursor: pointer; cursor: hand;" onclick="document.location = '<?php echo $config['LIVE_URL'];?>clients/id/<?php echo $outArrClients[$i]['id'];?>/products/';"><p style="text-align:center;font-size:20px;color:black;"><?php echo count($outArrProductsTot[$i]);?></p></td>
                                    <td style="cursor: pointer; cursor: hand;" onclick="document.location = '<?php echo $config['LIVE_URL'];?>clients/id/<?php echo $outArrClients[$i]['id'];?>/triggers/';"><p style="text-align:center;font-size:20px;color:black;"><?php echo count($outArrTriggersTot[$i]);?></p></td> -->
                                     <td style="cursor: pointer; cursor: hand;"><?php echo $dispClient; ?></td>
                                    <td style="cursor: pointer; cursor: hand;"><p style="text-align:center;font-size:20px;color:black;"><?php echo count($outArrProductsTot[$i]);?></p></td>
                                    <td style="cursor: pointer; cursor: hand;" ><p style="text-align:center;font-size:20px;color:black;"><?php echo count($outArrTriggersTot[$i]);?></p></td>
                                </tr>
                                <?php }?>
                                </tbody>
                            </table>
        


            <!-- <div class="alert alert-info clearfix">
                <span class="alert-icon"><?php echo $dispClient; ?></span>
                <div class="notification-info">
                    <ul class="clearfix notification-meta">
                        <li class="pull-left notification-sender"><span><a href="#">Jonathan Smith</a></span> send you a mail </li>
                        <li class="pull-right notification-time">1 min ago</li>
                    </ul>
                    <p>
                        Urgent meeting for next proposal
                    </p>
                </div>
            </div> -->
            <!-- <div align="right"><button onclick="document.location = '<?php echo $config['LIVE_URL']; ?>clients/>';" type="submit" class="btn btn-default btn-primary" > More Clients</button></div> -->
            <div align="right"><button type="submit" class="btn btn-default btn-primary" > More Clients</button></div>
            
        </div>
    </section>
    <!--notification end-->
</div>
<div class="col-md-6">
    <!--todolist start-->
    <section class="panel">
        <header class="panel-heading">
            Recent App Users <span class="tools pull-right">
            <a href="javascript:;" class="fa fa-chevron-down"></a>
            <a href="javascript:;" class="fa fa-cog"></a>
            <a href="javascript:;" class="fa fa-times"></a>
            </span>
        </header>
        <div class="panel-body">
            <ul class="to-do-list" id="sortable-todo">
                <?php for($i=0;$i<4;$i++) {?>
               <!-- <a href="<?php echo $config['LIVE_URL']; ?>appusers/profile/id/<?php echo isset($outArrAppUsers[$i]['user_id']) ? $outArrAppUsers[$i]['user_id']: ''; ?>/view/">-->
                    
                    <li class="clearfix">
                        <div class="todo-check pull-left" style="width:70px;">
                            <img src="<?php echo $config['LIVE_URL']; ?>views/images/Profile.png" alt="avatar" >
                        </div>
                        <p></p>
                        <p class="todo-title">
                            <?php echo $outArrAppUsers[$i]['user_firstname']." ".$outArrAppUsers[$i]['user_lastname']?>
                        </p>
                        
                    </li>
                <!--</a>-->
                <?php }?>
                
            </ul>
           <!-- <div align="right"><button onclick="document.location = '<?php echo $config['LIVE_URL']; ?>appusers/>';" type="submit" class="btn btn-default btn-primary" > More App Users</button></div> -->
           <div align="right"><button type="submit" class="btn btn-default btn-primary" > More App Users</button></div>
            
        </div>
    </section>
    <!--todolist end-->
</div>
</div>
<?php }?>

</section>
</section>
<!--main content end-->
<!--right sidebar start-->
<div class="right-sidebar">
<div class="search-row">
    <input type="text" placeholder="Search" class="form-control">
</div>
<div class="right-stat-bar">
<ul class="right-side-accordion">
<li class="widget-collapsible">
    <a href="#" class="head widget-head red-bg active clearfix">
        <span class="pull-left">work progress (5)</span>
        <span class="pull-right widget-collapse"><i class="ico-minus"></i></span>
    </a>
    <ul class="widget-container">
        <li>
            <div class="prog-row side-mini-stat clearfix">
                <div class="side-graph-info">
                    <h4>Target sell</h4>
                    <p>
                        25%, Deadline 12 june 13
                    </p>
                </div>
                <div class="side-mini-graph">
                    <div class="target-sell">
                    </div>
                </div>
            </div>
            <div class="prog-row side-mini-stat">
                <div class="side-graph-info">
                    <h4>product delivery</h4>
                    <p>
                        55%, Deadline 12 june 13
                    </p>
                </div>
                <div class="side-mini-graph">
                    <div class="p-delivery">
                        <div class="sparkline" data-type="bar" data-resize="true" data-height="30" data-width="90%" data-bar-color="#39b7ab" data-bar-width="5" data-data="[200,135,667,333,526,996,564,123,890,564,455]">
                        </div>
                    </div>
                </div>
            </div>
            <div class="prog-row side-mini-stat">
                <div class="side-graph-info payment-info">
                    <h4>payment collection</h4>
                    <p>
                        25%, Deadline 12 june 13
                    </p>
                </div>
                <div class="side-mini-graph">
                    <div class="p-collection">
						<span class="pc-epie-chart" data-percent="45">
						<span class="percent"></span>
						</span>
                    </div>
                </div>
            </div>
            <div class="prog-row side-mini-stat">
                <div class="side-graph-info">
                    <h4>delivery pending</h4>
                    <p>
                        44%, Deadline 12 june 13
                    </p>
                </div>
                <div class="side-mini-graph">
                    <div class="d-pending">
                    </div>
                </div>
            </div>
            <div class="prog-row side-mini-stat">
                <div class="col-md-12">
                    <h4>total progress</h4>
                    <p>
                        50%, Deadline 12 june 13
                    </p>
                    <div class="progress progress-xs mtop10">
                        <div style="width: 50%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="20" role="progressbar" class="progress-bar progress-bar-info">
                            <span class="sr-only">50% Complete</span>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    </ul>
</li>
<li class="widget-collapsible">
    <a href="#" class="head widget-head terques-bg active clearfix">
        <span class="pull-left">contact online (5)</span>
        <span class="pull-right widget-collapse"><i class="ico-minus"></i></span>
    </a>
    <ul class="widget-container">
        <li>
            <div class="prog-row">
                <div class="user-thumb">
                    <a href="#"><img src="<?php echo $config['LIVE_URL']; ?>views/images/avatar1_small.jpg" alt=""></a>
                </div>
                <div class="user-details">
                    <h4><a href="#">Jonathan Smith</a></h4>
                    <p>
                        Work for fun
                    </p>
                </div>
                <div class="user-status text-danger">
                    <i class="fa fa-comments-o"></i>
                </div>
            </div>
            <div class="prog-row">
                <div class="user-thumb">
                    <a href="#"><img src="<?php echo $config['LIVE_URL']; ?>views/images/avatar1.jpg" alt=""></a>
                </div>
                <div class="user-details">
                    <h4><a href="#">Anjelina Joe</a></h4>
                    <p>
                        Available
                    </p>
                </div>
                <div class="user-status text-success">
                    <i class="fa fa-comments-o"></i>
                </div>
            </div>
            <div class="prog-row">
                <div class="user-thumb">
                    <a href="#"><img src="<?php echo $config['LIVE_URL']; ?>views/images/chat-avatar2.jpg" alt=""></a>
                </div>
                <div class="user-details">
                    <h4><a href="#">John Doe</a></h4>
                    <p>
                        Away from Desk
                    </p>
                </div>
                <div class="user-status text-warning">
                    <i class="fa fa-comments-o"></i>
                </div>
            </div>
            <div class="prog-row">
                <div class="user-thumb">
                    <a href="#"><img src="<?php echo $config['LIVE_URL']; ?>views/images/avatar1_small.jpg" alt=""></a>
                </div>
                <div class="user-details">
                    <h4><a href="#">Mark Henry</a></h4>
                    <p>
                        working
                    </p>
                </div>
                <div class="user-status text-info">
                    <i class="fa fa-comments-o"></i>
                </div>
            </div>
            <div class="prog-row">
                <div class="user-thumb">
                    <a href="#"><img src="<?php echo $config['LIVE_URL']; ?>views/images/avatar1.jpg" alt=""></a>
                </div>
                <div class="user-details">
                    <h4><a href="#">Shila Jones</a></h4>
                    <p>
                        Work for fun
                    </p>
                </div>
                <div class="user-status text-danger">
                    <i class="fa fa-comments-o"></i>
                </div>
            </div>
            <p class="text-center">
                <a href="#" class="view-btn">View all Contacts</a>
            </p>
        </li>
    </ul>
</li>
<li class="widget-collapsible">
    <a href="#" class="head widget-head purple-bg active">
        <span class="pull-left"> recent activity (3)</span>
        <span class="pull-right widget-collapse"><i class="ico-minus"></i></span>
    </a>
    <ul class="widget-container">
        <li>
            <div class="prog-row">
                <div class="user-thumb rsn-activity">
                    <i class="fa fa-clock-o"></i>
                </div>
                <div class="rsn-details ">
                    <p class="text-muted">
                        just now
                    </p>
                    <p>
                        <a href="#">Jim Doe </a>Purchased new equipments for zonal office setup
                    </p>
                </div>
            </div>
            <div class="prog-row">
                <div class="user-thumb rsn-activity">
                    <i class="fa fa-clock-o"></i>
                </div>
                <div class="rsn-details ">
                    <p class="text-muted">
                        2 min ago
                    </p>
                    <p>
                        <a href="#">Jane Doe </a>Purchased new equipments for zonal office setup
                    </p>
                </div>
            </div>
            <div class="prog-row">
                <div class="user-thumb rsn-activity">
                    <i class="fa fa-clock-o"></i>
                </div>
                <div class="rsn-details ">
                    <p class="text-muted">
                        1 day ago
                    </p>
                    <p>
                        <a href="#">Jim Doe </a>Purchased new equipments for zonal office setup
                    </p>
                </div>
            </div>
        </li>
    </ul>
</li>
<li class="widget-collapsible">
    <a href="#" class="head widget-head yellow-bg active">
        <span class="pull-left"> shipment status</span>
        <span class="pull-right widget-collapse"><i class="ico-minus"></i></span>
    </a>
    <ul class="widget-container">
        <li>
            <div class="col-md-12">
                <div class="prog-row">
                    <p>
                        Full sleeve baby wear (SL: 17665)
                    </p>
                    <div class="progress progress-xs mtop10">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                            <span class="sr-only">40% Complete</span>
                        </div>
                    </div>
                </div>
                <div class="prog-row">
                    <p>
                        Full sleeve baby wear (SL: 17665)
                    </p>
                    <div class="progress progress-xs mtop10">
                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 70%">
                            <span class="sr-only">70% Completed</span>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    </ul>
</li>
</ul>

</div>

</div>
<!--right sidebar end-->
</section>

<script src="http://www.google.com/jsapi"></script>
    <script>
        
        
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChartProductsTrack);
  function drawChartProductsTrack() {
    var data = new google.visualization.DataTable();

    <!-- Create the data table -->
    data.addColumn('string', 'Day');
    data.addColumn('number', 'Product Views');

    data.addRows([
     <?php
     for($i=0;$i<count($arrData[0]['graph_results']);$i++)
     {
         echo '["'.date('M j,Y',strtotime($arrData[0]['graph_results'][$i]['dates'])).'", '.$arrData[0]['graph_results'][$i]['productids'].'],';
     }
      ?>
    ]);

   
     var chart = new google.visualization.AreaChart(document.getElementById('chart_products_views'));
    chart.draw(data, {title: '',
                      colors:['#058dc7','#e6f4fa'],
                      areaOpacity: 0.1,
                      hAxis: {textPosition: 'bottom', showTextEvery: 20, slantedText: false, textStyle: { color: '#058dc7', fontSize: 10 } },
                      pointSize: 7,
                      legend: 'right'
    });
    
  }
  // google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChartOffersTrack);
  function drawChartOffersTrack() {
    var data = new google.visualization.DataTable();

    <!-- Create the data table -->
    data.addColumn('string', 'Day');
    data.addColumn('number', 'Offer Views');

    data.addRows([
    <?php
     for($i=0;$i<count($arrData[0]['graph_results']);$i++){
     echo '["'.date('M j,Y',strtotime($arrData[0]['graph_results'][$i]['dates'])).'", '.$arrData[0]['graph_results'][$i]['offerids'].'],';
     }?>
     ]);

   
     var chart = new google.visualization.AreaChart(document.getElementById('chart_offers_views'));
    chart.draw(data, {title: '',
                      colors:['#058dc7','#e6f4fa'],
                      areaOpacity: 0.1,
                      hAxis: {textPosition: 'bottom', showTextEvery: 20, slantedText: false, textStyle: { color: '#058dc7', fontSize: 10 } },
                      pointSize: 7,
                      legend: 'right'
    });
    
  }
  /////////
  google.setOnLoadCallback(drawChartProductsSharedTrack);
  function drawChartProductsSharedTrack() {
    var data = new google.visualization.DataTable();

    <!-- Create the data table -->
    data.addColumn('string', 'Day');
    data.addColumn('number', 'Products Shared');

    data.addRows([
     <?php
     for($i=0;$i<count($arrData[0]['graph_results']);$i++)
     {
         echo '["'.date('M j,Y',strtotime($arrData[0]['graph_results'][$i]['dates'])).'", '.$arrData[0]['graph_results'][$i]['product_shareViews'].'],';
     }
      ?>
    ]);

   
     var chart = new google.visualization.AreaChart(document.getElementById('chart_products_shared'));
    chart.draw(data, {title: '',
                      colors:['#058dc7','#e6f4fa'],
                      areaOpacity: 0.1,
                      hAxis: {textPosition: 'bottom', showTextEvery: 20, slantedText: false, textStyle: { color: '#058dc7', fontSize: 10 } },
                      pointSize: 7,
                      legend: 'right'
    });
    
  }
 // google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChartOffersSharedTrack);
  function drawChartOffersSharedTrack() {
    var data = new google.visualization.DataTable();

    <!-- Create the data table -->
    data.addColumn('string', 'Day');
    data.addColumn('number', 'Offers Shared');

    data.addRows([
    <?php
     for($i=0;$i<count($arrData[0]['graph_results']);$i++){
     echo '["'.date('M j,Y',strtotime($arrData[0]['graph_results'][$i]['dates'])).'", '.$arrData[0]['graph_results'][$i]['offer_shareViews'].'],';
     }?>
     ]);

   
     var chart = new google.visualization.AreaChart(document.getElementById('chart_offers_shared'));
    chart.draw(data, { title: '',
                      colors:['#058dc7','#e6f4fa'],
                      areaOpacity: 0.1,
                      hAxis: {textPosition: 'bottom', showTextEvery: 20, slantedText: false, textStyle: { color: '#058dc7', fontSize: 10 } },
                      pointSize: 7,
                      legend: 'right'
    });
    
  }
 </script>

