
 <section id="main-content">
  <section class="wrapper">
   <div class="row">
                 <div class="col-md-12">
                    <ul class="breadcrumbs-alt">
                        <li>
                            <a href="<?php echo $config['LIVE_URL'];?>">Dashboard</a>
                        </li>
                        <li>
                            <a href="<?php echo $config['LIVE_URL'];?>clients/id/<?php echo $cid; ?>/triggers/">Triggers list</a>
                        </li>
						 <li>
                            <a href="<?php echo $config['LIVE_URL'];?>clients/id/<?php echo $cid; ?>/triggers/<?php echo $tid; ?>/visuals/<?php echo $outArrClientTriggerVisuals[$i]['id'];?>">Visual list</a>
                        </li>
                         <li>
                            <a class="current" href="#">models</a>
                        </li>
                    </ul>
                </div>
</div>

<div style="clear: both;"></div>
       <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                      Models
					   <span class="tools pull-right">
                            <a href="javascript:;" class="fa fa-chevron-down"></a>
                            <a href="javascript:;" class="fa fa-cog"></a>
                            <a href="javascript:;" class="fa fa-times"></a>
                         </span>
                    </header>
					<div class="panel-body">
                    <div class="adv-table">
		   <div align="right">
		   <a href="<?php echo $config['LIVE_URL']; ?>dynamic_content/client_models_add.tpl.php?tid=<?php echo $tid; ?>&cid=<?php echo $cid; ?>&visual_id=<?php echo $visual_id;?>" rel="facebox[.bolder]" class="btn btn-success" > Add Model </a>&nbsp;&nbsp; 
	               </div>
         <table  class="display table table-bordered table-striped" id="dynamic-table">
                    <thead>
                    <tr>
                        <th>Model</th>
                        <th>Texture</th>
                        <th>Material</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
					<tbody>
					<?php for($i=0;$i<count($getClientModelsList);$i++) { 
					if($getClientModelsList[$i]['texture']!=""){
							
							$disptextureImage=str_replace("{client_id}",$cid,$config['files']['models']).$getClientModelsList[$i]['texture'];
						}else{
							$disptextureImage=$config['LIVE_URL']."views/images/no-product.png";
						}
						
				   ?>
				 <tr>
					<!--<td class="th table-check-cell"><input type="checkbox" name="selected[]" id="table-selected-1" value="1"></td>-->
					<td><?php echo $getClientModelsList[$i]['model']; ?></td>
					<td style="text-align:center;"><img src="<?php echo $disptextureImage; ?>" height="100"/></td>
                    <td><?php echo $getClientModelsList[$i]['material']; ?></td>
                    <td>  <a href="<?php echo $config['LIVE_URL']; ?>dynamic_content/client_models_edit.tpl.php?tid=<?php echo $tid; ?>&cid=<?php echo $cid; ?>&visual_id=<?php echo $getClientModelsList[$i]['three_d_model_id']; ?>&model_id=<?php echo $getClientModelsList[$i]['id'];?>" rel="facebox[.bolder]" class="btn btn-warning cancel" >Edit </a>&nbsp;&nbsp; 
					<button type="button" class="btn btn-danger delete" onclick="delete3DModel(<?php echo $cid; ?>,<?php echo $tid; ?>,<?php echo $getClientModelsList[$i]['three_d_model_id']; ?>,<?php echo $getClientModelsList[$i]['id'];?>);">Delete</button></td> 

                    </tr>
				<?php }  ?>
			</tbody>
               <tfoot>
                    <tr>
                         <th>Model</th>
                        <th>Texture</th>
                        <th>Material</th>
                        <th>Actions</th>
                    </tr>
                    </tfoot>
                    </table>
                    </div>
                    </div>
                </section>
            </div>
        </div>
 </section>
    </section>


















					