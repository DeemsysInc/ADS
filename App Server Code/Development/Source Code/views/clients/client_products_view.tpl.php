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
                            <a href="<?php echo $config['LIVE_URL'];?>clients/id/<?php echo $cid; ?>/products/">Products List</a>
                        </li>
                        <li>
                            <a class="current" href="#">Products Details</a>
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
                        Products Details
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
			
			if (isset($outArrClientProductView[0]['pd_image']) && $outArrClientProductView[0]['pd_image']!=""){
				
				$productImage = str_replace("{client_id}",$outArrClientProductView[0]['client_id'],$config['files']['products']).$outArrClientProductView[0]['pd_image']; 
			}else{
				$productImage = $config['LIVE_URL']."views/images/no-product.png";
			}
			if (isset($outArrClientProductView[0]['pd_status']) && $outArrClientProductView[0]['pd_status']=="1"){
				$dispClientStatus = "YES";
			}else{
				$dispClientStatus = "NO";
			}
		?>
		 <input type="hidden" name="c_id" id="c_id" value="<?php echo $cid;?>" />
          <fieldset class="grey-bg required" style="height:40px;">
			  <table width="500" border="0">
			   <tr><td><p style="float:left;padding:12px;">Product Image</p> </td></tr>
			   <tr><td> <p style="float:left;">
			  <img src="<?php echo $productImage; ?>" alt="client background image"  width="150" height="100"/>
			  </p>
			  </td></tr>
			 
		</table>
		</fieldset>
			    
			     </div>
                       </div>
			   <div class="col-md-9">
                           <div class="profile-desk">
                               <!-- <span class="text-muted">Product Manager</span> -->
		<input type="hidden" name="c_id" id="c_id" value="<?php echo $cid;?>" />
		 <div style="float:right;"><button onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrClientProductView[0]['client_id']; ?>/products/<?php echo $outArrClientProductView[0]['pd_id']; ?>/edit/'" type="button" class="btn btn-info">Edit</button></div>

	                           <p>
                                   <div class="prf-box">
								   <div class=" wk-progress pf-status">
                                             <div class="col-md-8 col-xs-8"><b>Title</b></div>
                                                <div class="col-md-4 col-xs-4">
                                 <strong><?php echo isset($outArrClientProductView[0]['pd_name']) ? $outArrClientProductView[0]['pd_name'] : ''; ?></strong>
                                                </div>
                                             </div>
											  <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8"><b>Barcode&nbsp;</b></div>
                                                <div class="col-md-4 col-xs-4">
                                           <strong><?php echo isset($outArrClientProductView[0]['pd_barcode']) ? $outArrClientProductView[0]['pd_barcode'] :''; ?></strong>
                                                </div>
                                            </div>

										  <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8"><b>Active&nbsp;</b></div>
                                                <div class="col-md-4 col-xs-4">
                                              <strong><?php echo $dispClientStatus;?></strong>
                                                </div>
                                            </div>	
                                            <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8"><b>Description&nbsp;</b></div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php echo isset($outArrClientProductView[0]['pd_description']) ? $outArrClientProductView[0]['pd_description'] : ''; ?></strong>
                                                </div>
                                            </div>
                                       
                         											
		                       <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8"><b>Price&nbsp;</b></div>
                                                <div class="col-md-4 col-xs-4">
                                          <strong><?php echo isset($outArrClientProductView[0]['pd_price']) ? $outArrClientProductView[0]['pd_price'] : ''; ?></strong>
                                                </div>
                                            </div>
								 <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8"><b>Short Description&nbsp;</b></div>
                                                <div class="col-md-4 col-xs-4">
                                 <strong><?php echo isset($outArrClientProductView[0]['pd_short_description']) ? $outArrClientProductView[0]['pd_short_description'] : ''; ?></strong>
                                                </div>
                                            </div>	
                           <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8"><b>Url&nbsp;</b></div>
                                                <div class="col-md-4 col-xs-4">
             <strong> <a href="<?php echo isset($outArrClientProductView[0]['pd_url']) ? $outArrClientProductView[0]['pd_url'] : ''; ?>" target="_blank"><?php echo isset($outArrClientProductView[0]['pd_url']) ? $outArrClientProductView[0]['pd_url'] : ''; ?></a></strong>
                                                </div>
                                            </div>	
											<div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8"><b>Button Name&nbsp;</b></div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php echo isset($outArrClientProductView[0]['pd_button_name']) ? $outArrClientProductView[0]['pd_button_name'] : ''; ?></strong>
                                                </div>
                                            </div>
										         <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8"><b>Is try on?&nbsp;</b></div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php if(isset($outArrClientProductView[0]['pd_istryon']) && $outArrClientProductView[0]['pd_istryon']==1){echo "YES";}else{echo "NO";}?></strong>
                                                </div>
                                            </div>		  				
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
                                <label class="col-md-8 col-xs-8"><b>Background Color&nbsp;</b></label> 
                                                <div class="col-md-4 col-xs-4">
						             <div data-color-format="rgb" data-color="<?php  echo hex2rgb($outArrClientProductView[0]['pd_bg_color']); ?>" class="input-append colorpicker-default color">
                                   <input id="bcid" name="bcid" class="form-control"  type="text" value="<?php echo $outArrClientProductView[0]['pd_bg_color']; ?>" />
						            <input type="hidden" id="hdn_bcid" name="hdn_bcid" value="" />    
						                      <span class=" input-group-btn add-on">
                                                  <button class="btn btn-white" type="button" style="padding: 8px">
                                                      <i style="background-color: <?php  echo hex2rgb($outArrClientProductView[0]['pd_bg_color']); ?>"></i>
                                                  </button>
                                              </span>
                                    </div>
                                </div>
                           </div>	
						   
						           <div class=" wk-progress pf-status">
                                                <div class="col-md-8 col-xs-8"><b>Background&nbsp;</b></div>
                                                <div class="col-md-4 col-xs-4">
                                                    <strong><?php if(isset($outArrClientProductView[0]['pd_hide_bg_image']) && $outArrClientProductView[0]['pd_hide_bg_image']==1){echo "Show";}else{echo "Hide";}?></strong>
                                                </div>
                                            </div>		  				
											
                    	 <div class=" wk-progress pf-status">
                                         <div class="col-md-8 col-xs-8"><b>Category&nbsp;</b></div>
                                          <div class="col-md-4 col-xs-4">
                 <strong><?php echo isset($getCatagoriesById[0]['pd_category_name']) ? $getCatagoriesById[0]['pd_category_name']:''; ?></strong>
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

						   
						   
						   
						   
						   
						   