<?php
$template_setting = kopa_get_template_setting();
$sidebars = $template_setting['sidebars'];
$total = count($sidebars);

$footer_sidebar[0] = ($template_setting) ? $sidebars[$total - 4] : 'sidebar_6';
$footer_sidebar[1] = ($template_setting) ? $sidebars[$total - 3] : 'sidebar_7';
$footer_sidebar[2] = ($template_setting) ? $sidebars[$total - 2] : 'sidebar_8';
$footer_sidebar[3] = ($template_setting) ? $sidebars[$total - 1] : 'sidebar_9';
?>

</div><!--main-content-->

<div id="bottom-sidebar">

    <div class="wrapper">

        <div class="row-fluid">

            <div class="span3">
                <?php
                if (is_active_sidebar($footer_sidebar[0])):
                    dynamic_sidebar($footer_sidebar[0]);
                endif;
                ?>
            </div><!--span3-->

            <div class="span3">
                <?php
                if (is_active_sidebar($footer_sidebar[1])):
                    dynamic_sidebar($footer_sidebar[1]);
                endif;
                ?>
            </div><!--span3-->

            <div class="span3">
                <?php
                if (is_active_sidebar($footer_sidebar[2])):
                    dynamic_sidebar($footer_sidebar[2]);
                endif;
                ?>
            </div><!--span3-->

            <div class="span3">
                <?php
                if (is_active_sidebar($footer_sidebar[3])):
                    dynamic_sidebar($footer_sidebar[3]);
                endif;
                ?>
            </div><!--span3-->

        </div><!--row-fluid-->

    </div><!--wrapper-->

</div><!--bottom-sidebar-->

<footer id="page-footer">
    <div class="wrapper">
        <div class="row-fluid">
            <div class="span12">
                <p id="copyright"><?php echo get_option('kopa_theme_options_copyright'); ?></p>
            </div><!--span12-->
        </div><!--row-fluid-->
    </div><!--wrapper-->
</footer><!--page-footer-->

<p id="back-top"><a href="#top"><?php _e('Back to Top', kopa_get_domain()); ?></a></p>

<?php
$kopa_theme_options_tracking_code = get_option('kopa_theme_options_tracking_code');
if (!empty($kopa_theme_options_tracking_code)) {
    echo htmlspecialchars_decode(stripslashes($kopa_theme_options_tracking_code));
}
wp_footer();
?>
</body>
</html>