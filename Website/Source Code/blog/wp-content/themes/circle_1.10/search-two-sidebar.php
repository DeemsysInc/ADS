<?php
get_header();

$template_setting = kopa_get_template_setting();
$sidebars = $template_setting['sidebars'];
?>

<div class="wrapper">
    <div class="row-fluid">
        <div class="span12">

            <div class="sidebar left-sidebar widget-area-10">
                <?php
                if (is_active_sidebar($sidebars[1])):
                    dynamic_sidebar($sidebars[1]);
                endif;
                ?>
            </div><!--sidebar-->

            <div id="main-col">
                <ul class="article-list clearfix">
                    <?php get_template_part('loop', 'search'); ?>
                </ul><!--article-list-->

                <?php get_template_part('pagination'); ?>
            </div><!--main-col-->

            <div class="sidebar right-sidebar widget-area-1">
                <?php
                if (is_active_sidebar($sidebars[0])):
                    dynamic_sidebar($sidebars[0]);
                endif;
                ?>
            </div><!--sidebar-->

            <div class="clear"></div>

        </div><!--span12-->

    </div><!--row-fluid-->  

</div><!--wrapper-->

<?php
get_footer();