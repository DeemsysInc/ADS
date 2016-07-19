<div id="control-bar" class="grey-bg clearfix" style="opacity: 1;"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="history.go(-1);return false;"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-180.png" width="16" height="16" > Back</button>
		</div>
        <div style="float:left; padding-top: 6px;text-align: center;width: 80%;"><h1 id="client_name">Analytics</h1></div>
		
			
	</div>
</div>
	<!-- Content -->
	<article class="container_12">
		
<style type="text/css">
ul.simple-list li span{
float:right;
}
</style>

<section class="grid_12">
<div class="block-border"><form class="block-content form" id="table_form" method="post" action="<?php echo $config['LIVE_URL']; ?>analytics/products/">
		<h1>Screens graph</h1>
		<div align="right">From:<input type="text" id="from" name="from">to:<input type="text" id="to" name="to">&nbsp;&nbsp;&nbsp;<input type="submit" value="Apply"></div>
<div id="chart" width="100%" height="100%"><div align="center"><img src="<?php echo $config['LIVE_URL']; ?>views/images/ajax-loader.gif" width="200" height="200"></div></div>
</form>
</div>
	<div class="block-border"><form class="block-content form" id="table_form" method="post" action="">
		<h1>Products</h1>
		
	
		
		
		
		<table class="table sortable no-margin" cellspacing="0" width="100%" id="cms_clients">
		
			<thead>
				<tr>
					<!--<th class="black-cell"><span class="loading"></span></th>-->
					<th scope="col">
						Screen Names
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Screen Views
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Unique Screen Views
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Screen Views Per Session
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						timeOnScreen
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						avgScreenviewDuration
					</th>
				</tr>
			</thead>
			
			<tbody>
			<?php for($i=0;$i<count($arrData['report']['screen_name']);$i++) {?>
				<tr>
					<!--<td class="th table-check-cell"><input type="checkbox" name="selected[]" id="table-selected-1" value="1"></td>-->
					<td><?php echo $arrData['report']['screen_name'][$i]; ?></td>
					<td><?php echo $arrData['report']['screen_views'][$i]; ?></td>
					<td><?php echo $arrData['report']['unique_screen_views'][$i]; ?></td>
					<td><?php echo $arrData['report']['screen_views_per_session'][$i]; ?></td>
					<td><?php echo $arrData['report']['time_on_screen'][$i]; ?></td>
					<td><?php echo $arrData['report']['avg_screen_view_duration'][$i]; ?></td>
					
					
				</tr>
				<?php }?>
			</tbody>
		</table>
		
	</form></div>
</section>

<div class="clear"></div>




</article>
<!-- End content -->
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script type="text/javascript">
  jQuery(document).ready(function($) {

   $( "#from" ).datepicker({
      defaultDate: "+1w",
      dateFormat:"yy-mm-dd",
      changeMonth: true,
      numberOfMonths: 3
      
    });
    $( "#to" ).datepicker({
      defaultDate: "+1w",
        dateFormat:"yy-mm-dd",
      changeMonth: true,
      numberOfMonths: 3
      
    });
});
</script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
    var data = new google.visualization.DataTable();

    <!-- Create the data table -->
    data.addColumn('string', 'Day');
    data.addColumn('number', 'ScreenViews');

    <!-- Fill the chart with the data pulled from Analtyics. Each row matches the order setup by the columns: day then pageviews -->
    data.addRows([
      <?php
     /* foreach($results as $result) {
          echo '["'.date('M j',strtotime($result->getDate())).'", '.$result->getScreenViews().'],';
      }
      */
      for($i=0;$i<count($arrData['report']['screen_name']);$i++){
      
       echo '["'.date('M j,Y',strtotime($arrData['report']['date'][$i])).'", '.$arrData['report']['screen_views'][$i].'],';
      }
      ?>
    ]);

    var chart = new google.visualization.AreaChart(document.getElementById('chart'));
    chart.draw(data, {width: "60%", height: "50%", title: '<?php echo date('M j, Y',strtotime('-30 day')).' - '.date('M j, Y'); ?>',
                      colors:['#058dc7','#e6f4fa'],
                      areaOpacity: 0.1,
                      hAxis: {textPosition: 'in', showTextEvery: 5, slantedText: false, textStyle: { color: '#058dc7', fontSize: 10 } },
                      pointSize: 5,
                      legend: 'none',
                      chartArea:{left:0,top:10,width:"100%",height:"100%"}
    });
  }

</script>
