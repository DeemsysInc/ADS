<!--main content start-->
    <section id="main-content">
        <section class="wrapper">
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
                            <a class="current" href="#">Visual List</a>
                        </li>
                    </ul>
                </div>
            </div>
    <div style="clear: both;"></div>
       <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Visual Info
                        <span class="tools pull-right">
                            <a href="javascript:;" class="fa fa-chevron-down"></a>
                            <a href="javascript:;" class="fa fa-cog"></a>
                            <a href="javascript:;" class="fa fa-times"></a>
                         </span>
                    </header>
                    <div class="panel-body">
                    <div class="adv-table">
					<div align="right">
						<a href="<?php echo $config['LIVE_URL']; ?>dynamic_content/client_visual_addbutton.tpl.php?tid=<?php echo $tid; ?>&cid=<?php echo $cid; ?>" data-toggle="modal" data-target="#visual_addbutton<?php echo $tid; ?>" class="btn btn-success">Add Button</a>
						<!-- Modal HTML -->
						<div id="visual_addbutton<?php echo $tid; ?>" class="modal fade">
							<div class="modal-dialog">
								<div class="modal-content">
									<!-- Content will be loaded here from "remote.php" file -->
								</div>
							</div>
						</div>
						<a href="<?php echo $config['LIVE_URL']; ?>dynamic_content/client_visual_add3dmodel.tpl.php?tid=<?php echo $tid; ?>&cid=<?php echo $cid; ?>" data-toggle="modal" data-target="#visual_add3dmodel<?php echo $tid; ?>" class="btn btn-success">Add 3D Model</a>
						<!-- Modal HTML -->
						<div id="visual_add3dmodel<?php echo $tid; ?>" class="modal fade">
							<div class="modal-dialog">
								<div class="modal-content">
									<!-- Content will be loaded here from "remote.php" file -->
								</div>
							</div>
						</div>
						<a href="<?php echo $config['LIVE_URL']; ?>dynamic_content/client_visual_addvideo.tpl.php?tid=<?php echo $tid; ?>&cid=<?php echo $cid; ?>" data-toggle="modal" data-target="#visual_addvideo<?php echo $tid; ?>" class="btn btn-success">Add Video</a>
						<!-- Modal HTML -->
						<div id="visual_addvideo<?php echo $tid; ?>" class="modal fade">
							<div class="modal-dialog">
								<div class="modal-content">
									<!-- Content will be loaded here from "remote.php" file -->
								</div>
							</div>
						</div>
						<a href="<?php echo $config['LIVE_URL']; ?>dynamic_content/client_visual_add_audio.tpl.php?tid=<?php echo $tid; ?>&cid=<?php echo $cid; ?>" data-toggle="modal" data-target="#visual_add_audio<?php echo $tid; ?>" class="btn btn-success">Add Audio</a>
						<!-- Modal HTML -->
						<div id="visual_add_audio<?php echo $tid; ?>" class="modal fade">
							<div class="modal-dialog">
								<div class="modal-content">
									<!-- Content will be loaded here from "remote.php" file -->
								</div>
							</div>
						</div>
						<a href="<?php echo $config['LIVE_URL']; ?>dynamic_content/client_visual_addurl.tpl.php?tid=<?php echo $tid; ?>&cid=<?php echo $cid; ?>" data-toggle="modal" data-target="#visual_addurl<?php echo $tid; ?>" class="btn btn-success">Add Url</a>
						<!-- Modal HTML -->
						<div id="visual_addurl<?php echo $tid; ?>" class="modal fade">
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
                        <th>X</th>
                        <th>Y</th>
                        <th>Scale</th>
                        <th class="hidden-phone">Type</th>
                        <th class="hidden-phone">Url</th>
                        <th>In 3D</th>
                        <th>Ignore Tracking</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
					<?php for($i=0;$i<count($outArrClientTriggerVisuals);$i++) { ?>
				    <tr>
					<td><?php echo $outArrClientTriggerVisuals[$i]['x']; ?></td>
					<td><?php echo $outArrClientTriggerVisuals[$i]['y']; ?></td>
					<td><?php echo $outArrClientTriggerVisuals[$i]['scale']; ?></td>
					<td><?php echo $outArrClientTriggerVisuals[$i]['discriminator']; ?></td>
                    <?php if(pathinfo($outArrClientTriggerVisuals[$i]['url'], PATHINFO_EXTENSION)=='mp4'){?>
                    <td>
                    <div align="center">
                    <video width="300" height="200" controls >
                    <source src="<?php echo str_replace("{client_id}",$cid,$config['files']['videos']).$outArrClientTriggerVisuals[$i]['url'];?>" type="video/mp4">
                    </video>
                   </div>
				   </td>
					<?php }else if(pathinfo($outArrClientTriggerVisuals[$i]['url'], PATHINFO_EXTENSION)=='mp3'){?>
					<td>
				<div align="center">
					<audio width="300" height="200" controls>
                    <source src="<?php echo str_replace("{client_id}",$cid,$config['files']['additional']).$outArrClientTriggerVisuals[$i]['url'];?>" type="audio/mpeg">
				    </audio>
				   </div>
                    </td>
					<?php } else{?>
                    <td><?php echo $outArrClientTriggerVisuals[$i]['url']; ?></td>
                    <?php }?> 
                    <td><?php echo $outArrClientTriggerVisuals[$i]['video_in_metaio']; ?></td>
                    <td><?php echo $outArrClientTriggerVisuals[$i]['ignore_tracking']; ?></td>
                   <td>
                   <?php if($outArrClientTriggerVisuals[$i]['discriminator']=='VIDEO'){?>
				    <a href="<?php echo $config['LIVE_URL']; ?>dynamic_content/client_visual_addvideo.tpl.php?tid=<?php echo $tid; ?>&cid=<?php echo $cid; ?>&visual_id=<?php echo $outArrClientTriggerVisuals[$i]['id'];?>" data-toggle="modal" data-target="#visual_editvideo<?php echo $tid; ?>" class="btn btn-warning cancel">Edit</a>
					<!-- Modal HTML -->
					<div id="visual_editvideo<?php echo $tid; ?>" class="modal fade">
						<div class="modal-dialog">
							<div class="modal-content">
								<!-- Content will be loaded here from "remote.php" file -->
							</div>
						</div>
					</div>
                   <?php }else if($outArrClientTriggerVisuals[$i]['discriminator']=='BUTTON'){?>
				   <a href="<?php echo $config['LIVE_URL']; ?>dynamic_content/client_visual_addbutton.tpl.php?tid=<?php echo $tid; ?>&cid=<?php echo $cid; ?>&visual_id=<?php echo $outArrClientTriggerVisuals[$i]['id'];?>" data-toggle="modal" data-target="#visual_editbutton<?php echo $tid; ?>" class="btn btn-warning cancel">Edit</a>
					<!-- Modal HTML -->
					<div id="visual_editbutton<?php echo $tid; ?>" class="modal fade">
						<div class="modal-dialog">
							<div class="modal-content">
								<!-- Content will be loaded here from "remote.php" file -->
							</div>
						</div>
					</div>
				   <?php }else if($outArrClientTriggerVisuals[$i]['discriminator']=='URL'){?>
				   <a href="<?php echo $config['LIVE_URL']; ?>dynamic_content/client_visual_addurl.tpl.php?tid=<?php echo $tid; ?>&cid=<?php echo $cid; ?>&visual_id=<?php echo $outArrClientTriggerVisuals[$i]['id'];?>" data-toggle="modal" data-target="#visual_editurl<?php echo $tid; ?>" class="btn btn-warning cancel">Edit</a>
					<!-- Modal HTML -->
					<div id="visual_editurl<?php echo $tid; ?>" class="modal fade">
						<div class="modal-dialog">
							<div class="modal-content">
								<!-- Content will be loaded here from "remote.php" file -->
							</div>
						</div>
					</div>
                   <?php }else if($outArrClientTriggerVisuals[$i]['discriminator']=='AUDIO'){?>
				   <a href="<?php echo $config['LIVE_URL']; ?>dynamic_content/client_visual_add_audio.tpl.php?tid=<?php echo $tid; ?>&cid=<?php echo $cid; ?>&visual_id=<?php echo $outArrClientTriggerVisuals[$i]['id'];?>" data-toggle="modal" data-target="#visual_editaudio<?php echo $tid; ?>" class="btn btn-warning cancel">Edit</a>
					<!-- Modal HTML -->
					<div id="visual_editaudio<?php echo $tid; ?>" class="modal fade">
						<div class="modal-dialog">
							<div class="modal-content">
								<!-- Content will be loaded here from "remote.php" file -->
							</div>
						</div>
					</div>
		           <?php }else if($outArrClientTriggerVisuals[$i]['discriminator']=='3DMODEL'){?>
                   <a href="<?php echo $config['LIVE_URL']; ?>dynamic_content/client_visual_add3dmodel.tpl.php?tid=<?php echo $tid; ?>&cid=<?php echo $cid; ?>&visual_id=<?php echo $outArrClientTriggerVisuals[$i]['id'];?>" data-toggle="modal" data-target="#visual_editmodel<?php echo $tid; ?>" class="btn btn-warning cancel">Edit</a>
					<!-- Modal HTML -->
					<div id="visual_editmodel<?php echo $tid; ?>" class="modal fade">
						<div class="modal-dialog">
							<div class="modal-content">
								<!-- Content will be loaded here from "remote.php" file -->
							</div>
						</div>
					</div>
				   <button type="button" class="btn btn-success" onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $cid; ?>/triggers/<?php echo $tid; ?>/visuals/<?php echo $outArrClientTriggerVisuals[$i]['id'];?>/models/'">View</button>
                   <?php }?>
                   <button type="button"  class="btn btn-danger delete"  onclick="deleteTriggerVisual(<?php echo $outArrClientTriggerVisuals[$i]['id'];?>,<?php echo $cid;?>,<?php echo $tid;?>);">Delete</button>
                   </td>
				   </tr>
				<?php } ?>
			</tbody>
                <tfoot>
                    <tr>
                      <th>X</th>
                       <th>Y</th>
                        <th>Scale</th>
                        <th class="hidden-phone">Type</th>
                        <th class="hidden-phone">Url</th>
                        <th>In 3D</th>
                        <th>Ignore Tracking</th>
                        <th>Action</th>
                     </tr>
                </tfoot>
                </table>
            </div>
                </div>
                </section>
            </div>
        </div>
        <div id="tmp_holder"></div>
   <div class="clear"></div>
        <!-- page end-->
        </section>
    </section>