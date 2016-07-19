
			
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
						<a href="#">All users</a>
					</li>
				</ul>
			</div>
			<!--table start-->
			<div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-user"></i> Users</h2>
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
								  <th>Username</th>
								  <th>E-mail</th>
								  <th>Phone</th>
								  <th>User Type</th>
								  <th>Actions</th>
							  </tr>
						  </thead>   
						  <tbody>
							<?php for($i=0;$i<count($outArray);$i++){?>
							<tr>
								<td><?php echo isset($outArray[$i]['user_name']) ? $outArray[$i]['user_name'] : '';?></td>
								<td class="center"><?php echo isset($outArray[$i]['email_address']) ? $outArray[$i]['email_address'] : '';?></td>
								<td class="center"><?php echo isset($outArray[$i]['phone']) ? $outArray[$i]['phone'] : '---';?></td>
								<td class="center">
									<span class="label label-success"><?php echo $outArray[$i]['group_name'];?></span>
								</td>
								<td class="center">
									
									<?php if($_SESSION['admin_group_id']=="1"){?>
									<a class="btn btn-info" href="<?php echo $config['LIVE_ADMIN_URL']?>users/edit/<?php echo $outArray[$i]['user_id'];?>">
										<i class="icon-edit icon-white"></i>  
										Edit                                            
									</a>
									<?php }else if($_SESSION['admin_group_id']=="2"){?>
									<?php if($outArray[$i]['group_id']!="1"){?>
									<a class="btn btn-info" href="<?php echo $config['LIVE_ADMIN_URL']?>users/edit/<?php echo $outArray[$i]['user_id'];?>">
										<i class="icon-edit icon-white"></i>  
										Edit                                            
									</a>
									<?php }}?>
									<a class="btn btn-danger" href="#">
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
			<!--table ends-->
			
			
			


    
					<!-- content ends -->
			</div><!--/#content.span10-->
				</div><!--/fluid-row-->
				
		
