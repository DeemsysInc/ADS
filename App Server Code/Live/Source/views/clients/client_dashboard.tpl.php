
<div id="control-bar" class="grey-bg clearfix" style="opacity: 1;"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="history.go(-1);return false;"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-180.png" width="16" height="16" > Back</button>
		</div>
		<div style="float:left; padding-top: 6px;text-align: center;width: 80%;"><h1 id="client_name"><?php echo isset($outArrClient[0]['name']) ? $outArrClient[0]['name'] : ''; ?></h1></div>
		<div class="float-right"> 
			<button type="button" class="red" onclick="deleteClient('<?php echo $outArrClient[0]['id']; ?>');">Delete Client</button> 
		</div>
			
	</div>
</div>
	<!-- Content -->
	<article class="container_12">
	
<style type="text/css">
ul.simple-list li span{
float:right;
}
</style>

<section class="grid_8">
	<div class="block-border">
	<form class="block-content form" id="complex_form" method="post" action="">
		<h1>Client Details</h1>
		<?php 
		
		
			if (isset($outArrClient[0]['logo']) && $outArrClient[0]['logo']!=""){
				$filepath=str_replace("{client_id}",$cid,$config['files']['logo']).$outArrClient[0]['logo'];
				$dispLogo = $filepath;
			}else{
				$dispLogo = $config['LIVE_URL']."views/images/no_logo.png";
			}
			if (isset($outArrClient[0]['background_image']) && $outArrClient[0]['background_image']!=""){
				
				$filepathBg=str_replace("{client_id}",$cid,$config['files']['background']).$outArrClient[0]['background_image'];
				$dispBackgroundImage = $filepathBg;
			}else{
				$dispBackgroundImage = $config['LIVE_URL']."views/images/no-product.png";
			}
			if (isset($outArrClient[0]['active']) && $outArrClient[0]['active']==1){
				$dispClientStatus = "YES";
			}else{
				$dispClientStatus = "NO";
			}
		?>
		<?php /*<ul class="message warning no-margin">
			<li>This is a <strong>warning message</strong>, inside a box</li>
			<li class="close-bt"></li>
		</ul> */ ?>
         <input type="hidden" name="c_id" id="c_id" value="<?php echo $cid;?>" />
         <input type="hidden" name="c_exist_logo" id="c_exist_logo" value="<?php echo $dispLogo; ?>" />
		<fieldset class="grey-bg required" style="height:40px;">
			<p style="float:left;">
				<img src="<?php echo $dispLogo; ?>" alt="client logo" height="40px" />
			</p>
			<p style="float:right;padding:12px;"><button onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $cid; ?>/edit/'" type="button" class="blue">Modify Logo</button></p>
		</fieldset>
        
        
        <?php /*?><fieldset class="grey-bg required" style="height:100px;">
			<p style="float:left;">
				<img  id="img_prev" src="<?php echo $dispLogo; ?>" alt="client logo" />
			</p>
			 <p style="float:right;padding:12px;"><input type="file" onchange="previewLogo(this);" id="c_logo" name="c_logo" /><button onclick="updateClientLogo();"  type="button" class="blue">Upload</button></p>
		</fieldset><?php */?>
   
		<div style="border:1px solid #CCC;padding: 10px;height:500px;">
			<div style="float:right;"><button onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $cid; ?>/edit/'" type="button" class="blue">Edit</button></div>
			<div class="clear"></div>
			<hr style="border-top: 1px dotted #afafaf;"/>
			<div class="columns">
				<div class="colx2-left">
					<span class="label">Background Image</span>
					<ul class="checkable-list">
						<li><img src="<?php echo $dispBackgroundImage; ?>" alt="client background image" width="300px" height="300px"/></li>
					</ul>
				</div>
				<p class="colx2-right">
					<ul class="checkable-list" style="line-height:3em">
						<li><span style="margin-right:33px;"><b>Client Name&nbsp;</b></span><span><?php echo $outArrClient[0]['name']; ?></span></li>
                        <li><span style="margin-right:33px;"><b>Client Prefix&nbsp;</b></span><span><?php echo $outArrClient[0]['prefix']; ?></span></li>
						<li><span style="margin-right:60px;"><b>Website&nbsp;</b></span><span><a href="<?php echo $outArrClient[0]['url']; ?>" target="_blank"><?php echo $outArrClient[0]['url']; ?></a></span></li>
						<li><span><b>Background Color&nbsp;</b></span><input id="bcid" class="minicolors" type="text" value="<?php echo $outArrClient[0]['background_color']; ?>"><input type="hidden" id="hdn_bcid" name="hdn_bcid" value="" /></li>
						<li><span style="margin-right:40px;"><b>Light Color:&nbsp;</b></span><input id="lcid" class="minicolors" type="text" value="<?php echo $outArrClient[0]['light_color']; ?>"><input type="hidden" id="hdn_lcid" name="hdn_bcid" value="" /></li>
						<li><span style="margin-right:40px;"><b>Dark Color:&nbsp;</b></span><input id="dcid" class="minicolors" type="text" value="<?php echo $outArrClient[0]['dark_color']; ?>"><input type="hidden" id="hdn_dcid" name="hdn_bcid" value="" /></li>
						<li><span style="margin-right:75px;"><b>Active&nbsp;</b></span><span><?php echo $dispClientStatus;?></span></li>
						<li><span style="margin-right:75px;"><b>Is Demo&nbsp;</b></span><span><?php if(isset($outArrClient[0]['is_demo']) && $outArrClient[0]['is_demo']==1){echo "YES";}else{echo "NO";}?></span></li>
						<li><span style="margin-right:75px;"><b>Client Vertical&nbsp;</b></span><span><?php echo isset($$outArrVerticalClients[0]['client_vertical_name']) ? $$outArrVerticalClients[0]['client_vertical_name'] :'None'; ?></span></li>
						
					</ul>
				</p>
			</div>
		</div>
	</form>
	</div>
	
