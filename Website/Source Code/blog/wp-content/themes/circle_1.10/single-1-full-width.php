<?php
get_header();
?>

<div class="wrapper">
    <div class="row-fluid">
        <div class="span12">
            <div id="main-col-full">
                <section class="single-wrapper">
                    <div class="single-line"></div>
                    <div class="single-bullet"></div>                    
                    <?php get_template_part('loop', 'single-1'); ?>                                        
                </section>              
            </div><!--main-col-->

            <div class="clear"></div>

        </div><!--span12-->

    </div><!--row-fluid-->  

</div><!--wrapper-->

<?php
get_footer();