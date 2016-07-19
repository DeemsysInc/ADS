<div class="kopa-content-box tab-content tab-content-1" id="tab-general">

    <!--tab-logo-favicon-icon-->
    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('Logo, Favicon, Apple Icon', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->

    <div class="kopa-box-body">
        <div class="kopa-element-box kopa-theme-options">

            <span class="kopa-component-title"><?php _e('Logo', kopa_get_domain()); ?></span>
            <p class="kopa-desc"><?php _e('Upload your own logo.', kopa_get_domain()); ?></p>                         
            <div class="clearfix">
                <input class="left" type="text" value="<?php echo get_option('kopa_theme_options_logo_url'); ?>" id="kopa_theme_options_logo_url" name="kopa_theme_options_logo_url">
                <button class="left btn btn-success upload_image_button" alt="kopa_theme_options_logo_url"><i class="icon-circle-arrow-up"></i><?php _e('Upload', kopa_get_domain()); ?></button>
            </div>
            <p class="kopa-desc"><?php _e('Logo margin', kopa_get_domain()); ?></p>
            <label class="kopa-label"><?php _e('Top margin:', kopa_get_domain()); ?> </label>
            <input type="text" value="<?php echo get_option('kopa_theme_options_logo_margin_top'); ?>" id="kopa_theme_options_logo_margin_top" name="kopa_theme_options_logo_margin_top" class=" kopa-short-input">
            <label class="kopa-label"><?php _e('px', kopa_get_domain()); ?></label>

            <span class="kopa-spacer"></span>

            <label class="kopa-label"><?php _e('Left margin:', kopa_get_domain()); ?> </label>
            <input type="text" value="<?php echo get_option('kopa_theme_options_logo_margin_left'); ?>" id="kopa_theme_options_logo_margin_left" name="kopa_theme_options_logo_margin_left" class=" kopa-short-input">
            <label class="kopa-label"><?php _e('px', kopa_get_domain()); ?></label>

            <span class="kopa-spacer"></span>

            <label class="kopa-label"><?php _e('Right margin:', kopa_get_domain()); ?> </label>
            <input type="text" value="<?php echo get_option('kopa_theme_options_logo_margin_right'); ?>" id="kopa_theme_options_logo_margin_right" name="kopa_theme_options_logo_margin_right" class=" kopa-short-input">
            <label class="kopa-label"><?php _e('px', kopa_get_domain()); ?></label>

            <span class="kopa-spacer"></span>

            <label class="kopa-label"><?php _e('Bottom margin:', kopa_get_domain()); ?> </label>
            <input type="text" value="<?php echo get_option('kopa_theme_options_logo_margin_bottom'); ?>" id="kopa_theme_options_logo_margin_bottom" name="kopa_theme_options_logo_margin_bottom" class=" kopa-short-input">
            <label class="kopa-label"><?php _e('px', kopa_get_domain()); ?></label>
        </div><!--kopa-element-box-->


        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Favicon', kopa_get_domain()); ?></span>

            <p class="kopa-desc"><?php _e('Upload your own favicon.', kopa_get_domain()); ?></p>    
            <div class="clearfix">
                <input class="left" type="text" value="<?php echo get_option('kopa_theme_options_favicon_url'); ?>" id="kopa_theme_options_favicon_url" name="kopa_theme_options_favicon_url">
                <button class="left btn btn-success upload_image_button" alt="kopa_theme_options_favicon_url"><i class="icon-circle-arrow-up"></i><?php _e('Upload', kopa_get_domain()); ?></button>
            </div>
        </div><!--kopa-element-box-->


        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Apple Icons', kopa_get_domain()); ?></span>

            <p class="kopa-desc"><?php _e('Iphone (57px - 57px)', kopa_get_domain()); ?></p>   
            <div class="clearfix">
                <input class="left" type="text" value="<?php echo get_option('kopa_theme_options_apple_iphone_icon_url'); ?>" id="kopa_theme_options_apple_iphone_icon_url" name="kopa_theme_options_apple_iphone_icon_url">
                <button class="left btn btn-success upload_image_button" alt="kopa_theme_options_apple_iphone_icon_url"><i class="icon-circle-arrow-up"></i><?php _e('Upload', kopa_get_domain()); ?></button>
            </div>
            <p class="kopa-desc"><?php _e('Iphone Retina (114px - 114px)', kopa_get_domain()); ?></p>    
            <div class="clearfix">
                <input class="left" type="text" value="<?php echo get_option('kopa_theme_options_apple_iphone_retina_icon_url'); ?>" id="kopa_theme_options_apple_iphone_retina_icon_url" name="kopa_theme_options_apple_iphone_retina_icon_url">
                <button class="left btn btn-success upload_image_button" alt="kopa_theme_options_apple_iphone_retina_icon_url"><i class="icon-circle-arrow-up"></i><?php _e('Upload', kopa_get_domain()); ?></button>
            </div>

            <p class="kopa-desc"><?php _e('Ipad (72px - 72px)', kopa_get_domain()); ?></p>    
            <div class="clearfix">
                <input class="left" type="text" value="<?php echo get_option('kopa_theme_options_apple_ipad_icon_url'); ?>" id="kopa_theme_options_apple_ipad_icon_url" name="kopa_theme_options_apple_ipad_icon_url">
                <button class="left btn btn-success upload_image_button" alt="kopa_theme_options_apple_ipad_icon_url"><i class="icon-circle-arrow-up"></i><?php _e('Upload', kopa_get_domain()); ?></button>
            </div>

            <p class="kopa-desc"><?php _e('Ipad Retina (144px - 144px)', kopa_get_domain()); ?></p>    
            <div class="clearfix">
                <input class="" type="text" value="<?php echo get_option('kopa_theme_options_apple_ipad_retina_icon_url'); ?>" id="kopa_theme_options_apple_ipad_retina_icon_url" name="kopa_theme_options_apple_ipad_retina_icon_url">
                <button class="btn btn-success upload_image_button" alt="kopa_theme_options_apple_ipad_retina_icon_url"><i class="icon-circle-arrow-up"></i><?php _e('Upload', kopa_get_domain()); ?></button>
            </div>
        </div><!--kopa-element-box-->


    </div><!--tab-logo-favicon-icon-->


    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('Main Content', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->

    <div class="kopa-box-body">

        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Play Video in lightbox', kopa_get_domain()); ?></span>
            <?php
            $play_video_types = array(
                'enable' => __('Enable', kopa_get_domain()),
                'disable' => __('Disable', kopa_get_domain())
            );
            $play_video_type_name = "kopa_theme_options_play_video_in_lightbox";
            foreach ($play_video_types as $value => $label):
                $play_video_type_id = $play_video_type_name . "_{$value}";
                ?>
                <label  for="<?php echo $play_video_type_id; ?>" class="kopa-label-for-radio-button"><input type="radio" value="<?php echo $value; ?>" id="<?php echo $play_video_type_id; ?>" name="<?php echo $play_video_type_name; ?>" <?php echo ($value == get_option($play_video_type_name, 'disable')) ? 'checked="checked"' : ''; ?>><?php echo $label; ?></label>
                <?php
            endforeach
            ?>
        </div>
    </div>
    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('Contact Information', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->

    <div class="kopa-box-body">
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Show contact information at the header', kopa_get_domain()); ?></span>  
            <span class="kopa-component-title"><?php _e('Email:', kopa_get_domain()); ?></span>
            <input type="text" value="<?php echo get_option('kopa_theme_options_email_address'); ?>" id="kopa_theme_options_email_address" name="kopa_theme_options_email_address">
        </div>
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Phone number:', kopa_get_domain()); ?></span>
            <input type="text" value="<?php echo get_option('kopa_theme_options_phone_number'); ?>" id="kopa_theme_options_phone_number" name="kopa_theme_options_phone_number">
        </div>
    </div>


    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('Portfolio', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->

    <div class="kopa-box-body">
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Show related portfolio by', kopa_get_domain()); ?></span>                        
            <select class="" id="kopa_theme_options_portfolio_related_get_by" name="kopa_theme_options_portfolio_related_get_by">
                <?php
                $portfolio_related_get_by = array(
                    'hide' => __('-- Hide --', kopa_get_domain()),
                    'portfolio_project' => __('Project', kopa_get_domain()),
                    'portfolio_tag' => __('Tag', kopa_get_domain())
                );
                foreach ($portfolio_related_get_by as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value == get_option('kopa_theme_options_portfolio_related_get_by', 'portfolio_tag')) ? 'selected="selected"' : '');
                }
                ?>
            </select>                        
        </div>
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Limit', kopa_get_domain()); ?></span>
            <input type="number" value="<?php echo get_option('kopa_theme_options_portfolio_related_limit', 3); ?>" id="kopa_theme_options_portfolio_related_limit" name="kopa_theme_options_portfolio_related_limit">
        </div>
    </div>

    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('Footer', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->

    <div class="kopa-box-body">

        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Background', kopa_get_domain()); ?></span>
            <p class="kopa-desc"><?php _e('Theme support two footer style: Dark & Light', kopa_get_domain()); ?></p>    
            <?php
            $footer_styles = array(
                'dark-footer' => __('Dark', kopa_get_domain()),
                'light-footer' => __('Light', kopa_get_domain())
            );
            $footer_style_name = "kopa_theme_options_footer_style";
            foreach ($footer_styles as $value => $label):
                $footer_style_id = $footer_style_name . "_{$value}";
                ?>
                <label  for="<?php echo $footer_style_id; ?>" class="kopa-label-for-radio-button"><input type="radio" value="<?php echo $value; ?>" id="<?php echo $footer_style_id; ?>" name="<?php echo $footer_style_name; ?>" <?php echo ($value == get_option($footer_style_name, 'dark-footer')) ? 'checked="checked"' : ''; ?>><?php echo $label; ?></label>
                <?php
            endforeach
            ?>
        </div>

        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Custom Footer', kopa_get_domain()); ?></span>
            <p class="kopa-desc"><?php _e('Enter the content you want to display in your footer (e.g. copyright text).', kopa_get_domain()); ?></p>    
            <textarea class="" rows="6" id="kopa_setting_copyrights" name="kopa_theme_options_copyright"><?php echo htmlspecialchars_decode(stripslashes(get_option('kopa_theme_options_copyright'))); ?></textarea>
        </div><!--kopa-element-box-->


        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Google Analytics', kopa_get_domain()); ?></span>
            <p class="kopa-desc"><?php _e('Enter Google Analytics code. This should be something like: &ltscript type="text/javascript"&gt;  ...  &lt;/script&gt;', kopa_get_domain()); ?></p>    
            <textarea class="" id="kopa_setting_tracking_code" rows="10" name="kopa_theme_options_tracking_code"><?php echo htmlspecialchars_decode(stripslashes(get_option('kopa_theme_options_tracking_code'))); ?></textarea>
        </div><!--kopa-element-box-->

    </div>

</div><!--kopa-content-box-->

