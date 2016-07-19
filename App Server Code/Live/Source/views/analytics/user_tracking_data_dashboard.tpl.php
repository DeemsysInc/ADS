
<style>
  article, aside, figure, footer, header, hgroup, 
  menu, nav, section { display: block; }
  #modal1 {
    width: auto;
    border: 1px solid #CCC;
    box-shadow: 0 1px 5px #CCC;
    border-radius: 5px;
    font-family: verdana;
    /*margin: 25px auto;*/
    margin-left: 17px;
    overflow: hidden;
    height:225px;
  }
  #modal1 div {
    background: #f1f1f1;
    background-image: -webkit-linear-gradient( top, #f1f1f1, #CCC );
    background-image: -ms-linear-gradient( top, #f1f1f1, #CCC );
    background-image: -moz-linear-gradient( top, #f1f1f1, #CCC );
    background-image: -o-linear-gradient( top, #f1f1f1, #CCC );
    box-shadow: 0 1px 2px #888;
    padding: 10px;
  }
  #modal1 span {
    padding: 0;
    margin: 0;
    font-size: 16px;
    font-weight: normal;
    text-shadow: 0 1px 2px white;
    color: #888;
    text-align: center;
  }
  #modal1 section {
    padding: 10px 30px; 
    font-size: 12px;
    line-height: 175%;
    color: #333;
  }
  #modal1 section span{
text-align: center;
font-size: 50px;
padding: 61px 0px 26px;
float: left;
width: 100%;
  }
</style>

<div id="control-bar" class="grey-bg clearfix" style="opacity: 1;"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="history.go(-1);return false;"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-180.png" width="16" height="16" > Back</button>
		</div>
        <div style="float:left; padding-top: 6px;text-align: center;width: 80%;"><h1 id="client_name">Mobile Analytics</h1></div>
		  <div class="float-left">
       <?php if(isset($arrData[0]['client_info'][0]['logo']) && $arrData[0]['client_info'][0]['logo']!=""){
              $dispClientImage=str_replace("{client_id}",$arrData[0]['client_info'][0]['id'],$config['files']['logo']).$arrData[0]['client_info'][0]['logo'];
           
              ?>

           <img width="185" src="<?php echo $dispClientImage;?>" >
           <?php  }else{
              echo $dispClientImage=isset($arrData[0]['client_info'][0]['name']) ? $arrData[0]['client_info'][0]['name'] : '';
            }?>
         
      
    </div>
			
	</div>
</div>
<div align="right">
<form  id="table_form" method="post" action="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/">
  
  <div style="width:50%;">
    <?php if(isset($_SESSION['user_group']) && $_SESSION['user_group'] !=3){?>
<div style="float:left;width:35%;">
 <label for="id_select">Clients</label>
    <!-- <select id="id_select" class="selectpicker bla bla bli" data-live-search="true">
       <?php for($i=0;$i<count($arrData[0]['total_clients']);$i++){?>
       <?php if($clientSearch==$arrData[0]['total_clients'][$i]['id']){?>
        <option selected="selected" value="<?php echo isset($arrData[0]['total_clients'][$i]['id']) ? $arrData[0]['total_clients'][$i]['id'] : 0;?>"><?php echo isset($arrData[0]['total_clients'][$i]['name']) ? $arrData[0]['total_clients'][$i]['name'] : '';?></option>
        <?php }else{?>
        <option value="<?php echo isset($arrData[0]['total_clients'][$i]['id']) ? $arrData[0]['total_clients'][$i]['id'] : 0;?>"><?php echo isset($arrData[0]['total_clients'][$i]['name']) ? $arrData[0]['total_clients'][$i]['name'] : '';?></option>
       <?php }?>
       <?php }?>
    </select> -->



 <select name="byClient" id="byClient">
   <?php for($i=0;$i<count($arrData[0]['total_clients']);$i++){?>
   <?php if($clientSearch==$arrData[0]['total_clients'][$i]['id']){?>
    <option selected="selected" value="<?php echo isset($arrData[0]['total_clients'][$i]['id']) ? $arrData[0]['total_clients'][$i]['id'] : 0;?>"><?php echo isset($arrData[0]['total_clients'][$i]['name']) ? $arrData[0]['total_clients'][$i]['name'] : '';?></option>
    <?php }else{?>
    <option value="<?php echo isset($arrData[0]['total_clients'][$i]['id']) ? $arrData[0]['total_clients'][$i]['id'] : 0;?>"><?php echo isset($arrData[0]['total_clients'][$i]['name']) ? $arrData[0]['total_clients'][$i]['name'] : '';?></option>
   <?php }?>
   <?php }?>
  </select>
</div>
<?php }?>
<div> 

	From:<input type="text" id="from" name="from" value="<?php echo $start_date;?>">to:<input type="text" id="to" name="to" value="<?php echo $end_date;?>">&nbsp;&nbsp;&nbsp;<input type="submit" value="Apply">
	
</div>  
</div>

</form>
</div>
	<!-- Content -->
	<article class="container_12">

<style type="text/css">
ul.simple-list li span{
float:right;
}

