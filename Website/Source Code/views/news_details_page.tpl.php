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
            <div class="col-md-6">
                <h1 class="main_heading"><?php echo isset($arrNewsDetailsById[0]['title']) ? $arrNewsDetailsById[0]['title'] : '';?></h1>
            </div>
        </div>    
        <div class="row">
                
            <div class="col-md-6">
                <p><strong> (<?php echo date('F d, Y', strtotime($arrNewsDetailsById[0]['created_date']));?>)</strong></p>
                <p><?php echo isset($arrNewsDetailsById[0]['content']) ? $arrNewsDetailsById[0]['content'] : '';?></p>
            </div>
            <div class="col-md-6">
                 <h3>News Contacts</h3>
                 <p>For media inquiries, please contact <a class="recent" href="mailto:media@seemoreinteractive.com">media@seemoreinteractive.com</a>.</p>
            </div>

        </div>
    </div>
</section>
















