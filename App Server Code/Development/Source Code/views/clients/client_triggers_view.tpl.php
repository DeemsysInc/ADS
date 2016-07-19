<section id="main-content">
    <section class="wrapper">
     <!-- page start-->
        <div class="row">
            <div class="col-md-12">
                <ul class="breadcrumbs-alt">
                    <li>
					   <a href="<?php echo $config['LIVE_URL'];?>">Dashboard</a>
                    </li>
                    <li>
                        <a href="<?php echo $config['LIVE_URL'];?>clients/id/<?php echo $cid; ?>/triggers/">Triggers List</a>
                    </li>
                    <li>
                        <a class="current" href="#">Triggers Details</a>
                    </li>
                    </ul>
                </div>
        </div>   
<div style="clear: both;"></div>
    <div class="row">
        <div class="col-md-12">
            <section class="panel">
                <header class="panel-heading">
                    Triggers Details
                        <span class="tools pull-right">
                            <a href="javascript:;" class="fa fa-chevron-down"></a>
                            <a href="javascript:;" class="fa fa-cog"></a>
                            <a href="javascript:;" class="fa fa-times"></a>
                        </span>
                </header>
                   <div class="panel-body profile-information">
                      <div class="col-md-3">
                        <div class="profile-pic text-center">
						 <?php if ($outArrClientTriggerView[0]['url']!=""){
							
							$triggerImage = str_replace("{client_id}",$outArrClientTriggerView[0]['client_id'],$config['files']['triggers']).$outArrClientTriggerView[0]['url']; 
						}else{
							$triggerImage = $config['LIVE_URL']."views/images/no-product.png";
						}
						if ($outArrClientTriggerView[0]['active']=="1"){
							$dispClientStatus = "Active";
						}else{
							$dispClientStatus = "In Active";
						} ?>
						 <input type="hidden" name="c_id" id="c_id" value="<?php echo $cid;?>" />
						 <div id="frm_error" style="display:none;"></div>
						<fieldset class="grey-bg required" style="height:40px;">
						   <table width="500" border="0">
						   <tr><td>
						   <p style="float:left;">Trigger Image</p> </td></tr>
						   <tr><td> <p style="float:left;">
						   <img src="<?php echo $triggerImage; ?>" alt="client trigger image" width="320px" height="250px"/>
						   </p>
						   </td></tr>
						   </table>
						</fieldset>				   
		                </div>
                      </div>
			   <div class="col-md-9">
                  <div class="profile-desk">
					<input type="hidden" name="c_id" id="c_id" value="<?php echo $cid;?>" />
					 <div style="float:right;"><button type="button" class="btn btn-success" onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientTriggerView[0]['client_id']; ?>/triggers/<?php echo $outArrClientTriggerView[0]['id']; ?>/visuals/'">Visuals</button>
					 &nbsp;&nbsp;<button onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientTriggerView[0]['client_id']; ?>/triggers/<?php echo $outArrClientTriggerView[0]['id']; ?>/edit/'" type="button" class="btn btn-success">Edit</button></div>
                                <p>
                                <div class="prf-box">
								   <div class=" wk-progress pf-status">
                                        <div class="col-md-8 col-xs-8"><b>Title</b></div>
                                            <div class="col-md-4 col-xs-4">
                                        <strong><?php echo $outArrClientTriggerView[0]['title']; ?></strong>
                                            </div>
                                    </div>	
                                   <div class=" wk-progress pf-status">
                                        <div class="col-md-8 col-xs-8"><b>Instruction&nbsp;</b></div>
                                        <div class="col-md-4 col-xs-4">
                                            <strong><?php echo $outArrClientTriggerView[0]['instruction']; ?></strong>
                                        </div>
                                    </div>	
						            <div class=" wk-progress pf-status">
                                        <div class="col-md-8 col-xs-8"><b>Height&nbsp;</b></div>
                                        <div class="col-md-4 col-xs-4">
                                            <strong><?php echo $outArrClientTriggerView[0]['height']; ?></strong>
                                        </div>
                                    </div>	
									<div class=" wk-progress pf-status">
											<div class="col-md-8 col-xs-8"><b>width&nbsp;</b></div>
											<div class="col-md-4 col-xs-4">
										<strong><?php echo $outArrClientTriggerView[0]['width']; ?></strong>
											</div>
									</div>																				
									<div class=" wk-progress pf-status">
									    <div class="col-md-8 col-xs-8"><b>Status&nbsp;</b></div>
										<div class="col-md-4 col-xs-4">
										    <strong><?php echo $dispClientStatus; ?></strong>
											    </div>
									</div>																				
									<div class=" wk-progress pf-status">
									    <div class="col-md-8 col-xs-8"><b>Client Verticals&nbsp;</b></div>
										<div class="col-md-4 col-xs-4">
                                     <strong><?php echo $outArrAllVerticalClients[0]['client_vertical_name']; ?></strong>
                                        </div>
                                    </div>																				
						   	   	   	   
						        </div>
                                </p>
                           </div>
                       </div>
                    </div>
                </section>
		 </div>
    </div>
   </section>
</section>
		   
						   
						   
						   
						   
						   
						   
						   
						   
						   
						   