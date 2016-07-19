
<div id="control-bar" class="grey-bg clearfix" style="opacity: 1;"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="history.go(-1);return false;"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-180.png" width="16" height="16" > Back</button>
		</div>
		<div style="float:left; padding-top: 6px;text-align: center;width: 80%;"><h1 id="client_name"><?php echo isset($outArrClient[0]['name']) ? $outArrClient[0]['name'] : ''; ?></h1></div>

			
	</div>
</div>


<div align="right">
	<form  id="table_form" method="post" action="<?php echo $config['LIVE_URL']; ?>analytics/dashboard/">
	From:<input type="text" id="from" name="from" value="<?php echo $start_date;?>">to:<input type="text" id="to" name="to" value="<?php echo $end_date;?>">&nbsp;&nbsp;&nbsp;<input type="submit" value="Apply">
	</form>
</div>
	<!-- Content -->
	
	<article class="container_12">
	
<style type="text/css">
ul.simple-list li span{
float:right;
}
</style>
<!--
<section class="grid_6">
  <div class="block-border">
	<form class="block-content form" id="simple_form" method="post" action="">
		<h1>New Users</h1>
	<div id="chart" ><div align="center"><img src="<?php echo $config['LIVE_URL']; ?>views/images/ajax-loader.gif" width="100" height="100"></div></div>
    </form>
 </div>
-->
	
</section>

<section class="grid_12">
	<div class="block-border">
	<form class="block-content form" id="simple_form" method="post" action="" style="height: 250px;">
		<a href="<?php echo $config['LIVE_URL']; ?>analytics/activeusers/"><h1>Active Users</h1></a>
		<?php if(!empty($activeUsersresults)) {?> 
	       <div id="active_users_chart" ><div align="center"><img src="<?php echo $config['LIVE_URL']; ?>views/images/ajax-loader.gif" width="100" height="100"></div></div>
		<?php }else{?>
		   <div align="center">There is no data for this view.</div>
		<?php }?>
	</form></div>
</section>

<div class="clear"></div>

<section class="grid_6">
  <div class="block-border">
	<form class="block-content form" id="simple_form" method="post" action="" style="height: 250px;">
		<a href="<?php echo $config['LIVE_URL']; ?>analytics/visitors_geo"><h1>Country / Territory</h1></a>
		<?php if(!empty($Countryresults)) {?> 	
	      <div id="country_chart" ><div align="center"><img src="<?php echo $config['LIVE_URL']; ?>views/images/ajax-loader.gif" width="100" height="100"></div></div>
	    <?php }else{?>
		  <div align="center">There is no data for this view.</div>
		<?php }?>
    </form>
 </div>
</section>

<section class="grid_6">
	<div class="block-border">
	<form class="block-content form" id="simple_form" method="post" action="" style="height: 250px;">
		<a href="<?php echo $config['LIVE_URL']; ?>analytics/visitors_device_overview"><h1>Top Device Models</h1></a>
	
	<div class="YK" style="margin-left: 25px;margin-top: 25px;">
	    <?php if(!empty($TopdeviceModelsresults)) {?> 
	    <?php foreach($TopdeviceModelsresults as $result) {?>
		<div class="a-t-u pAb" style="min-width: 300px;display: inline-block;">
			<div class="a-t-u P7" style="height: 100px;margin-right: 5px;display: inline-block;">
			   <?php if($result->getmobileDeviceInfo()=="Apple iPad"){?>
			   <img src="<?php echo $config['LIVE_URL']; ?>views/images/ipad.jpg">
			   <?php }elseif($result->getmobileDeviceInfo()=="Apple iPhone"){?>
			   <img src="<?php echo $config['LIVE_URL']; ?>views/images/iphone.jpg">
			   <?php }?>
			   
			</div>
			<div class="a-t-u tpb" style="vertical-align: top;text-align: left;display: inline-block;" >
			   <div class="Zbb" style="font-size: 14px;color: #4278a8;text-overflow: ellipsis;overflow: hidden;white-space: nowrap;"><?php echo $result->getmobileDeviceInfo();?></div>
			   <br>
			   <div style="color: #046B97"><?php echo number_format($result->getvisits());?> sessions</div>
			   <br>
			   <div style="color: #046B97"><?php echo $avgSession= round(($result->getvisits()/ $totalSessions) * 100, 2);?> %</div>
			  
			</div>
		</div>
		<?php }?>
		<?php }else{?>
		<div align="center">There is no data for this view.</div>
		<?php }?>

		
	</div>		
	
	</form></div>
</section>

<div class="clear"></div>

<section class="grid_6">
  <div class="block-border">
	<form class="block-content form" id="simple_form" method="post" action="" style="height: 250px;">
		<h1>User Engagement</h1>
	<div id="UserEngagementChart" ><div align="center"><img src="<?php echo $config['LIVE_URL']; ?>views/images/ajax-loader.gif" width="100" height="100"></div></div>
    </form>
 </div>
