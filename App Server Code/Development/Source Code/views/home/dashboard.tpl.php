
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
						  <input size="25" id="from-to-date-range" name="from-to-date-range" class="form-control"  value="<?php echo $_SESSION['start_date'];?> to <?php echo $_SESSION['end_date'];?>">
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
        <a href="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/clients/products/list">
        <div class="mini-stat clearfix">
            <span class="mini-stat-icon orange"><i class="fa fa-gavel"></i></span>
            <div class="mini-stat-info">
                <span><div id="productview"><img src="<?php echo $config['LIVE_URL']; ?>views/images/loader.gif" width="20" height="20"><?php //echo isset($arrData[0]['productViews']) ? $arrData[0]['productViews'] : 0;?></div></span>
                Product Views
            </div>
        </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/clients/offers/list">
        <div class="mini-stat clearfix">
            <span class="mini-stat-icon tar"><i class="fa fa-tag"></i></span>
            <div class="mini-stat-info">
                <span><div id="offerview"><img src="<?php echo $config['LIVE_URL']; ?>views/images/loader.gif" width="20" height="20"><?php// echo isset($arrData[0]['offerViews']) ? $arrData[0]['offerViews'] : 0;?></div></span>
                Offer Views
            </div>
        </div>
        </a>
    </div> 
  <!-- <div class="col-md-3">
        <a href="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/clients/<?php echo $_SESSION['search_client_id'];?>/productsShares/<?php echo $start_date;?>/<?php echo $end_date;?>">
        <div class="mini-stat clearfix">
            <span class="mini-stat-icon pink"><i class="fa fa-money"></i></span>
            <div class="mini-stat-info">
                <span><div id="productshareview"><img src="<?php echo $config['LIVE_URL']; ?>views/images/loader.gif" width="20" height="20"><?php //echo isset($arrData[0]['product_shareViews']) ? $arrData[0]['product_shareViews'] : 0;?></div></span>
                Products Shared
            </div>
        </div>
        </a>
    </div> -->
	 <div class="col-md-3">
        <a href="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/clients/<?php echo $_SESSION['search_client_id'];?>/products/<?php echo $start_date;?>/<?php echo $end_date;?>">
        <div class="mini-stat clearfix">
            <span class="mini-stat-icon pink"><i class="fa fa-money"></i></span>
            <div class="mini-stat-info">
                <span><div id="productshareview"><img src="<?php echo $config['LIVE_URL']; ?>views/images/loader.gif" width="20" height="20"><?php //echo isset($arrData[0]['product_shareViews']) ? $arrData[0]['product_shareViews'] : 0;?></div></span>
                Products Shared
            </div>
        </div>
        </a>
    </div> 
	
    <div class="col-md-3">
       <a href="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/clients/<?php echo $_SESSION['search_client_id'];?>/offers/<?php echo $start_date;?>/<?php echo $end_date;?>">
            <div class="mini-stat clearfix">
                 <span class="mini-stat-icon green"><i class="fa fa-eye"></i></span>
                    <div class="mini-stat-info">
                       <span><div id="offershareview"><img src="<?php echo $config['LIVE_URL']; ?>views/images/loader.gif" width="20" height="20"><?php //echo isset($arrData[0]['offer_shareViews']) ? $arrData[0]['offer_shareViews'] : 0;?></div></span>
                            Offers Shared
                    </div>
            </div>
        </a>
    </div>
	<!-- <div class="col-md-3">
         <a href="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/clients/<?php echo $arrData[0]['client_info'][0]['id'];?>/offerShares/<?php echo $start_date;?>/<?php echo $end_date;?>">
        <div class="mini-stat clearfix">
            <span class="mini-stat-icon green"><i class="fa fa-eye"></i></span>
            <div class="mini-stat-info">
                <span><div id="offershareview"><img src="<?php echo $config['LIVE_URL']; ?>views/images/loader.gif" width="20" height="20"><?php //echo isset($arrData[0]['offer_shareViews']) ? $arrData[0]['offer_shareViews'] : 0;?></div></span>
                Offers Shared
            </div>
        </div>
        </a>
    </div>-->
</div>

<!--mini statistics end-->

