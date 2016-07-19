<?php
get_header();

$template_setting = kopa_get_template_setting();
$sidebars = $template_setting['sidebars'];
?>
<div class="wrapper">
    <div class="row-fluid">
        <div class="span12">
            <div id="main-col">                                                    
                <?php if (is_active_sidebar($sidebars[1])): ?>
                    <div class="row-fluid">
                        <div class="span12 clearfix">
                            <div class="widget-area-2">
                                <?php dynamic_sidebar($sidebars[1]); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>            


                <?php if (is_active_sidebar($sidebars[2]) || is_active_sidebar($sidebars[3])): ?>
                    <div class="row-fluid">
                        <div class="span12 clearfix">
                            <?php if (is_active_sidebar($sidebars[2])): ?>
                                <div class="widget-area-3">
                                    <?php dynamic_sidebar($sidebars[2]); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (is_active_sidebar($sidebars[3])): ?>
                                <div class="widget-area-4">
                                    <?php dynamic_sidebar($sidebars[3]); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (is_active_sidebar($sidebars[4])): ?>
                    <div class="row-fluid">
                        <div class="span12 clearfix">
                            <div class="widget-area-5">
                                <?php dynamic_sidebar($sidebars[4]); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>  
            </div><!--main-col-->   

           <div class="sidebar widget-area-1">
                <?php
                if (is_active_sidebar($sidebars[0])):
                    dynamic_sidebar($sidebars[0]);
                endif;
                ?>
            </div><!--sidebar-->

            <div class="clear"></div>

        </div><!--span12-->

    </div><!--row-fluid--> 

    <div class="row-fluid">
        
        <div class="span12">
            <div class="widget-area-14">
                <?php
                if (is_active_sidebar($sidebars[5])):
                    dynamic_sidebar($sidebars[5]);
                endif;
                ?>
            </div>
        </div>

    </div>

</div><!--wrapper-->

<?php
get_footer();