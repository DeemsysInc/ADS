<noscript>
				<div class="alert alert-block span10">
					<h4 class="alert-heading">Warning!</h4>
					<p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a> enabled to use this site.</p>
				</div>
			</noscript>
			
			<div id="content" class="span10">
			<!-- content starts -->
			

			<div>
				<ul class="breadcrumb">
					<li>
						<a href="<?php echo $config['LIVE_ADMIN_URL']?>">Home</a> <span class="divider">/</span>
					</li>
					<li>
						<a href="#">News List</a>
					</li>
				</ul>
			</div>
			
			<div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-user"></i> News</h2>
						<div class="box-icon">
							<a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a>
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<table class="table table-striped table-bordered bootstrap-datatable datatable">
						  <thead>
							  <tr>
								  <th>Title</th>
								  <th>Subtitle</th>
								  <th>Location</th>
								  <th>Date</th>
								  <th>Featured</th>
								  <th>Excerpt</th>
								  <th>Actions</th>
							  </tr>
						  </thead>   
						  <tbody>
							<?php for($i=0;$i<count($outArray);$i++) {?>
							<tr>
								<td><?php echo $outArray[$i]['title'];?></td>
								<td class="center"><?php echo $outArray[$i]['subtitle'];?></td>
								<td class="center"><?php echo $outArray[$i]['location'];?></td>
								<td class="center"><?php echo $outArray[$i]['created_date'];?></td>
								<td class="center"><?php if($outArray[$i]['featured']=="1"){echo "Yes";}else{echo "No";}?></td>
								<td class="center"><?php echo $outArray[$i]['excerpt'];?></td>
								
								
								<td class="center">
									
									<a class="btn btn-info" href="<?php echo $config['LIVE_ADMIN_URL']?>news/edit/<?php echo $outArray[$i]['id'];?>">
										<i class="icon-edit icon-white"></i>  
										Edit                                            
									</a>
									<a class="btn btn-danger" href="javascript:void(0);" onclick="return deleteNews('<?php echo $outArray[$i]['id'];?>');">
										<i class="icon-trash icon-white"></i> 
										Delete
									</a>
								</td>
							</tr>
							<?php }?>
							
							
						  </tbody>
					  </table>            
					</div>
				</div><!--/span-->
			
			</div><!--/row-->

					<!-- content ends -->
			</div><!--/#content.span10-->
				</div><!--/fluid-row-->