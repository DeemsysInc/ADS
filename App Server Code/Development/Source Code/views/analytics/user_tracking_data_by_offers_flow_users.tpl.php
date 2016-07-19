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
						<th>Add</th>
						<th>Remove</th>
						<th>Redeem</th>
						<th>Share</th>
						<th>My Offers</th>
                    </tr>
                    </thead>
                    <tbody>
                   <?php for($i=0;$i<count($arrData);$i++){?>
					<tr>
						<td><?php echo isset($arrData[$i]['user_username']) ? $arrData[$i]['user_username'] : ''; ?></td>
						<td><?php echo isset($arrData[$i]['user_email_id']) ? $arrData[$i]['user_email_id'] : ''; ?></td>
						<td><?php echo isset($arrData[$i]['addViews']) ? $arrData[$i]['addViews'] : 'N/A'; ?></td>
						<td><?php echo isset($arrData[$i]['removedViews']) ? $arrData[$i]['removedViews'] : 0; ?></td>
						<td><?php echo isset($arrData[$i]['redeemViews']) ? $arrData[$i]['redeemViews'] : 0; ?></td>
						<td><?php echo isset($arrData[$i]['shareViews']) ? $arrData[$i]['shareViews'] : 0; ?></td>
						<td><?php echo isset($arrData[$i]['myOfferViews']) ? $arrData[$i]['myOfferViews'] : 0; ?></td>
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

