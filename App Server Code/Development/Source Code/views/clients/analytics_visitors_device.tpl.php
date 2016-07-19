
<div id="control-bar" class="grey-bg clearfix" style="opacity: 1;"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="history.go(-1);return false;"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-180.png" width="16" height="16" > Back</button>
		</div>
		<div style="float:left; padding-top: 6px;text-align: center;width: 80%;"><h1 id="client_name"><?php echo isset($outArrClient[0]['name']) ? $outArrClient[0]['name'] : ''; ?></h1></div>

			
	</div>
</div>


<div align="right">
	<form  id="table_form" method="post" action="<?php echo $config['LIVE_URL']; ?>analytics/visitors_geo/">
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
	<form class="block-content form" id="simple_form" method="post" action="" style="height: 300px;">
		<h1>OS Versions</h1>
		<?php if(!empty($deviceInfoArray)) {?> 
	       <div id="deviceInfopiechart" style="width: 490px; height: 300px;" ><div align="center"><img src="<?php echo $config['LIVE_URL']; ?>views/images/ajax-loader.gif" width="100" height="100"></div>
		<?php }else{?>
		   <div align="center">There is no data for this view.</div>
		<?php }?>
	</form></div>
</section>

<div class="clear"></div>

<section class="grid_12">
  <div class="block-border">
	<form class="block-content form" id="simple_form" method="post" action="" style="height: 300px;">
		<h1>Results</h1>
		

		
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
  google.setOnLoadCallback(drawChartActiveUserPieChart);
  function drawChartActiveUserPieChart() {
    var data = new google.visualization.DataTable();

    <!-- Create the data table -->
    data.addColumn('string', 'Os Version');
    data.addColumn('number', 'Session');

    <!-- Fill the chart with the data pulled from Analtyics. Each row matches the order setup by the columns: day then pageviews -->
    data.addRows([
     <?php
      foreach($deviceInfoArray as $result) {
      $osNamewithVersion=$result->getoperatingSystem().' '.$result->getoperatingSystemVersion();
      
          echo '["'.$osNamewithVersion.'", '.$result->getvisits().'],';
      }
     
      ?>
    ]);


    var chart = new google.visualization.PieChart(document.getElementById('deviceInfopiechart'));
    chart.draw(data, {legend: 'right'});
  }
  
 
 
</script>
 