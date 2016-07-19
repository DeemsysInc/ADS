
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
	<form class="block-content form" id="simple_form" method="post" action="" style="height: 250px;">
		<h1>Location</h1>
		<?php if(!empty($Countryresults)) {?> 
	       <div id="country_chart" ><div align="center"><img src="<?php echo $config['LIVE_URL']; ?>views/images/ajax-loader.gif" width="100" height="100"></div></div>
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
		<table class="table sortable no-margin" cellspacing="0" width="100%" id="cms_clients">
		
			<thead>
				<tr>
					<!--<th class="black-cell"><span class="loading"></span></th>-->
					<th scope="col">
						Country/Territory
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Sessions
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
						Screens/Session
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Avg.Session Duration
					</th>
					
				</tr>
			</thead>
			
			<tbody>
			
				<?php foreach ($Countryresults as $result) {?>
				<tr>
					<!--<td class="th table-check-cell"><input type="checkbox" name="selected[]" id="table-selected-1" value="1"></td>-->
					<td><?php echo $result->getcountry(); ?></td>
					<td><?php echo $result->getvisits(); ?></td>
					<td><?php echo $result->getscreenViews(); ?></td>
					<td><?php echo $result->getscreenviewspersession(); ?></td>
					<td><?php echo $result->getavgTimeOnSite();?></td>
					
					
				</tr>
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
    chart.draw(data, {width: 1100, height: 250, title: ''});
  }
  
 
 
</script>
 