
<div id="control-bar" class="grey-bg clearfix" style="opacity: 1;"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="history.go(-1);return false;"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-180.png" width="16" height="16" > Back</button>
		</div>
		<?php /*?><div style="float:left; padding-top: 6px;text-align: center;width: 80%;"><h1 id="client_name"><?php echo $outArrClientTriggerView[0]['name']; ?></h1></div>
		<?php */?>
			<div class="float-right"> 
			<button type="button" class="red"  onclick="deleteTrigger(<?php echo $cid;?>,<?php echo isset($outArrClientTriggerView[0]['client_id']) ? $outArrClientTriggerView[0]['client_id'] : 0; ?>);">Delete Trigger</button> 
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

<section class="grid_12">
	<div class="block-border">
	<form class="block-content form" id="complex_form" method="post" action="">
		<h1>Trigger Details</h1>
		<?php 
			
			if ($outArrClientTriggerView[0]['url']!=""){
				
				$triggerImage = str_replace("{client_id}",$outArrClientTriggerView[0]['client_id'],$config['files']['triggers']).$outArrClientTriggerView[0]['url']; 
			}else{
				$triggerImage = $config['LIVE_URL']."views/images/no-product.png";
			}
			if ($outArrClientTriggerView[0]['active']=="1"){
				$dispClientStatus = "YES";
			}else{
				$dispClientStatus = "NO";
			}
		?>
		 <input type="hidden" name="c_id" id="c_id" value="<?php echo $cid;?>" />
        
		<div style="border:1px solid #CCC;padding: 10px;height:auto;">
			<div style="float:right;"><button type="button" class="blue" onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientTriggerView[0]['client_id']; ?>/triggers/<?php echo $outArrClientTriggerView[0]['id']; ?>/visuals/'">Visuals</button>&nbsp;&nbsp;<button onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientTriggerView[0]['client_id']; ?>/triggers/<?php echo $outArrClientTriggerView[0]['id']; ?>/edit/'" type="button" class="blue">Edit</button></div>
			<div class="clear"></div>
			<hr style="border-top: 1px dotted #afafaf;"/>
		  <div class="columns">
				<div class="colx2-left">
					<span class="label">Trigger Image</span>
					<ul class="checkable-list">
						<li><img src="<?php echo $triggerImage; ?>" alt="client trigger image" width="320px" height="250px"/></li>
					</ul>
				</div>
			<p class="colx2-right">
              <table width="386">
                <tr>
                  <td width="125" height="35"><b>Title</b></td>
                  <td width="249"><span style="line-height: 2.3em;"><?php echo $outArrClientTriggerView[0]['title']; ?></span></td>
                </tr>
                <tr>
                  <td height="38"><b>Instruction</b></td>
                  <td><span style="line-height: 2.3em;"><?php echo $outArrClientTriggerView[0]['instruction']; ?></span></td>
                </tr>
                <tr>
                  <td height="32"><b>Height</b></td>
                  <td><span style="line-height: 2.3em;"><?php echo $outArrClientTriggerView[0]['height']; ?></span></td>
                </tr>
                <tr>
                  <td height="43"><b>width</b></td>
                  <td><span style="line-height: 2.3em;"><?php echo $outArrClientTriggerView[0]['width']; ?></span></td>
                </tr>
                 <tr>
                  <td height="43"><b>Status</b></td>
                  <td><span style="line-height: 2.3em;"><?php echo $dispClientStatus; ?></span></td>
                </tr>
              </table>

			  </p>
			</div>
		</div>
	</form>
	</div>
	
</section>


<div class="clear"></div>
<script type="text/javascript">
	
</script>
</article>
<!-- End content -->