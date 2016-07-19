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
						<a href="#">Press List</a>
					</li>
				</ul>
			</div>
			
			<div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-user"></i> Press Releases</h2>
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
								  <th>Url</th>
								  <th>Publication</th>
								  <th>Date</th>
								  <th>Actions</th>
							  </tr>
						  </thead>   
						  <tbody>
							<?php for($i=0;$i<count($outArray);$i++) {?>
							<tr>
								<td><?php echo $outArray[$i]['title'];?></td>
								<td class="center"><?php echo $outArray[$i]['url'];?></td>
								<td class="center"><?php echo $outArray[$i]['publication'];?></td>
								<td class="center">
									<span class="label label-success"><?php echo $outArray[$i]['created_date'];?></span>
								</td>
								<td class="center">
									
									<a class="btn btn-info" href="<?php echo $config['LIVE_ADMIN_URL']?>press/edit/<?php echo $outArray[$i]['id'];?>">
										<i class="icon-edit icon-white"></i>  
										Edit                                            
									</a>
									<a class="btn btn-danger" href="javascript:void(0);" onclick="return deletePressRelease('<?php echo $outArray[$i]['id'];?>');">
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