<?php
get_header();

$template_setting = kopa_get_template_setting();
$sidebars = $template_setting['sidebars'];


?>

<div class="wrapper">
    <div class="row-fluid">
        <div class="span12">
            <div id="main-col">
            
                <div class="article-list-wrapper clearfix">
                    <div class="article-list-line"></div>
                    <span class="article-bullet"></span>

                    <ul class="article-list clearfix">
                        <?php get_template_part('loop', 'blog-1'); ?>
                    </ul><!--article-list-->

                    <?php get_template_part('pagination'); ?>

                </div><!--article-list-wrapper-->

            </div><!--main-col-->

            <div class="sidebar widget-area-10">
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