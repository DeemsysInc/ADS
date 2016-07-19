<div id="control-bar" class="grey-bg clearfix" style="opacity: 1;"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="history.go(-1);return false;"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-180.png" width="16" height="16" > Back</button>
		</div>
        <div style="float:left; padding-top: 6px;text-align: center;width: 80%;"><h1 id="client_name">User tracking Data</h1></div>
		
			
	</div>
</div>

<div align="right">
	<form  id="table_form" method="post">
	From:<input type="text" id="from" name="from" value="<?php echo $start_date;?>">to:<input type="text" id="to" name="to" value="<?php echo $end_date;?>">&nbsp;&nbsp;&nbsp;<input type="submit" value="Apply" Onclick="return submitDateRange('<?php echo isset($outArrGetProdInfo[0]['pd_id']) ? $outArrGetProdInfo[0]['pd_id'] : '';?>');">
	</form>
</div>
	<!-- Content -->
	<article class="container_12">
		
<style type="text/css">
ul.simple-list li span{
float:right;
}
</style>
<style type="text/css">


.tree ul {
  padding-top: 20px; position: relative;
  
  transition: all 0.5s;
  -webkit-transition: all 0.5s;
  -moz-transition: all 0.5s;
}

.tree li {
  float: left; text-align: center;
  list-style-type: none;
  position: relative;
  padding: 20px 5px 0 5px;
  
  transition: all 0.5s;
  -webkit-transition: all 0.5s;
  -moz-transition: all 0.5s;
}

/*We will use ::before and ::after to draw the connectors*/

.tree li::before, .tree li::after{
  content: '';
  position: absolute; top: 0; right: 50%;
  border-top: 1px solid #ccc;
  width: 50%; height: 20px;
}
.tree li::after{
  right: auto; left: 50%;
  border-left: 1px solid #ccc;
}

/*We need to remove left-right connectors from elements without 
any siblings*/
.tree li:only-child::after, .tree li:only-child::before {
  display: none;
}

/*Remove space from the top of single children*/
.tree li:only-child{ padding-top: 0;}

/*Remove left connector from first child and 
right connector from last child*/
.tree li:first-child::before, .tree li:last-child::after{
  border: 0 none;
}
/*Adding back the vertical connector to the last nodes*/
.tree li:last-child::before{
  border-right: 1px solid #ccc;
  border-radius: 0 5px 0 0;
  -webkit-border-radius: 0 5px 0 0;
  -moz-border-radius: 0 5px 0 0;
}
.tree li:first-child::after{
  border-radius: 5px 0 0 0;
  -webkit-border-radius: 5px 0 0 0;
  -moz-border-radius: 5px 0 0 0;
}

/*Time to add downward connectors from parents*/
.tree ul ul::before{
  content: '';
  position: absolute; top: 0; left: 50%;
  border-left: 1px solid #ccc;
  width: 0; height: 20px;
}

.tree li a{
  border: 1px solid #ccc;
  padding: 35px 70px;
  text-decoration: none;
  color: #666;
  font-family: arial, verdana, tahoma;
  font-size: 11px;
  display: inline-block;
  
  border-radius: 5px;
  -webkit-border-radius: 5px;
  -moz-border-radius: 5px;
  
  transition: all 0.5s;
  -webkit-transition: all 0.5s;
  -moz-transition: all 0.5s;
}

/*Time for some hover effects*/
/*We will apply the hover effect the the lineage of the element also*/
.tree li a:hover, .tree li a:hover+ul li a {
  background: #c8e4f8; color: #000; border: 1px solid #94a0b4;
}
/*Connector styles on hover*/
.tree li a:hover+ul li::after, 
.tree li a:hover+ul li::before, 
.tree li a:hover+ul::before, 
.tree li a:hover+ul ul::before{
  border-color:  #94a0b4;
}

/*Thats all. I hope you enjoyed it.
Thanks :)*/


</style>
<section class="grid_12">
  <div class="block-border">
	<form class="block-content form" id="simple_form" method="post" action="" style="height: 250px;">
		<a href="#"><h1>Product Info</h1></a>
		<div>
			<div style="width:39%;float:left">
				<?php 
				        if(isset($outArrGetProdInfo[0]['pd_image']) && $outArrGetProdInfo[0]['pd_image']!=""){
							
							$dispProductImage=str_replace("{client_id}",$outArrGetProdInfo[0]['client_id'],$config['files']['products']).$outArrGetProdInfo[0]['pd_image'];
						}else{
							$dispProductImage=$config['LIVE_URL']."views/images/no-product.png";
						}

				?>		
				<img src="<?php echo $dispProductImage;?>" width="250px" height="250px">


			</div>
			<div>
				<h2><?php echo isset($outArrGetProdInfo[0]['pd_name']) ? $outArrGetProdInfo[0]['pd_name'] : '';?></h2>
