<?php
get_header();
?>

<div class="wrapper">
    <div class="row-fluid">
        <div class="span12">
            <div id="main-col-full">
                <div class="elements-box">
                    <?php get_template_part('loop', 'page'); ?>
                </div>
            </div><!--main-col-->          

            <div class="cflear"></div>

        </div><!--span12-->

    </div><!--row-fluid-->  

</div><!--wrapper-->

<?php
get_footer();