<?php if(isset($_SESSION['user_group']) && $_SESSION['user_group']=='2'){//ADMINISTRATOR?>
<!--mini statistics start-->
<div class="row">
    <div class="col-md-3">
      <a href="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/downloads/<?php echo $start_date;?>/<?php echo $end_date;?>">
        <div class="mini-stat clearfix">
            <span class="mini-stat-icon orange"><i class="fa fa-download"></i></span>
            <div class="mini-stat-info">
                <span><div id="downloads"><img src="<?php echo $config['LIVE_URL']; ?>views/images/loader.gif" width="20" height="20"></div><?php //echo isset($arrData[0]['total_downloads']) ? $arrData[0]['total_downloads'] : 0;?></span>
                Downloads
            </div>
        </div>
       </a>
    </div>

<!--mini statistics end-->
<!--mini statistics start-->

    <div class="col-md-3">
        <div class="mini-stat clearfix">
            <span class="mini-stat-icon tar"><i class="fa fa-users"></i></span>
            <div class="mini-stat-info">
                <span><div id="online"><?php echo isset($arrData[0]['onlineUsers']) ? $arrData[0]['onlineUsers'] : 0;?> </div></span>
                 OnlineUsers
            </div>
        </div>
       
    
 </div>
</div>
<!--mini statistics end-->

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
									<th>Offers Added</th>
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
                                    <td style="cursor: pointer; cursor: hand;"><a href="<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClients[$i]['id']; ?>/"><?php echo $dispClient; ?></a></td>
                                    <td style="cursor: pointer; cursor: hand;"><p style="text-align:center;font-size:20px;color:black;"><a href="<?php echo $config['LIVE_URL'];?>clients/id/<?php echo $outArrClients[$i]['id'];?>/products/"><?php echo $outArrClients[$i]['products_total'];?></a></p></td>
                                    <td style="cursor: pointer; cursor: hand;"><p style="text-align:center;font-size:20px;color:black;"><a href="<?php echo $config['LIVE_URL'];?>clients/id/<?php echo $outArrClients[$i]['id'];?>/triggers/"><?php echo $outArrClients[$i]['triggers_total'];?></a></p></td>
									 <td style="cursor: pointer; cursor: hand;"><p style="text-align:center;font-size:20px;color:black;"><a href="<?php echo $config['LIVE_URL'];?>clients/id/<?php echo $outArrClients[$i]['id'];?>/offers/"><?php echo $outArrClients[$i]['offers_total'];?></a></p></td>
                                </tr>
                                <?php }?>
                                </tbody>
                            </table>
        
            <div align="right">
			<a href="<?php echo $config['LIVE_URL']; ?>clients/" class="btn btn-default btn-primary">More Clients</a>
			</div>
            
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
            <div align="right"><button onclick="document.location = '<?php echo $config['LIVE_URL']; ?>appusers/';" type="submit" class="btn btn-default btn-primary" > More App Users</button></div> 
          <!-- <div align="right"><button type="submit" class="btn btn-default btn-primary" > More App Users</button></div>-->
            
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
<script src="http://www.google.com/jsapi"></script>
<script> 
 //product graphs 
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChartProductsTrack);
    function drawChartProductsTrack() {
	  var actionType = "product_graphs";
	  var campaign_id='<?php echo $_SESSION['search_campaign_id']?>';
	  var client_id='<?php echo $_SESSION['search_client_id']?>';
	  var start_date='<?php echo $_SESSION['start_date']?>';
	  var end_date='<?php echo $_SESSION['end_date']?>';
	  var dates=[];
	  var productids=[];
   
	$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType,client_id:client_id,start_date:start_date,end_date:end_date,campaign_id:campaign_id},
	function(data) {
		//alert(data);
		
		$.each(data, function(i,e) {
         dates.push(e.dates);
	     productids.push(e.productids);
})
    var resultdata = new google.visualization.DataTable();

    <!-- Create the data table -->
    resultdata.addColumn('string', 'dates');
    resultdata.addColumn('number', 'productids');
	var result = [];

for(var i=0; i<dates.length; i++) {
    result.push( [dates[i], productids[i]] );
}    

resultdata.addRows(result);
	
     var chart = new google.visualization.AreaChart(document.getElementById('chart_products_views'));
    chart.draw(resultdata, {title: '',
                      colors:['#058dc7','#e6f4fa'],
                      areaOpacity: 0.1,
                      hAxis: {textPosition: 'bottom', showTextEvery: 20, slantedText: false, textStyle: { color: '#058dc7', fontSize: 10 } },
                      pointSize: 7,
                      legend: 'right'
    });
						
		},'json'
	);
    
  }
  //offers graph
  google.setOnLoadCallback(drawChartOffersTrack);
  function drawChartOffersTrack() {
	  var actionType = "offer_graphs";
	  var campaign_id='<?php echo $_SESSION['search_campaign_id']?>';
	  var client_id='<?php echo $_SESSION['search_client_id']?>';
	  var start_date='<?php echo $_SESSION['start_date']?>';
	  var end_date='<?php echo $_SESSION['end_date']?>';
	  var dates=[];
	  var offerids=[];
  $.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType,client_id:client_id,start_date:start_date,end_date:end_date,campaign_id:campaign_id},
		function(data) {
			$.each(data, function(i,e) {
            dates.push(e.dates);
	        offerids.push(e.offerids);
 })

	  var resultdata = new google.visualization.DataTable();
	  resultdata.addColumn('string', 'dates');
	  resultdata.addColumn('number', 'offerids');
	  var result = [];
   for(var i=0; i<dates.length; i++) {
    result.push( [dates[i], offerids[i]] );
   }  
resultdata.addRows(result);
	
 var chart = new google.visualization.AreaChart(document.getElementById('chart_offers_views'));
 chart.draw(resultdata, {title: '',
                      colors:['#058dc7','#e6f4fa'],
                      areaOpacity: 0.1,
                      hAxis: {textPosition: 'bottom', showTextEvery: 20, slantedText: false, textStyle: { color: '#058dc7', fontSize: 10 } },
                      pointSize: 7,
                      legend: 'right'
    });
						
		},'json'
	);
  }
  //product share graphs
  google.setOnLoadCallback(drawChartProductShareTrack);
  function drawChartProductShareTrack() {
	  var actionType = "product_share_graphs";
	  var campaign_id='<?php echo $_SESSION['search_campaign_id']?>';
	  var client_id='<?php echo $_SESSION['search_client_id']?>';
	  var start_date='<?php echo $_SESSION['start_date']?>';
	  var end_date='<?php echo $_SESSION['end_date']?>';
	  var dates=[];
	  var product_shareViews=[];
$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType,client_id:client_id,start_date:start_date,end_date:end_date,campaign_id:campaign_id},
		function(data) {
			$.each(data, function(i,e) {
			dates.push(e.dates);
			product_shareViews.push(e.product_shareViews);
      })
	  var resultdata = new google.visualization.DataTable();
	  resultdata.addColumn('string', 'dates');
	  resultdata.addColumn('number', 'product_shareViews');
	  var result = [];
	for(var i=0; i<dates.length; i++) {
		result.push( [dates[i], product_shareViews[i]] );
			}    
    resultdata.addRows(result);
    var chart = new google.visualization.AreaChart(document.getElementById('chart_products_shared'));
    chart.draw(resultdata, {title: '',
                      colors:['#058dc7','#e6f4fa'],
                      areaOpacity: 0.1,
                      hAxis: {textPosition: 'bottom', showTextEvery: 20, slantedText: false, textStyle: { color: '#058dc7', fontSize: 10 } },
                      pointSize: 7,
                      legend: 'right'
    });
						
		},'json'
	);
    
  } 
  //offer share graphs
  google.setOnLoadCallback(drawChartOffersSharedTrack);
  function drawChartOffersSharedTrack() {
	  var actionType = "offer_share_graphs";
	  var campaign_id='<?php echo $_SESSION['search_campaign_id']?>';
	  var client_id='<?php echo $_SESSION['search_client_id']?>';
	  var start_date='<?php echo $_SESSION['start_date']?>';
	  var end_date='<?php echo $_SESSION['end_date']?>';
	  var dates=[];
	  var offer_shareViews=[];
$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType,client_id:client_id,start_date:start_date,end_date:end_date,campaign_id:campaign_id},
		function(data) {
			$.each(data, function(i,e) {
			dates.push(e.dates);
			offer_shareViews.push(e.offer_shareViews);
         })
      var resultdata = new google.visualization.DataTable();

    <!-- Create the data table -->
    resultdata.addColumn('string', 'dates');
    resultdata.addColumn('number', 'offer_shareViews');
	var result = [];

	for(var i=0; i<dates.length; i++) {
		result.push( [dates[i], offer_shareViews[i]] );
	}  
	resultdata.addRows(result);
	var chart = new google.visualization.AreaChart(document.getElementById('chart_offers_shared'));
    chart.draw(resultdata, {title: '',
                      colors:['#058dc7','#e6f4fa'],
                      areaOpacity: 0.1,
                      hAxis: {textPosition: 'bottom', showTextEvery: 20, slantedText: false, textStyle: { color: '#058dc7', fontSize: 10 } },
                      pointSize: 7,
                      legend: 'right'
    });
						
		},'json'
	);
  } 
 </script>
