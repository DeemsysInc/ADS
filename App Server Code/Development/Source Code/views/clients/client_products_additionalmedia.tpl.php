 <section id="main-content">
        <section class="wrapper">
		<div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumbs-alt">
                        <li>
                            <a href="<?php echo $config['LIVE_URL'];?>">Dashboard</a>
                        </li>
						 <li>
                            <a href="<?php echo $config['LIVE_URL'];?>clients/id/<?php echo $cid; ?>/products/">Product List</a>
                        </li>
                        <li>
                            <a class="current" href="#">Additional Media List</a>
                        </li>
                    </ul> 
                </div> 
        </div> 
	<div style="clear: both;"></div> 
       <div class="row"> 
            <div class="col-sm-12"> 
                <section class="panel"> 
                    <header class="panel-heading"> 
                        Additional Media Info 
                        <span class="tools pull-right">
                            <a href="javascript:;" class="fa fa-chevron-down"></a>
                            <a href="javascript:;" class="fa fa-cog"></a>
                            <a href="javascript:;" class="fa fa-times"></a>
                         </span>
                    </header>
            <div class="panel-body">
                <div class="adv-table">
					 <div align="right">
						<a href="<?php echo $config['LIVE_URL']; ?>dynamic_content/client_products_add_additional_media.tpl.php?pid=<?php echo $pid;?>&cid=<?php echo $cid;?>" class="btn btn-lg btn-primary" data-toggle="modal" data-target="#add_additional_media<?php echo $pid;?>" class="btn btn-success">Add Additional Media</a>
						<!-- Modal HTML -->
						<div id="add_additional_media<?php echo $pid;?>" class="modal fade">
							<div class="modal-dialog">
								<div class="modal-content">
									<!-- Content will be loaded here from "remote.php" file -->
								</div>
							</div>
						</div>
			         </div>
                <table  class="display table table-bordered table-striped" id="dynamic-table">
                    <thead>
                    <tr>
            <div align="center" style="display:none" id="loadingmessage">
               <img src="<?php echo $config['LIVE_URL']; ?>views/images/loading-cafe.gif">
             </div>
			<div id="frm_error"></div>
                    <th>Image</th>
                    <th>Action</th>
                    </tr>
                    </thead>
                <tbody>
					<?php for($i=0;$i<count($outArrClientProductAdditional);$i++) { 
						if($outArrClientProductAdditional[$i]['media']!=""){						
							$dispProductImage=str_replace("{client_id}",$cid,$config['files']['additional']).$outArrClientProductAdditional[$i]['media'];
						}else{
							$dispProductImage=$config['LIVE_URL']."views/images/no-product.png";
						}
			     	?>
					 <input type="hidden" value="<?php echo $cid;?>" name="cid" id="cid">
                    <input type="hidden" value="<?php echo $pid;?>" name="pid" id="pid">
						<tr>
					        <td>
							<?php if(pathinfo($dispProductImage, PATHINFO_EXTENSION)=='mp4'){?>
							<div>
								<video width="300" height="200" controls >
								<source src="<?php echo $dispProductImage;?>" type="video/mp4">
								</video>
							</div>
							<?php }else{?>
							<img src="<?php echo $dispProductImage; ?>" width="300" height="200"/>
                               <?php }?>
                            </td>
							<td>
							<a href="<?php echo $config['LIVE_URL']; ?>dynamic_content/client_products_edit_additional_media.tpl.php?pid=<?php echo $pid;?>&cid=<?php echo $cid;?>&additional_id=<?php echo $outArrClientProductAdditional[$i]['id'];?>" rel="facebox[.bolder]" class="btn btn-warning cancel" >
							 Edit</a><br /><br />
							<button type="button" class="btn btn-danger delete"  onclick="deleteAdditionalMedia(<?php echo $cid;?>,<?php echo $pid;?>,<?php echo $outArrClientProductAdditional[$i]['id'];?>)">Delete</button><br /><br /></td>
						</tr>
				<?php } ?>
			    </tbody>
			       <tfoot>
                    <tr>
                    <th>Image</th>
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
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					