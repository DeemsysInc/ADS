<div id="control-bar" class="grey-bg clearfix" style="opacity: 1;"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="history.go(-1);return false;"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-180.png" width="16" height="16" > Back</button>
		</div>
        <div style="float:left; padding-top: 6px;text-align: center;width: 80%;"><h1 id="client_name">User tracking Data</h1></div>
		
			
	</div>
</div>

<div align="right">
	<form  id="table_form" method="post" action="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/product_views">
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
<section class="grid_12">
  <div class="block-border">
	<form class="block-content form" id="simple_form" method="post" action="" style="height: 250px;">
		<a href="#"><h1>Product Views</h1></a>
	      <div id="chart_products_views" style="height:330px;"><div align="center"><img src="<?php echo $config['LIVE_URL']; ?>views/images/ajax-loader.gif" width="100" height="100"></div></div>
    </form>
 </div>
</section>
<div class="clear"></div>
<section class="grid_12">
	<div class="block-border"><form class="block-content form" id="table_form" method="post" action="">
		<h1>Product Info</h1>
		
		<table class="table sortable no-margin" cellspacing="0" width="100%" id="cms_clients">
		
			<thead>
				<tr>
					<!--<th class="black-cell"><span class="loading"></span></th>-->
					<th scope="col">
						Date
					</th>
					
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						No.Of Views
					</th>
					<th style="display:none;">&nbsp;</th>
					<th style="display:none;">&nbsp;</th>
					<th style="display:none;">&nbsp;</th>
					<th style="display:none;">&nbsp;</th>
					<th style="display:none;">&nbsp;</th>
					<th style="display:none;">&nbsp;</th>
					<th style="display:none;">&nbsp;</th>
					<th style="display:none;">&nbsp;</th>
					<th style="display:none;">&nbsp;</th>
					<th style="display:none;">&nbsp;</th>
					
				</tr>
			</thead>
			
			<tbody>
				<?php for($i=0;$i<count($arrDataForGraph);$i++){?>
				<tr>
					<td><a href="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/product_views/date/<?php echo isset($arrDataForGraph[$i]['dates']) ? $arrDataForGraph[$i]['dates'] : ''; ?>"><?php echo isset($arrDataForGraph[$i]['dates']) ? $arrDataForGraph[$i]['dates'] : ''; ?></a></td>
					<td><?php echo isset($arrDataForGraph[$i]['product_views']) ? $arrDataForGraph[$i]['product_views'] : ''; ?></td>
					<td style="display:none;">&nbsp;</td>
					<td style="display:none;">&nbsp;</td>
					<td style="display:none;">&nbsp;</td>
					<td style="display:none;">&nbsp;</td>
					<td style="display:none;">&nbsp;</td>
					<td style="display:none;">&nbsp;</td>
					<td style="display:none;">&nbsp;</td>
					<td style="display:none;">&nbsp;</td>
					<td style="display:none;">&nbsp;</td>
					<td style="display:none;">&nbsp;</td>
					
               
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
<script src="http://www.google.com/jsapi"></script>
	<script type="text/javascript">
		
		
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChartProductsTrack);
  function drawChartProductsTrack() {
    var data = new google.visualization.DataTable();

    <!-- Create the data table -->
    data.addColumn('string', 'Day');
    data.addColumn('number', 'Products');

    data.addRows([
     <?php
     for($i=0;$i<count($arrDataForGraph);$i++)
     {
         echo '["'.date('M j,Y',strtotime($arrDataForGraph[$i]['dates'])).'", '.$arrDataForGraph[$i]['product_views'].'],';
     }
      ?>
    ]);

   
     var chart = new google.visualization.AreaChart(document.getElementById('chart_products_views'));
    chart.draw(data, {width: 1150, height: 250, title: '',
                      colors:['#058dc7','#e6f4fa'],
                      areaOpacity: 0.1,
                      hAxis: {textPosition: 'bottom', showTextEvery: 20, slantedText: false, textStyle: { color: '#058dc7', fontSize: 10 } },
                      pointSize: 7,
                      legend: 'right'
    });
    
  }
  </script>