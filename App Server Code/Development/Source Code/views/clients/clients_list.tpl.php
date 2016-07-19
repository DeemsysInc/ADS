 <!--main content start-->
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
                            <a class="current" href="#">Client List</a>
                        </li>
                        
                    </ul>
                </div>
</div>

<div style="clear: both;"></div>
       <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Client Info
                        <span class="tools pull-right">
                            <a href="javascript:;" class="fa fa-chevron-down"></a>
                            <a href="javascript:;" class="fa fa-cog"></a>
                            <a href="javascript:;" class="fa fa-times"></a>
                         </span>
                    </header>
                    <div class="panel-body">
                    <div class="adv-table">
				 <div align="right">
		   <a href="<?php echo $config['LIVE_URL']; ?>clients/add/" class="btn btn-success"> Add Client </a>&nbsp;&nbsp; 
	               </div>
                    <table  class="display table table-bordered table-striped" id="dynamic-table">
                    <thead>
                    <tr>
                        <th>Logo</th>
                        <th>Name</th>
                        <th>Prefix</th>
                        <th class="hidden-phone">Website</th>
                        <th class="hidden-phone">Status</th>
                        <th>Action</th>
                    


                    </tr>
                    </thead>
                    <tbody>
                   <?php for($i=0;$i<count($outArrAllClients);$i++) { 
					if(isset($outArrAllClients[$i]['active']) && $outArrAllClients[$i]['active']!=2)
					{
						if($outArrAllClients[$i]['logo']!=""){
							 $filepath=str_replace("{client_id}",$outArrAllClients[$i]['id'],$config['files']['logo']).$outArrAllClients[$i]['logo'];
							 $dispClient='<img src="'.$filepath.'" height="40"/>';
						}else{
							$dispClient='<p style="font-size: 28px;text-align: center;">'.$outArrAllClients[$i]['name'].'</p>';
						}
						if ($outArrAllClients[$i]['active']=="1"){
							$dispClientStatus = "Active";
						}else{
							$dispClientStatus = "Not Active";
						}
				?>
					<tr>
					<!--<td class="th table-check-cell"><input type="checkbox" name="selected[]" id="table-selected-1" value="1"></td>-->
					<td style="background:#CCC;border-bottom: 1px solid #fff;"><a href="<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrAllClients[$i]['id']; ?>/"><?php echo $dispClient; ?></a></td>
					<td><?php echo $outArrAllClients[$i]['name']; ?></td>
					<td><?php echo $outArrAllClients[$i]['prefix']; ?></td>
					<td><a href="<?php echo $outArrAllClients[$i]['url']; ?>" target="_blank"><?php echo $outArrAllClients[$i]['url']; ?></a></td>
					<td><?php echo $dispClientStatus; ?></td>
					<td>
                    <a href="<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrAllClients[$i]['id']; ?>/"  class="btn btn-success">View</a>&nbsp;&nbsp;
                    <a href="<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrAllClients[$i]['id']; ?>/edit/"  class="btn btn-warning cancel">Edit</a>&nbsp;&nbsp;
                   
                    <?php /*?><button type="button" class="blue" onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrAllClients[$i]['id']; ?>/'">View</button>&nbsp;&nbsp;<button type="button" class="blue"  onclick="location.href='<?php echo $config['LIVE_URL']; ?>clients/id/<?php echo $outArrAllClients[$i]['id']; ?>/edit/'">Edit</button>&nbsp;&nbsp;<?php */?>
					<button type="button"  class="btn btn-danger delete"   onclick="deleteClient('<?php echo $outArrAllClients[$i]['id']; ?>');">Delete</button></td>
				</tr>
				<?php } }?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Logo</th>
                        <th>Name</th>
                        <th>Prefix</th>
                        <th class="hidden-phone">Website</th>
                        <th class="hidden-phone">Status</th>
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