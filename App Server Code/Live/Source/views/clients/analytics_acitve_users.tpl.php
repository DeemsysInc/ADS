
<div id="control-bar" class="grey-bg clearfix" style="opacity: 1;"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="history.go(-1);return false;"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-180.png" width="16" height="16" > Back</button>
		</div>
		<div style="float:left; padding-top: 6px;text-align: center;width: 80%;"><h1 id="client_name"><?php echo isset($outArrClient[0]['name']) ? $outArrClient[0]['name'] : ''; ?></h1></div>

			
	</div>
</div>


<div align="right">
	<form  id="table_form" method="post" action="<?php echo $config['LIVE_URL']; ?>analytics/activeusers/">
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

	
</section>

<section class="grid_12">
	<div class="block-border">
	<form class="block-content form" id="simple_form" method="post" action="" style="height: 250px;">
		<a href="<?php echo $config['LIVE_URL']; ?>analytics/activeusers/"><h1>Active Users</h1>
		<?php if(!empty($activeUsersresults)) {?> 
	       <div id="active_users_chart" ><div align="center"><img src="<?php echo $config['LIVE_URL']; ?>views/images/ajax-loader.gif" width="100" height="100"></div></div>
		<?php }else{?>
		   <div align="center">There is no data for this view.</div>
		<?php }?>
	</form></div>
</section>

<div class="clear"></div>

<section class="grid_12">
  <div class="block-border">
	<form class="block-content form" id="simple_form" method="post" action="" style="height: 300px;">
		<h1><?php echo isset($totalActiveUsers) ? $totalActiveUsers : '0';?> users used this app</h1>
