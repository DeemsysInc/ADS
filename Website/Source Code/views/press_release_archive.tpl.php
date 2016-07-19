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
#about article.news_details{
margin: 0 0 20px;
}
#about time {
color: #4d4d4d;
font: .857143em 'HelveticaNeueW01-75Bold';
}
</style>
        

<section id="about">

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="main_heading">Press Release Archive</h1>
            </div>
        </div>
        <div class="row">
            
            <div class="col-md-12">
              <?php for($i=0;$i<count($pressReleasesArray);$i++){?>  
                <article class="news_details">
                    <p><a class="recent" href="<?php echo isset($pressReleasesArray[$i]['url']) ? $pressReleasesArray[$i]['url'] : '';?>" target="_blank"><?php echo isset($pressReleasesArray[$i]['title']) ? $pressReleasesArray[$i]['title'] : '';?></a></p>
                    <time datetime="2013-00-03"><?php echo date('F d, Y', strtotime($pressReleasesArray[$i]['created_date']));?></time>
                   
                </article>
                
                <?php }?>
            </div>
        </div>
    </div>
</section>