</section>

<!-- <section class="grid_4">
	<div class="block-border"><form class="block-content form" id="simple_form" method="post" action="">
		<h1>New Products</h1>
		<?php if (count($outArrClientProducts)>0){ ?>
		<div class="block-controls">
			<ul class="controls-buttons">
				<li><a href="<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientProducts[0]['client_id']; ?>/products"><strong>View All Products</strong></a></li>
			</ul>
		</div>
		<?php } ?>
		<fieldset>
			<?php 
			if (count($outArrClientProducts)>0){
			for($i=0;$i<count($outArrClientProducts);$i++){
				if ($i<2){
					if ($outArrClientProducts[$i]['pd_image']!=""){
						$dispProductImage = str_replace("{client_id}",$outArrClientProducts[0]['client_id'],$config['files']['products']).$outArrClientProducts[$i]['pd_image'];
					}else{
						$dispProductImage = $config['LIVE_URL']."views/images/no-product.png";
					}
			?>
			<p>
				<label for="simple-calendar"><?php echo $outArrClientProducts[$i]['pd_name']; ?></label>
				<p style="text-align:center;"><img src="<?php echo $dispProductImage; ?>" alt="product" height="220px" width="250px;"/></p>
			</p>
			<?php } } }else{ ?>
				<p>There are no products.</p>
                <div align="right"><button onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $cid; ?>/products/add/'" type="button" class="blue">Add Product</button></div>
			<?php } ?>
			
		</fieldset>

			
	</form></div>
</section> -->
<section class="grid_4">
	<div class="block-border"><form class="block-content form" id="simple_form" method="post" action="">
		<h1>Client Stores</h1>
		<?php if (count($outArrClientStores)>0){ ?>
		<div class="block-controls">
			<ul class="controls-buttons">
				<li><a href="<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientStores[0]['client_id']; ?>/stores"><strong>View All Stores</strong></a></li>
			</ul>
		</div>
		<?php } ?>
		<fieldset>
	<?php if(count($outArrClientStores)>0) {?> 
		  
		<table width="100%">
		 <tbody>
		  <tr><th>Store Name</th><th>Latitude</th><th>Langitude</th></tr>
		  <?php if(count($outArrClientStores)>0) {?> 
		  <?php for($i=0;$i<count($outArrClientStores);$i++){?>
		  <tr style="background-color: #f8f8f8;">
		   <td style="border-bottom: 1px solid #cbcbcb;padding: 8px 8px 7px;"><div id="screenName"><?php echo $outArrClientStores[$i]['store_name'];?></div></td>
		   <td style="border-bottom: 1px solid #cbcbcb;padding: 8px 8px 7px;text-align: right;"><?php echo $outArrClientStores[$i]['latitude'];?></td>
		   <td style="border-bottom: 1px solid #cbcbcb;padding: 8px 8px 7px;text-align: right;"><?php echo $outArrClientStores[$i]['longitude'];?></td>
		  </tr>
            <?php } ?>
          <?php } else { ?>
                <p>There are no stores availabe.</p>
                <div align="right"><button onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $cid; ?>/stores/add/'" type="button" class="blue">Add Store</button></div>
			<?php } ?>
			
		  		   
		 </tbody>
		</table>

		<?php } else { ?>
                <p>There are no stores availabe.</p>
                <div align="right"><button onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $cid; ?>/stores/add/'" type="button" class="blue">Add Store</button></div>
			<?php } ?>			
		</fieldset>

			
	</form></div>