<table width="100%">
<tr>
<td style="vertical-align:top;">
<div style="margin: 10px 0 30px;" >
 <div class="block" style="display: inline-block;margin: 15px 0 10px 10px;">
   <div style="font-size: 13px;cursor: pointer;">Active Users</div>
   <div style="display: inline-block;margin: 5px auto;text-align: left;">
    <div>
     <div style="font-size: 24px;"><?php echo isset($totalActiveUsers) ? $totalActiveUsers : '0';?></div>
      <?php foreach ($activeUsersresults as $result) {
		  $resultstr[] = $result->getvisitors();
		}
		$resultActiveUsers = implode(",",$resultstr);
		
	  ?>
      <div style="border-color: #fff;border-style: solid;border-width: 1px 2px 2px 1px;cursor: pointer;float: left;margin-right: 10px;">
        <div> <img src="https://chart.googleapis.com/chart?chs=170x20&chd=t:<?php echo $resultActiveUsers;?>&cht=ls"></div>
      </div>
    </div>
   </div>
 </div>
 <div class="block" style="display: inline-block;margin: 15px 0 10px 10px;">
   <div style="font-size: 13px;cursor: pointer;">Sessions</div>
   <div style="display: inline-block;margin: 5px auto;text-align: left;">
    <div>
     <div style="font-size: 24px;"><?php echo isset($totalActiveUsersSessions) ? $totalActiveUsersSessions : '0';?></div>
       <?php foreach ($activeUsersresults as $result) {
		  $resultstr[] = $result->getvisits();
		}
		$resultSessions = implode(",",$resultstr);
		
	  ?>
      <div style="border-color: #fff;border-style: solid;border-width: 1px 2px 2px 1px;cursor: pointer;float: left;margin-right: 10px;">
        <div> <img src="https://chart.googleapis.com/chart?chs=170x20&chd=t:<?php echo $resultSessions;?>&cht=ls"></div>
      </div>
    </div>
   </div>
 </div>
 <div class="block" style="display: inline-block;margin: 15px 0 10px 10px;">
   <div style="font-size: 13px;cursor: pointer;">Screen Views</div>
   <div style="display: inline-block;margin: 5px auto;text-align: left;">
    <div>
     <div style="font-size: 24px;"><?php echo isset($totalScreenViews) ? $totalScreenViews : '0';?></div>
      <?php foreach ($activeUsersresults as $result) {
		  $resultstr[] = $result->getscreenViews();
		}
		$resultScreenViews = implode(",",$resultstr);
		
	  ?>
      <div style="border-color: #fff;border-style: solid;border-width: 1px 2px 2px 1px;cursor: pointer;float: left;margin-right: 10px;">
        <div> <img src="https://chart.googleapis.com/chart?chs=170x20&chd=t:<?php echo $resultScreenViews;?>&cht=ls"></div>
      </div>
    </div>
   </div>
 </div>
 <div class="block" style="display: inline-block;margin: 15px 0 10px 10px;">
   <div style="font-size: 13px;cursor: pointer;">Screens / Session</div>
   <div style="display: inline-block;margin: 5px auto;text-align: left;">
    <div>
     <div style="font-size: 24px;"><?php echo isset($avgscreenviewsperSession) ? $avgscreenviewsperSession : '0';?></div>
      <?php foreach ($activeUsersresults as $result) {
		  $resultstr[] = $result->getscreenviewspersession();
		}
		$resultScreenviewspersession= implode(",",$resultstr);
		
	  ?>
      <div style="border-color: #fff;border-style: solid;border-width: 1px 2px 2px 1px;cursor: pointer;float: left;margin-right: 10px;">
        <div> <img src="https://chart.googleapis.com/chart?chs=170x20&chd=t:<?php echo $resultScreenviewspersession;?>&cht=ls"></div>
      </div>
    </div>
   </div>
 </div>
 <div class="block" style="display: inline-block;margin: 15px 0 10px 10px;">
   <div style="font-size: 13px;cursor: pointer;">Avg. Session Duration</div>
   <?php foreach ($activeUsersresults as $result) {
		  $resultstr[] = $result->getavgTimeOnSite();
		}
		$resultavgTimeOnSite= implode(",",$resultstr);
		
	  ?>
   <div style="display: inline-block;margin: 5px auto;text-align: left;">
    <div>
     <div style="font-size: 24px;"><?php echo isset($avgTimeOnSite) ? $avgTimeOnSite : '0';?></div>
      <div style="border-color: #fff;border-style: solid;border-width: 1px 2px 2px 1px;cursor: pointer;float: left;margin-right: 10px;">
        <div> <img src="https://chart.googleapis.com/chart?chs=170x20&chd=t:<?php echo $resultavgTimeOnSite;?>&cht=ls"></div>
      </div>
    </div>
   </div>
 </div>
 <div class="block" style="display: inline-block;margin: 15px 0 10px 10px;">
   <div style="font-size: 13px;cursor: pointer;">% New Sessions</div>
   
   <?php foreach ($percentNewUsersSessionforChart as $result) {
		  $resultstr[] = $result->getvisits();
		}
		$resultpercentNewUsersSessionVals= implode(",",$resultstr);
		
	  ?>
   <div style="display: inline-block;margin: 5px auto;text-align: left;">
    <div>
     <div style="font-size: 24px;"><?php echo isset($percentNewVisitorsSessions) ? $percentNewVisitorsSessions : '0';?></div>
      <div style="border-color: #fff;border-style: solid;border-width: 1px 2px 2px 1px;cursor: pointer;float: left;margin-right: 10px;">
        <div> <img src="https://chart.googleapis.com/chart?chs=170x20&chd=t:<?php echo $resultpercentNewUsersSessionVals;?>&cht=ls"></div>
      </div>
    </div>
   </div>
 </div>
</div>

</td>
<td style="vertical-align:top;">
 <div id="piechart" style="width: 437px; height: 300px;" ></div>
</td>
</tr>
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

  

  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChartActiveUserPieChart);
  function drawChartActiveUserPieChart() {
    var data = new google.visualization.DataTable();

    <!-- Create the data table -->
    data.addColumn('string', 'Country');
    data.addColumn('number', 'Sessions');

    <!-- Fill the chart with the data pulled from Analtyics. Each row matches the order setup by the columns: day then pageviews -->
    data.addRows([
     <?php
      foreach($activeUsersSessionsByMonth as $result) {
      
          echo '["'.$result->getvisitorType().'", '.$result->getvisits().'],';
      }
     
      ?>
    ]);


    var chart = new google.visualization.PieChart(document.getElementById('piechart'));
    chart.draw(data, {legend: 'top'});
  }
 
  
 
 
</script>
 