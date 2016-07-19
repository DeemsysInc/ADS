 <section id="main-content">
    <section class="wrapper">
		<div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumbs-alt">
                        <li>
                           <a href="<?php echo $config['LIVE_URL'];?>">Dashboard</a>
                        </li>
                        <li>
                            <a class="current" href="#">Triggers List</a>
                        </li>
                    </ul>
                </div>
        </div>
    <div style="clear: both;"></div>
       <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                       Trigger Info
                        <span class="tools pull-right">
                            <a href="javascript:;" class="fa fa-chevron-down"></a>
                            <a href="javascript:;" class="fa fa-cog"></a>
                            <a href="javascript:;" class="fa fa-times"></a>
                        </span>
                    </header>
                    <div class="panel-body">
                    <div class="adv-table">
					<div align="right">
		                <a href="<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $cid;?>/triggers/add/" class="btn btn-success"> Add Trigger </a>&nbsp;&nbsp; 
	               </div>
                    <table  class="display table table-bordered table-striped" id="dynamic-table">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Image</th>
                        <th>Height</th>
                        <th class="hidden-phone">Width</th>
                        <th class="hidden-phone">Instruction</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
					<tbody>
					<?php for($i=0;$i<count($outArrClientTriggers);$i++) { 
						if(isset($outArrClientTriggers[$i]['active']) && $outArrClientTriggers[$i]['active']!=2)
						{
							if($outArrClientTriggers[$i]['url']!=""){
								$dispTriggerImage=str_replace("{client_id}",$outArrClientTriggers[$i]['client_id'],$config['files']['triggers']).$outArrClientTriggers[$i]['url'];
							}else{
								$dispTriggerImage=$config['LIVE_URL']."views/images/no-product.png";
							}
							if ($outArrClientTriggers[$i]['active']=="1"){
								$dispClientTriggerStatus = "Active";
							}else{
								$dispClientTriggerStatus = "In active";
							}
					?>
				 <tr>
					<td><?php echo $outArrClientTriggers[$i]['title']; ?></td>
					<td style="text-align:center;"><a href="<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientTriggers[$i]['client_id']; ?>/triggers/<?php echo $outArrClientTriggers[$i]['id']; ?>/view/">&nbsp;&nbsp;<img src="<?php echo $dispTriggerImage; ?>" height="100"/></a></td>
					<td><?php echo $outArrClientTriggers[$i]['height']; ?></td>
					<td><?php echo $outArrClientTriggers[$i]['width']; ?></td>
                    <td><?php echo $outArrClientTriggers[$i]['instruction']; ?></td>
					<td><?php echo $dispClientTriggerStatus; ?></td>
					<td>
					<a href="<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientTriggers[$i]['client_id']; ?>/triggers/<?php echo $outArrClientTriggers[$i]['id']; ?>/view/" class="btn btn-success">View</a>&nbsp;&nbsp;
                    <a href="<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientTriggers[$i]['client_id']; ?>/triggers/<?php echo $outArrClientTriggers[$i]['id']; ?>/edit/" class="btn btn-warning cancel">Edit</a><br /><br />
                    <a href="<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientTriggers[$i]['client_id']; ?>/triggers/<?php echo $outArrClientTriggers[$i]['id']; ?>/visuals/"  class="btn btn-success">Visuals</a>&nbsp;&nbsp;
                    <button type="button" class="btn btn-danger delete" onclick="deleteTrigger(<?php echo $outArrClientTriggers[$i]['client_id']; ?>,<?php echo $outArrClientTriggers[$i]['id']; ?>);">Delete</button></td>
                    <?php /*?><button type="button" class="blue" onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientTriggers[$i]['client_id']; ?>/triggers/<?php echo $outArrClientTriggers[$i]['id']; ?>/view/'">View</button><br /><br /><button type="button" class="blue" onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientTriggers[$i]['client_id']; ?>/triggers/<?php echo $outArrClientTriggers[$i]['id']; ?>/edit/'">Edit</button><br /><br /><button type="button" class="blue" onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientTriggers[$i]['client_id']; ?>/triggers/<?php echo $outArrClientTriggers[$i]['id']; ?>/visuals/'">Visuals</button><br /><br /><button type="button" class="blue" onclick="deleteTrigger(<?php echo $outArrClientTriggers[$i]['client_id']; ?>,<?php echo $outArrClientTriggers[$i]['id']; ?>);">Delete</button><?php */?></td>
				</tr>
				<?php } }?>
			</tbody>
			 <tfoot>
                    <tr>
					    <th>Title</th>
                        <th>Image</th>
                        <th>Height</th>
                        <th class="hidden-phone">Width</th>
                        <th class="hidden-phone">Instruction</th>
                        <th>Status</th>
                        <th>Action</th>
                     </tr>
					 </tfoot>
                    </table>
                </div>
                  </div>
                </section>
            </div>
        </div>
        <!-- page end-->
        </section>
    </section>
					
					
					
					
					
					
					
					
					
					
					
					