</style>
   <?php 

  $byClient =  isset($_POST['byClient']) ? $_POST['byClient'] : 814;
  if (($_SESSION['user_group'] ==1) || ($_SESSION['user_group'] ==2) || ($_SESSION['user_group'] ==6)) {//super admin and admin
        
    if($byClient!=0)
    {
      $clientInfo = "?cid=".$byClient; 


    }      
    else
    {
      $clientInfo = "?cid=814"; 

    }      
  }
  else
  {
    $clientInfo = "";

  } 
    
  ?>			

 
<section class="grid_12">
	<div class="block-border"><form class="block-content form" id="table_form" method="post" action="">
		<h1>Analytic Counter</h1>
		<table width="100%">
		<tr>
		<td>
		 <div id="modal1">
    <div style="text-align: center;"><span>Product Views</span></div>
    <section>
      <span id="count"><a href="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/clients/<?php echo $arrData[0]['client_info'][0]['id'];?>/products/<?php echo $start_date;?>/<?php echo $end_date;?>"><?php echo isset($arrData[0]['productViews']) ? $arrData[0]['productViews'] : 0;?></a></span
      
    </section>
  </div>
         </td>
         <td>
         <div id="modal1">
    <div style="text-align: center;"><span>Offer Views</span></div>
    <section>
       <span id="count"><a href="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/clients/<?php echo $arrData[0]['client_info'][0]['id'];?>/offers/<?php echo $start_date;?>/<?php echo $end_date;?>"><?php echo isset($arrData[0]['offerViews']) ? $arrData[0]['offerViews'] : 0;?></a></span
      
    </section>
  </div>
        </td>
        <td>
		 <div id="modal1">
    <div style="text-align: center;"><span> Products Shared </span></div>
    <section>
        <span id="count"><a href="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/clients/<?php echo $arrData[0]['client_info'][0]['id'];?>/products/<?php echo $start_date;?>/<?php echo $end_date;?>"><?php echo isset($arrData[0]['product_shareViews']) ? $arrData[0]['product_shareViews'] : 0;?></a></span
      
    </section>
  </div>
         </td>
         <td>
		 <div id="modal1">
    <div style="text-align: center;"><span>Offers Shared</span></div>
    <section>
        <span id="count"><a href="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/clients/<?php echo $arrData[0]['client_info'][0]['id'];?>/offers/<?php echo $start_date;?>/<?php echo $end_date;?>"><?php echo isset($arrData[0]['offer_shareViews']) ? $arrData[0]['offer_shareViews'] : 0;?></a></span
      
    </section>
  </div>
         </td>
  </tr>
  </table>
  
  
	</form></div>
</section>

<div class="clear"></div>

 <section class="grid_6">
  <div class="block-border">
	<form class="block-content form" id="simple_form" method="post" action="" style="height: 250px;">
		<a href="#"><h1>Product Views</h1></a>
			
	      <div id="chart_products_views" style="width: auto;height: 250px"><div align="center"><img src="<?php echo $config['LIVE_URL']; ?>views/images/ajax-loader.gif" width="100" height="100"></div></div>
	   
    </form>
 </div>
</section>
<section class="grid_6">
  <div class="block-border">
	<form class="block-content form" id="simple_form" method="post" action="" style="height: 250px;">
		<a href="#"><h1>Offer Views</h1></a>
			
	      <div id="chart_offers_views" style="width: auto;height: 250px"><div align="center"><img src="<?php echo $config['LIVE_URL']; ?>views/images/ajax-loader.gif" width="100" height="100"></div></div>
	   
    </form>
 </div>
</section>

<div class="clear"></div>

 <section class="grid_6">
  <div class="block-border">
  <form class="block-content form" id="simple_form" method="post" action="" style="height: 250px;">
    <a href="#"><h1>Products Shared</h1></a>
      
        <div id="chart_products_shared" style="width: auto;height: 250px"><div align="center"><img src="<?php echo $config['LIVE_URL']; ?>views/images/ajax-loader.gif" width="100" height="100"></div></div>
     
    </form>
 </div>
</section>
<section class="grid_6">
  <div class="block-border">
  <form class="block-content form" id="simple_form" method="post" action="" style="height: 250px;">
    <a href="#"><h1>Offers Shared</h1></a>
      
        <div id="chart_offers_shared" style="width: auto;height: 250px" ><div align="center"><img src="<?php echo $config['LIVE_URL']; ?>views/images/ajax-loader.gif" width="100" height="100"></div></div>
     
    </form>
 </div>
</section>

<div class="clear"></div>


<?php /*?>
<section class="grid_12">
	<div class="block-border"><form class="block-content form" id="table_form" method="post" action="">
		<h1>Screen Info</h1>
		
		<table class="table sortable no-margin" cellspacing="0" width="100%" id="cms_clients">
		
			<thead>
				<tr>
					<!--<th class="black-cell"><span class="loading"></span></th>-->
					<th scope="col">
						Screen Name
					</th>
					<th scope="col">
						<span class="column-sort">
							<a href="#" title="Sort up" class="sort-up"></a>
							<a href="#" title="Sort down" class="sort-down"></a>
						</span>
						Screen Views
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
				<?php for($i=0;$i<count($arrData);$i++){?>
					
				<tr>
					<td><?php echo isset($arrData[$i]['screen_name']) ? $arrData[$i]['screen_name'] : ''; ?></td>
					<td><?php echo isset($arrData[$i]['screen_count']) ? $arrData[$i]['screen_count'] : ''; ?></td>
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
<?php */?>
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
  

 