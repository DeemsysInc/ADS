 <section id="main-content">
        <section class="wrapper">
           <!-- page start-->
<!-- <div align="right">
    <form  id="table_form" method="post" action="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/clients/<?php echo $client_id;?>/products/<?php echo $start_date;?>/<?php echo $end_date;?>">
    From:<input type="text" id="from" name="from" value="<?php echo $start_date;?>">to:<input type="text" id="to" name="to" value="<?php echo $end_date;?>">&nbsp;&nbsp;&nbsp;<input type="submit" value="Apply">
    </form>
</div> -->
<div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumbs-alt">
                        <li>
                            <a href="<?php echo $config['LIVE_URL'];?>">Dashboard</a>
                        </li>
                        <li>
                            <a class="current" href="#">Appusers List</a>
                        </li>
                        
                    </ul>
                </div>
</div>
<div style="clear: both;"></div>
       <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Appusers Info
                      <span class="tools pull-right">
                            <a href="javascript:;" class="fa fa-chevron-down"></a>
                            <a href="javascript:;" class="fa fa-cog"></a>
                            <a href="javascript:;" class="fa fa-times"></a>
                         </span>
                    </header>
                    <div class="panel-body">
                    <div class="adv-table">
                    <table  class="display table table-bordered table-striped" id="dynamic-table">
                    <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>User Name</th>
						 <th>Email Id</th>
						     </tr>
                    </thead>
                    <tbody>
					<?php for($i=0;$i<count($outArray);$i++) { ?>
						
					<tr>
					<!--<td class="th table-check-cell"><input type="checkbox" name="selected[]" id="table-selected-1" value="1"></td>-->
					<td><?php echo isset($outArray[$i]['user_firstname']) ? $outArray[$i]['user_firstname'] :''; ?></td>
					<td><?php echo isset($outArray[$i]['user_lastname']) ? $outArray[$i]['user_lastname'] : ''; ?></td>
					<td><?php echo isset($outArray[$i]['user_username']) ? $outArray[$i]['user_username'] : ''; ?></td>
                    <td><?php echo isset($outArray[$i]['user_email_id']) ? $outArray[$i]['user_email_id'] : ''; ?></td>
                   <?php /*?>  <td><button type="button" class="blue" onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArray[$i]['id']; ?>/'">View</button>&nbsp;&nbsp;<button type="button" class="blue"  onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArray[$i]['id']; ?>/edit/'">Edit</button></td><?php */?>
                    
               
				</tr>
				<?php } ?>
			</tbody>
		  <tfoot>
                    <tr>
                          <th>First Name</th>
                        <th>Last Name</th>
                        <th>User Name</th>
						 <th>Email Id</th>
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
						
						
						
						
						
						
						
						
						
						
						