<table id="Table2" cellspacing="0" cellpadding="0" width="50%" border="0">
				    <tbody><tr>
					    <td valign="top" colspan="2" align="center"><a onclick="MM_openBrWindow('image-display.aspx','abc','scrollbars=yes')" href="#"></a>
                        </td>
				    </tr>
				    <tr>
					    <td align="center" colspan="2">
                          &nbsp;
                        </td>
				    </tr>
				    <tr>
					    <td width="30%" class="txt" style="height: 19px"><b>Scanned</b></td>
					    <td style="height: 19px"><span id="_ctl0_ContentPlaceHolder1_lblProductCode" class="txt"><?php echo count($arrDataTotalScannedPids);?></span></td>
				    </tr>
				    <tr>
					    <td width="30%" class="txt"><b>Cart</b></td>
					    <td><span id="_ctl0_ContentPlaceHolder1_lblCategoryName" class="txt"><?php echo count($arrDataTotalCartPids);?></span></td>
				    </tr>
				    <tr>
					    <td class="txt"><b>Closet</b></td>
					    <td><span id="_ctl0_ContentPlaceHolder1_lblTitle" class="txt"><?php echo count($arrDataTotalPdsCloset);?></span></td>
				    </tr>
				    
				    <tr>
					    <td valign="top" class="txt"><b>Shared</b></td>
					    <td><span id="_ctl0_ContentPlaceHolder1_lblDescription" class="txt"><?php echo count($totalShared);?></strong></span></td>
				    </tr>
				    <tr>
					    <td valign="top" class="txt"><b>Wishlists</b></td>
					    <td><span id="_ctl0_ContentPlaceHolder1_lblDescription" class="txt"><?php echo count($arrDataTotalPIdsWishlists);?></span></td>
				    </tr>
             <tr>
              <td valign="top" class="txt"><b>Detail Page</b></td>
              <td><span id="_ctl0_ContentPlaceHolder1_lblDescription" class="txt"><?php echo count($arrDataTotalPids);?></span></td>
            </tr>
			    </tbody></table>



</div>
			
		</div>
		
	      
    </form>
 </div>
</section>
<div class="clear"></div>
<section class="grid_12">
  <div class="block-border">
	<form class="block-content form" id="simple_form" method="post" action="" style="height: 450px;">
		<a href="#"><h1>Product Flow</h1></a>
		
		<div class="tree">
  <ul>
    <li>
      <a href="#"><div><div style="font-size:17px;">Scanned</div><br><div><h2><?php echo count($arrDataTotalScannedPids);?></h2></div></div></a>

      <ul>
        <li>
          <a href="#"><div><div style="font-size:17px;">Cart</div><div><h2><?php echo count($arrDataTotalCartPids);?></h2></div></div></a>
          
        </li>
        <li>
          <a href="#"><div><div style="font-size:17px;">Share</div><div><h2><?php echo count($arrDataTotalSharedPids);?></h2></div></div></a>
          <ul>
            <li>
              <a href="#"><div><div style="font-size:17px;">FB</div><div><h2><?php echo count($arrDataTotalSharedFacebookPids);?></h2></div></div></a>
            </li>
            <li>
              <a href="#"><div><div style="font-size:17px;">Twitter</div><div><h2><?php echo count($arrDataTotalSharedTwitterPids);?></h2></div></div></a>
            </li>
            <li>
              <a href="#"><div><div style="font-size:17px;">email</div><div><h2><?php echo count($arrDataTotalSharedEmailPids);?></h2></div></div></a>
            </li>
          </ul>
        </li>
        <li>
          <a href="#"><div><div style="font-size:17px;">Closet</div><div><h2><?php echo count($arrDataTotalPdsCloset);?></h2></div></div></a>
          <ul>
          	<li>
          		<a href="#"><div><div style="font-size:17px;">Wishlists</div><div><h2><?php echo count($arrDataTotalPIdsWishlists);?></h2></div></div></a>
          	</li>
          </ul>
        </li>
      </ul>
    </li>
  </ul>
</div>	
	      
    </form>
 </div>
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