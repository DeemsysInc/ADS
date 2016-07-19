<style type="text/css">
#about h1.main_heading{
	color: red;
}
#about a.recent {
color: red;	
text-decoration: underline;
}
#about a.pressrelease_a{

	color: #4d4d4d;	
	text-decoration: underline;

}
#about  h4 {
line-height: 40px;
margin: 0;
}

</style>
		

		<section id="about">
				
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<h1 class="main_heading">Press</h1>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6" style="border-right: 1px solid #e5e5e5;">
						<h2>RECENT NEWS</h2>
						 <article>
		                    <h4><a class="recent" href="<?php echo $config['LIVE_URL'];?>news/detail/<?php echo isset($newsArray[0]['id']) ? $newsArray[0]['id'] : '';?>"><?php echo isset($newsArray[0]['title']) ? $newsArray[0]['title'] : '';?></a></h4>
		                    <time datetime="2013-00-11"><?php echo date('F d, Y', strtotime($newsArray[0]['created_date']));?></time>
		                    <div>
		                        <?php echo isset($newsArray[0]['subtitle']) ? $newsArray[0]['subtitle'] : '';?>
		                    </div>
		                </article>
		                <p><a class="recent" href="<?php echo $config['LIVE_URL'];?>news">See all news</a></p>
				
		                <p></p>
		                <p>For media inquiries, please contact <a class="recent" href="mailto:media@seemoreinteractive.com">media@seemoreinteractive.com</a>.</p>
					</div>
					<div class="col-md-6">
						<h2>PRESS RELEASES</h2>
						<ul>
			              <?php for($i=0;$i<count($pressReleasesArray);$i++){?>	  
                           <li>
                            <h4><a class="pressrelease_a" href="<?php echo isset($pressReleasesArray[$i]['url']) ? $pressReleasesArray[$i]['url'] : '';?>" target="_blank"><?php echo isset($pressReleasesArray[$i]['title']) ? $pressReleasesArray[$i]['title'] : '';?></a></h4>
                           </li>
                        <?php }?>
				        </ul>
                        <p><a class="recent" href="<?php echo $config['LIVE_URL'];?>pressrelease">View archive</a></p>
					</div>
				</div>
			</div>
		</section>

