<?php
get_header();
$template_setting = kopa_get_template_setting();
$sidebars = $template_setting['sidebars'];

if (is_active_sidebar($sidebars[0])):
    ?>
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-area-2">
                <?php
                dynamic_sidebar($sidebars[0]);
                ?>
            </div><!--widget-area-2-->
        </div><!--span12-->
    </div><!--row-fluid-->
<?php
endif;
if (is_active_sidebar($sidebars[1]) or is_active_sidebar($sidebars[2])):
    ?>
    <div class="row-fluid">
        <div class="span12">
            <div class="kp-our-experient">
                <span class="bottom-circle"></span>
                <span class="bottom-bullet"></span>
                <div class="wrapper">
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="widget-area-3">
                                <?php
                                if (is_active_sidebar($sidebars[1])):
                                    dynamic_sidebar($sidebars[1]);
                                endif;
                                ?>
                            </div><!--widget-area-3-->

                            <div class="widget-area-4">
                                <?php
                                if (is_active_sidebar($sidebars[2])):
                                    dynamic_sidebar($sidebars[2]);
                                endif;
                                ?>
                            </div><!--widget-area-4-->
                            <div class="clear"></div>

                        </div><!--span12-->
                    </div><!--row-fluid-->
                </div><!--wrapper-->
            </div><!--kp-our-experient-->
        </div><!--span12-->
    </div><!--row-fluid-->
<?php
endif;
if (is_active_sidebar($sidebars[3])):
    ?>
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-area-5">
                <?php
                dynamic_sidebar($sidebars[3]);
                ?>            
            </div><!--widget-area-5-->
        </div><!--span12-->
    </div><!--row-fluid-->
    <?php
endif;
get_footer();