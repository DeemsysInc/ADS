<?php
get_header();
?>

<div class="wrapper">
    <div class="row-fluid">
        <div class="span12">
            <div id="main-col">
                <div class="elements-box">
                    <?php get_template_part('loop', 'page'); ?>
                </div>
            </div><!--main-col-->

            <aside class="sidebar widget-area-1">
                <?php
                if (is_active_sidebar('sidebar_1')):
                    dynamic_sidebar('sidebar_1');
                endif;
                ?>
            </aside><!--sidebar-->

            <div class="clear"></div>

        </div><!--span12-->

    </div><!--row-fluid-->  

</div><!--wrapper-->

<?php
get_footer();