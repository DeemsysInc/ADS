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
                            <a class="current" href="#">Downloads list</a>
                        </li>
                        
                    </ul>
                </div>
</div>
<div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <div class="panel-body">
                        <form class="form-inline"  method="post"  action="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/downloads">
                           
                            <div class="form-group">
                                <label class="col-lg-2 col-sm-2 control-label">From </label>
                                <div class="col-lg-2">
                                  <input type="text" class="form-control" id="from" name="from" value="<?php echo $start_date;?>">
                                </div>
                            </div>
                        
                            <div class="form-group">
                                <label class="col-lg-2 col-sm-2 control-label" value="">To </label>
                                <div class="col-lg-2">
                                  <input type="text" class="form-control" id="to" name="to" value="<?php echo $end_date;?>">
                                </div>
                            </div>
                            <input type="submit" class="btn btn-success" value="Apply">
                        
                        </form>
                    </div>
                </section>
            </div>
        </div>

<div style="clear: both;"></div>




<br>
       <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        App Downloads Info
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
                        <th>Device Type</th>
                        <th>Device Os</th>
                        <th>Device Os Version</th>
                        <th>Device Brand</th>
                        <th>Build Number</th>
                   
                    </tr>
                    </thead>
                    <tbody>
                        <?php 
						for($i=0;$i<count($arrData)-1;$i++){?>

                <tr class="gradeX" style="cursor:pointer;" >
                    <td><?php echo isset($arrData[$i]['device_type']) ? $arrData[$i]['device_type'] : ''; ?></td>
                    <td><?php echo isset($arrData[$i]['device_os']) ? $arrData[$i]['device_os'] : 0; ?></td>
                    <td><?php echo isset($arrData[$i]['device_os_version']) ? $arrData[$i]['device_os_version'] : 0; ?></td>
                    <td><?php echo isset($arrData[$i]['device_brand']) ? $arrData[$i]['device_brand'] : 0; ?></td>
                    <td><?php echo isset($arrData[$i]['build_number']) ? $arrData[$i]['build_number'] : 0; ?></td>
                   
               </tr>
               <?php }?>
                    </tbody>
                    <tfoot>
                     <tr>
                        <th>Device Type</th>
                        <th>Device Os</th>
                        <th>Device Os Version</th>
                        <th>Device Brand</th>
                        <th>Build Number</th>
                   
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
    <!--main content end-->