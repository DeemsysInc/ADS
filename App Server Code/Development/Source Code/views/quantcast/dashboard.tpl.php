<!--main content start-->
<section id="main-content">
	<section class="wrapper">
		<div class="row">
						<div class="col-md-12">
							<ul class="breadcrumbs-alt">
								<li>
									<a class="current" href="<?php echo $config['LIVE_URL'];?>">Dashboard</a>
								</li>                        
							</ul>
						</div>
		</div>
	 

		<!--mini statistics start-->
		<div class="row">
			<div class="col-md-3">
				<a href="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/clients/<?php echo $arrData[0]['client_info'][0]['id'];?>/products/<?php echo $start_date;?>/<?php echo $end_date;?>">
				<div class="mini-stat clearfix">
					<span class="mini-stat-icon orange"><i class="fa fa-gavel"></i></span>
					<div class="mini-stat-info">
					   <span><div id="uniques">1</div></span>
						UNIQUES
					</div>
				</div>
				</a>
			</div>
			
			<div class="col-md-3">
				<a href="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/clients/<?php echo $arrData[0]['client_info'][0]['id'];?>/offers/<?php echo $start_date;?>/<?php echo $end_date;?>">
				<div class="mini-stat clearfix">
					<span class="mini-stat-icon tar"><i class="fa fa-tag"></i></span>
					<div class="mini-stat-info">
						<span><div id="visits">1</div></span>
						VISITS
					</div>
				</div>
				</a>
			</div> 
			<div class="col-md-3">
				<a href="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/clients/<?php echo $arrData[0]['client_info'][0]['id'];?>/offers/<?php echo $start_date;?>/<?php echo $end_date;?>">
				<div class="mini-stat clearfix">
					<span class="mini-stat-icon tar"><i class="fa fa-tag"></i></span>
					<div class="mini-stat-info">
						<span><div id="installs">1</div></span>
						INSTALLS
					</div>
				</div>
				</a>
			</div> 
			<div class="col-md-3">
				<a href="<?php echo $config['LIVE_URL']; ?>analytics/mobile_users/clients/<?php echo $arrData[0]['client_info'][0]['id'];?>/offers/<?php echo $start_date;?>/<?php echo $end_date;?>">
				<div class="mini-stat clearfix">
					<span class="mini-stat-icon tar"><i class="fa fa-tag"></i></span>
					<div class="mini-stat-info">
						<span><div id="upgrades">1</div></span>
						UPGRADES
					</div>
				</div>
				</a>
			</div> 
			
		</div>
		<!--mini statistics end-->


	</section>
</section>
<!--main content end-->
</section>