</section>
<section class="grid_6">
  <div class="block-border">
	<form class="block-content form" id="simple_form" method="post" action="" style="height: 250px;">
		<h1>Screens</h1>
	  
	  <table width="100%">
		 <tbody>
		  <tr><th>Screen Name</th><th>Screen Views</th></tr>
		  <?php if(!empty($ScreensResults)) {?> 
		  <?php foreach($ScreensResults as $result) {?>
		  <tr style="background-color: #f8f8f8;">
		   <td style="border-bottom: 1px solid #cbcbcb;padding: 8px 8px 7px;"><div id="screenName"><?php echo $result->getscreenName();?></div></td>
		   <td style="border-bottom: 1px solid #cbcbcb;padding: 8px 8px 7px;text-align: right;"><?php echo $result->getscreenviews();?></td>
		  </tr>
		  <?php }?>
		  <?php }else{?>
 	       <div align="center">There is no data for this view.</div>
         <?php }?> 
		 </tbody>
		</table>
 
 
    </form>
 </div>
</section>
</article>
<!-- End content -->
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script type="text/javascript">
  jQuery(document).ready(function($) {

   $( "#from" ).datepicker({
      defaultDate: "-2m",
	  maxDate: +0,
      dateFormat:"yy-mm-dd",
      changeMonth: true,
      numberOfMonths: 3
      
    });
    $( "#to" ).datepicker({
      //defaultDate: "+1w",
	  maxDate: +0,
        dateFormat:"yy-mm-dd",
      changeMonth: true,
      numberOfMonths: 3
      
    });
});
</script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
 
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChartActiveUsers);
  function drawChartActiveUsers() {
    var data = new google.visualization.DataTable();

    <!-- Create the data table -->
    data.addColumn('string', 'Day');
    data.addColumn('number', 'Active Users');

    <!-- Fill the chart with the data pulled from Analtyics. Each row matches the order setup by the columns: day then pageviews -->
    data.addRows([
     <?php
      foreach($activeUsersresults as $result) {
          echo '["'.date('M j,Y',strtotime($result->getDate())).'", '.$result->getvisitors().'],';
      }
     
      ?>
    ]);

    var chart = new google.visualization.AreaChart(document.getElementById('active_users_chart'));
    chart.draw(data, {width: 1100, height: 250, title: '',
                      colors:['#058dc7','#e6f4fa'],
                      areaOpacity: 0.1,
                      hAxis: {textPosition: 'bottom', showTextEvery: 20, slantedText: false, textStyle: { color: '#058dc7', fontSize: 10 } },
                      pointSize: 7,
                      legend: 'right'
    });
  }

  google.load("visualization", "1", {packages:["geochart"]});
  google.setOnLoadCallback(drawChartCountry);
  function drawChartCountry() {
    var data = new google.visualization.DataTable();

    <!-- Create the data table -->
    data.addColumn('string', 'Country');
    data.addColumn('number', 'Sessions');

    <!-- Fill the chart with the data pulled from Analtyics. Each row matches the order setup by the columns: day then pageviews -->
    data.addRows([
      <?php
      foreach($Countryresults as $result) {
          echo '["'.$result->getcountry().'", '.$result->getvisits().'],';
      }
     
      ?>
    ]);

    var chart = new google.visualization.GeoChart(document.getElementById('country_chart'));
    chart.draw(data, {width: 519, height: 250, title: ''});
  }
 google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChartUserEngagement);
  function drawChartUserEngagement() {
    var data = new google.visualization.DataTable();

    <!-- Create the data table -->
    data.addColumn('string', 'Day');
    data.addColumn('number', 'Avg. Session Duration');
    data.addColumn('number', 'Screens / Session');

    <!-- Fill the chart with the data pulled from Analtyics. Each row matches the order setup by the columns: day then pageviews -->
    data.addRows([
      <?php
      foreach($userEngagementResults as $result) {
          echo '["'.date('M j,Y',strtotime($result->getDate())).'", '.$result->getavgTimeOnSite().', '.$result->getscreenviewsPerSession().'],';
      }
     
      ?>
    ]);
    var chart = new google.visualization.AreaChart(document.getElementById('UserEngagementChart'));
    
    chart.draw(data, {width: 519, height: 250, title: '',
                      //colors:['#058dc7','#e6f4fa'],
                      areaOpacity: 0.1,
                      hAxis: {textPosition: 'bottom', showTextEvery: 20, slantedText: false, textStyle: { color: '#058dc7', fontSize: 10 } },
                      //vAxis: {title:'sdfsdf'},
                      pointSize: 7,
                      legend: 'top'
                      
    });
  }
</script>
 