</section>

<section class="grid_12">
	<div class="block-border"><div class="block-content">
		<h1>Recent Triggers</h1>
		<?php if (count($outArrClientTriggers)>0){ ?>
		<div class="block-controls">
			<ul class="controls-buttons">
            <li><a href="<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientTriggers[0]['client_id']; ?>/triggers"><strong>View All Triggers</strong></a></li>
			</ul>
		</div>
		<?php } ?>
      

		<?php /*?><ul class="icon-list" style="height:160px;"><?php */?>
        
		<?php if(count($outArrClientTriggers)>0){?>
			<ul id="mycarousel2" class="jcarousel-skin-tango">
		<?php 
		   for($i=0;$i<count($outArrClientTriggers);$i++) {?>
			<li>
            <div class="imageSub" style="width: 150px;"> <!-- Put Your Image Width -->
            <?php if($outArrClientTriggers[$i]['url']==''){?>
           <img src="<?php echo $config['LIVE_URL']; ?>views/images/no-product.png" alt="<?php echo $outArrClientTriggers[$i]['title']?>" width="150" height="150"/>
            <?php }else{?>
				
           <a href="<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientTriggers[0]['client_id']; ?>/triggers/<?php echo $outArrClientTriggers[$i]['id']; ?>/view"><img src="<?php echo str_replace("{client_id}",$outArrClientTriggers[0]['client_id'],$config['files']['triggers']).$outArrClientTriggers[$i]['url'];?>" alt="<?php echo $outArrClientTriggers[$i]['title']?>" width="150" height="150"/>
        </a>
		<?php }?>
        <div class="blackbg"></div>
        <div class="label"><?php echo $outArrClientTriggers[$i]['title']?></div>
      </div>
				
				
			</li>
		<?php }  ?>
		</ul>
		
		<ul class="message no-margin">
			<li>More Triggers >></li>
		</ul>
		<?php } else {?>
		<p>There are no triggers.</p>
        <div align="right"><button onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $cid; ?>/triggers/add/'" type="button" class="blue">Add Trigger</button></div>
		<?php } ?>
	</div></div>
</section>
<section class="grid_12">
	<div class="block-border"><div class="block-content">
		<h1>Recent Products</h1>
		<?php if (count($outArrClientProducts)>0){ ?>
		<div class="block-controls">
			<ul class="controls-buttons">
            <li><a href="<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientProducts[0]['client_id']; ?>/products"><strong>View All Products</strong></a></li>
			</ul>
		</div>
		<?php } ?>
      

		<?php /*?><ul class="icon-list" style="height:160px;"><?php */?>
        
		<?php if(count($outArrClientProducts)>0){?>
			<ul id="mycarousel3" class="jcarousel-skin-tango">
		<?php 
		   for($i=0;$i<count($outArrClientProducts);$i++) {?>
			<li>
            <div class="imageSub" style="width: 150px;"> <!-- Put Your Image Width -->
            <?php if($outArrClientProducts[$i]['pd_image']==''){?>
           <img src="<?php echo $config['LIVE_URL']; ?>views/images/no-product.png" alt="<?php echo $outArrClientProducts[$i]['pd_name']?>" width="150" height="150"/>
            <?php }else{?>
				
           <a href="<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientProducts[0]['client_id']; ?>/products/<?php echo $outArrClientProducts[$i]['pd_id']; ?>/view"><img src="<?php echo str_replace("{client_id}",$outArrClientProducts[0]['client_id'],$config['files']['products']).$outArrClientProducts[$i]['pd_image'];?>" alt="<?php echo $outArrClientProducts[$i]['pd_name']?>" width="150" height="150"/>
        </a>
		<?php }?>
        <div class="blackbg"></div>
        <div class="label"><?php echo $outArrClientProducts[$i]['pd_name']?></div>
      </div>
				
				
			</li>
		<?php }  ?>
		</ul>
		
		<ul class="message no-margin">
			<li>More Products >></li>
		</ul>
		<?php } else {?>
		<p>There are no products.</p>
        <div align="right"><button onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $cid; ?>/products/add/'" type="button" class="blue">Add Product</button></div>
		<?php } ?>
	</div></div>
</section>

<div class="clear"></div>

</article>
<!-- End content -->