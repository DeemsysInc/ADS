<section class="grid_12">
  <div class="block-border"><form class="block-content form" id="table_form" method="post" action="">
    <h1>Analytic Counter</h1>
    <table width="100%">
    <tr>
    <td>
     <div id="modal1">
    <div style="text-align: center;"><span>Product Views</span></div>
    <section>
      <span id="count"><a href="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/product_views/"><?php echo number_format($totalProductViews);?></a></span
      
    ></section>
  </div>
         </td>
         <td>
         <div id="modal1">
    <div style="text-align: center;"><span>Offer Views</span></div>
    <section>
       <span id="count"><a href="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/offer_views/"><?php echo number_format($totalOfferViews);?></a></span
      
    ></section>
  </div>
        </td>
        <td>
     <div id="modal1">
    <div style="text-align: center;"><span> Unique Product Views </span></div>
    <section>
        <span id="count"><a href="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/client_products/"><?php echo number_format($totalProducts);?></a></span
      
    ></section>
  </div>
         </td>
         <td>
     <div id="modal1">
    <div style="text-align: center;"><span>Unique Offer Views</span></div>
    <section>
        <span id="count"><a href="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/client_offers/"><?php echo number_format($totalOffers);?></a></span
      
    ></section>
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
  <a href="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/product_views/"><h1>Product Views</h1></a>
    
      <div id="chart_products_views"><div align="center"><img src="<?php echo $config['LIVE_URL']; ?>views/images/ajax-loader.gif" width="100" height="100"></div></div>
   
  </form>
</div>
</section>
<section class="grid_6">
<div class="block-border">
<form class="block-content form" id="simple_form" method="post" action="" style="height: 250px;">
  <a href="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/offer_views/"><h1>Offer Views</h1></a>
    
      <div id="chart_offers_views" ><div align="center"><img src="<?php echo $config['LIVE_URL']; ?>views/images/ajax-loader.gif" width="100" height="100"></div></div>
   
  </form>
</div>
</section>

<div class="clear"></div>
<script src="http://www.google.com/jsapi"></script>
  <script>
  
    // jQuery(document).ready(function($) {
    //   getChartProductViews();
    //   getChartOfferViews();
    // });
  alert("hello");
    
  //google.load("visualization", "1", {packages:["corechart"]});
  
  function drawChartProductsTrack() {
	  alert("hello 1");
    var data = new google.visualization.DataTable();

    <!-- Create the data table -->
    data.addColumn('string', 'Day');
    data.addColumn('number', 'Product Views');

    data.addRows([
     <?php
     for($i=0;$i<count($arrDataForGraph);$i++)
     {
         echo '["'.date('M j,Y',strtotime($arrDataForGraph[$i]['dates'])).'", '.$arrDataForGraph[$i]['productids'].'],';
     }
      ?>
    ]);

   
     var chart = new google.visualization.AreaChart(document.getElementById('chart_products_views'));
    chart.draw(data, {width: 550, height: 250, title: '',
                      colors:['#058dc7','#e6f4fa'],
                      areaOpacity: 0.1,
                      hAxis: {textPosition: 'bottom', showTextEvery: 20, slantedText: false, textStyle: { color: '#058dc7', fontSize: 10 } },
                      pointSize: 7,
                      legend: 'right'
    });
    
  }
  /*google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChartOffersTrack);
  function drawChartOffersTrack() {
    var data = new google.visualization.DataTable();

    <!-- Create the data table -->
    data.addColumn('string', 'Day');
    data.addColumn('number', 'Offer Views');

    data.addRows([
    <?php
     for($i=0;$i<count($arrDataForGraph);$i++){
     echo '["'.date('M j,Y',strtotime($arrDataForGraph[$i]['dates'])).'", '.$arrDataForGraph[$i]['offerids'].'],';
     }?>
     ]);

   
     var chart = new google.visualization.AreaChart(document.getElementById('chart_offers_views'));
    chart.draw(data, {width: 550, height: 250, title: '',
                      colors:['#058dc7','#e6f4fa'],
                      areaOpacity: 0.1,
                      hAxis: {textPosition: 'bottom', showTextEvery: 20, slantedText: false, textStyle: { color: '#058dc7', fontSize: 10 } },
                      pointSize: 7,
                      legend: 'right'
    });
    
  }*/
 


  </script>
