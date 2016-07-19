<!--main content start-->
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
                            <a href="<?php echo $config['LIVE_URL'];?>clients/">Client List</a>
                        </li>
                        <li>
                            <a class="current" href="#">Client Details</a>
                        </li>
                        
                    </ul>
                </div>
</div>   
<div style="clear: both;"></div>

<br>
        <div class="row">
            <div class="col-md-12">
                <section class="panel">
                  <header class="panel-heading">
                        Client Details
                        <span class="tools pull-right">
                            <a href="javascript:;" class="fa fa-chevron-down"></a>
                            <a href="javascript:;" class="fa fa-cog"></a>
                            <a href="javascript:;" class="fa fa-times"></a>
                         </span>
                    </header>
                    <div class="panel-body profile-information">
                       <div class="col-md-3">
                           <div class="profile-pic text-center">
						   
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
			   <input type="hidden" name="c_id" id="c_id" value="<?php echo $cid;?>" />
	   <fieldset class="grey-bg required" style="height:40px;">
			  <table width="500" border="0">
			  <tr><td>
			   <p style="float:left;">
			  <img id="img_prev" src="<?php echo $dispLogo;?>" alt="client logo" width="250px" height="250px">
			  </p>
			  </td></tr>
			<tr><td><p style="float:left;padding:12px;"><button onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $cid; ?>/edit/'" type="button" class="btn btn-warning">Modify Logo</button></p></td><tr>
		</table>
		</fieldset>
			    
			     </div>
                       </div>
			   <div class="col-md-9">
                           <div class="profile-desk">
                               <h1><?php echo isset($outArrClient[0]['name']) ? $outArrClient[0]['name'] : '';?></h1>
                               <!-- <span class="text-muted">Product Manager</span> -->
		<input type="hidden" name="c_id" id="c_id" value="<?php echo $cid;?>" />
         <input type="hidden" name="c_exist_logo" id="c_exist_logo" value="<?php echo $dispLogo; ?>" />
		 
		<!-- <fieldset class="grey-bg required" style="height:40px;">
			<p style="float:right;padding:12px;"><button onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $cid; ?>/edit/'" type="button" class="btn btn-warning">Modify Logo</button></p>
		</fieldset> -->
		      <!-- <div class="colx2-left">
				<h3 class="prf-border-head">Background Image</h3>
						<img src="<?php echo $dispBackgroundImage; ?>" alt="client background image" width="200px" height="100px"/>
				</div>-->
				  			<div style="float:right;"><button onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $cid; ?>/edit/'" type="button" class="btn btn-info">Edit</button></div>

	                           <p>
                                   <div class="prf-box">
								   <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Background Image</div>
                                                <div class="col-md-4 col-xs-4">
                                                   <strong><img src="<?php echo $dispBackgroundImage; ?>" alt="client background image" width="150px" height="100px"/></strong>
                                                </div>
                                             </div>
                                            <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Client Name&nbsp;</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php echo isset($outArrClient[0]['name']) ? $outArrClient[0]['name'] : 0;?></strong>
                                                </div>
                                            </div>
                                            <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Client Prefix&nbsp;</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php echo isset($outArrClient[0]['prefix']) ? $outArrClient[0]['prefix'] : 0;?></strong>
                                                </div>
                                            </div>
                                            <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Website&nbsp;</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><a href="<?php echo $outArrClient[0]['url']; ?>" target="_blank"><?php echo $outArrClient[0]['url']; ?></a></strong>
                                                </div>
                                            </div>
							<!--  <div class="form-group">
                        <label class="col-sm-3 control-label">Background Color&nbsp;</label>
                        <div class="col-sm-6">
						<div data-color-format="rgb" data-color="rgb(255, 146, 180)" class="input-append colorpicker-default color">
                          <input id="bcid" class="form-control" type="text" value="<?php echo $outArrClientEditDetails[0]['background_color']; ?>" />
						  <input type="hidden" id="hdn_bcid" name="hdn_bcid" value="<?php echo $outArrClientEditDetails[0]['background_color']; ?>" />
						 <span class=" input-group-btn add-on">
                                                  <button class="btn btn-white" type="button" style="padding: 8px">
                                                      <i style="background-color:<?php  echo hex2rgb($outArrClientEditDetails[0]['background_color']); ?>"></i>
                                                  </button>
                                              </span>
                                    </div>
                        </div>
                      </div>-->
					  
					  
					  				
           <?php	
   function hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = "rgb($r, $g, $b)";
   //return implode(",", $rgb); // returns the rgb values separated by commas
   return $rgb; // returns an array with the rgb values
}
        ?>	
											   
								    <div class=" wk-progress pf-status">
                                <label class="col-md-8 col-xs-8">Background Color&nbsp;</label> 
                                                <div class="col-md-4 col-xs-4">
						             <div data-color-format="rgb" data-color="<?php  echo hex2rgb($outArrClient[0]['background_color']); ?>" class="input-append colorpicker-default color">
                                   <input id="bcid" name="bcid" class="form-control"  type="text" value="<?php echo $outArrClient[0]['background_color']; ?>" />
						            <input type="hidden" id="hdn_bcid" name="hdn_bcid" value="" />    
						                      <span class=" input-group-btn add-on">
                                                  <button class="btn btn-white" type="button" style="padding: 8px">
                                                      <i style="background-color: <?php  echo hex2rgb($outArrClient[0]['background_color']); ?>"></i>
                                                  </button>
                                              </span>
                                    </div>
                                </div>
                           </div>
                                             		  <div class=" wk-progress pf-status">
                                <label class="col-md-8 col-xs-8">Light Color&nbsp;</label> 
                                                <div class="col-md-4 col-xs-4">
						             <div data-color-format="rgb" data-color="<?php  echo hex2rgb($outArrClient[0]['light_color']); ?>" class="input-append colorpicker-default color">
                                  <input id="lcid"  name="lcid" class="form-control" type="text" value="<?php echo $outArrClient[0]['light_color']; ?>">
													<input type="hidden" id="hdn_lcid" name="hdn_lcid" value="" /> 
						                      <span class=" input-group-btn add-on">
                                                  <button class="btn btn-white" type="button" style="padding: 8px">
                                                      <i style="background-color: <?php  echo hex2rgb($outArrClient[0]['light_color']); ?>"></i>
                                                  </button>
                                              </span>
                                    </div>
                                </div>
                           </div>
						   
						         		  <div class=" wk-progress pf-status">
                                <label class="col-md-8 col-xs-8">Dark Color&nbsp;</label> 
                                                <div class="col-md-4 col-xs-4">
						             <div data-color-format="rgb" data-color=" <?php  echo hex2rgb($outArrClient[0]['dark_color']); ?>" class="input-append colorpicker-default color">
                                  <input id="dcid" name="dcid"  class="form-control" type="text" value="<?php echo $outArrClient[0]['dark_color']; ?>">
									<input type="hidden" id="hdn_dcid" name="hdn_dcid" value="" />
						                      <span class=" input-group-btn add-on">
                                                  <button class="btn btn-white" type="button" style="padding: 8px">
                                                      <i style="background-color: <?php  echo hex2rgb($outArrClient[0]['dark_color']); ?>"></i>
                                                  </button>
                                              </span>
                                    </div>
                                </div>
                           </div>       
                                            <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Active&nbsp;</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php echo $dispClientStatus;?></strong>
                                                </div>
                                            </div>
                                            <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Is Demo&nbsp;</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php if(isset($outArrClient[0]['is_demo']) && $outArrClient[0]['is_demo']==1){echo "YES";}else{echo "NO";}?></strong>
                                                </div>
                                            </div>
											 <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Client Vertical&nbsp;</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php echo isset($outArrVerticalClients[0]['client_vertical_name']) ? $outArrVerticalClients[0]['client_vertical_name'] :'None'; ?></strong>
                                                </div>
                                            </div>
											 <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8">Store Notify message&nbsp;</div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php echo isset($outArrClient[0]['store_notify_msg']) ? $outArrClient[0]['store_notify_msg'] :'None'; ?></strong>
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

			  
			  
			  
			  