<script>
$(document).ready(function () {
function refreshUserlist () {
  var actionType = "online_users";
$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType},
		function(data) {
			$("#online").html(data.onlineUsers);
			setTimeout(refreshUserlist, 5000); 
		},'json'
	);
    } refreshUserlist ();
});
</script>
<script>
	$(document).ready(function () {
	function productviews () {
	  var actionType = "product_views";
	  var campaign_id='<?php echo $_SESSION['search_campaign_id']?>';
	  var client_id='<?php echo $_SESSION['search_client_id']?>';
	  var start_date='<?php echo $_SESSION['start_date']?>';
	  var end_date='<?php echo $_SESSION['end_date']?>';
    $.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType,client_id:client_id,start_date:start_date,end_date:end_date,campaign_id:campaign_id},
		function(data) {
			$("#productview").html(data.productViews);
		},'json'
	   );
      } 

	productviews ();
   }); 
</script>
<script>
$(document).ready(function () {

function offerviews() {
  var actionType = "offer_views";
  var campaign_id='<?php echo $_SESSION['search_campaign_id']?>';
  var client_id='<?php echo $_SESSION['search_client_id']?>';
  var start_date='<?php echo $_SESSION['start_date']?>';
  var end_date='<?php echo $_SESSION['end_date']?>';
  $.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType,client_id:client_id,start_date:start_date,end_date:end_date,campaign_id:campaign_id},
		function(data) {
			$("#offerview").html(data.offerViews);
		},'json'
	);
   
    } offerviews() ; 
});  
</script>
<script>
	$(document).ready(function () {

	  function productshareviews () {
		  var actionType = "product_share_views";
		  var campaign_id='<?php echo $_SESSION['search_campaign_id']?>';
		  var client_id='<?php echo $_SESSION['search_client_id']?>';
		  var start_date='<?php echo $_SESSION['start_date']?>';
		  var end_date='<?php echo $_SESSION['end_date']?>';
	 $.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType,client_id:client_id,start_date:start_date,end_date:end_date,campaign_id:campaign_id},
			function(data) {
				$("#productshareview").html(data.product_shareViews);
			},'json'
		   );
             }  

	 productshareviews ();
        });
</script>
<script>
 $(document).ready(function () {

function offershareviews () {
  var actionType = "offer_share_views";
  var campaign_id='<?php echo $_SESSION['search_campaign_id']?>';
  var client_id='<?php echo $_SESSION['search_client_id']?>';
  var start_date='<?php echo $_SESSION['start_date']?>';
  var end_date='<?php echo $_SESSION['end_date']?>';
  $.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType,client_id:client_id,start_date:start_date,end_date:end_date,campaign_id:campaign_id},
		function(data) {
			$("#offershareview").html(data.offer_shareViews);
		  },'json'
	    );
    }  

   offershareviews ();
   }); 
</script>
<script>
$(document).ready(function () {

function totaldownloads () {
  var actionType = "downloads";
  var start_date='<?php echo $_SESSION['start_date']?>';
  var end_date='<?php echo $_SESSION['end_date']?>';
$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType,start_date:start_date,end_date:end_date},
		function(data) {
			$("#downloads").html(data.total_downloads);
		
		},'json'
	);
} 

	//totaldownloads ();
});
</script>
<script>
 jQuery.noConflict(true);
</script>