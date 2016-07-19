<div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        User Info
                        <span class="tools pull-right">
                            <a href="javascript:;" class="fa fa-chevron-down"></a>
                            <a href="javascript:;" class="fa fa-cog"></a>
                            <a href="javascript:;" class="fa fa-times"></a>
                         </span>
                    </header>
                    <div class="panel-body">
                    <div class="adv-table">
                    <?php if(count($arrData)>0){?>
		
                    <table  class="display table table-bordered table-striped" id="dynamic-table">
                    <thead>
                    <tr>
                        <th>UserName</th>
					    <th>Email Id</th>
						<th>Cart</th>
						<th>share</th>
						<th>Closet</th>
						<th>Wishlist</th>
						<th>Product views</th>
                    </tr>
                    </thead>
                    <tbody>
                   <?php for($i=0;$i<count($arrData);$i++){?>
				  <?php 
						$username=isset($arrData[$i]['user_username']) ? $arrData[$i]['user_username'] : '';
						$emailId=isset($arrData[$i]['user_email_id']) ? $arrData[$i]['user_email_id'] : '';
						
						if($username=='' && $emailId=='')
						{
						  $username='Ananymous';
						  $emailId='Ananymous';
						  
						}
						else if($username=='' && $emailId!='')
						{
						  $username=$emailId;
						  $emailId=isset($arrData[$i]['user_email_id']) ? $arrData[$i]['user_email_id'] : '';
						
						}
						?>
					<tr>
						<td><?php echo $username; ?></td>
						<td><?php echo $emailId; ?></td>
						<td><?php echo isset($arrData[$i]['cartViews']) ? $arrData[$i]['cartViews'] : ''; ?></td>
						<td><?php echo isset($arrData[$i]['shareViews']) ? $arrData[$i]['shareViews'] : ''; ?></td>
						<td><?php echo isset($arrData[$i]['closetViews']) ? $arrData[$i]['closetViews'] : ''; ?></td>
						<td><?php echo isset($arrData[$i]['wishlistViews']) ? $arrData[$i]['wishlistViews'] : ''; ?></td>
						<td><?php echo isset($arrData[$i]['scannedViews']) ? $arrData[$i]['scannedViews'] : ''; ?></td>
					</tr>
				    <?php }?>
                    </tbody>
                   
                    </table>
                    <?php }else { ?>
				<h3>No results found.</h3>

				<?php }?>
                    </div>
                    </div>
                </section>
            </div>

