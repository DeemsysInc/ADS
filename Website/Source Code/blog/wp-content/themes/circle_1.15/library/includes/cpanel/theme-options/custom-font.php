<div id="tab-custom-font" class="kopa-content-box tab-content tab-content-1">    
    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('Custom Fonts', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->
    <div class="kopa-box-body">
        <div class="kopa-element-box kopa-theme-options">            
            <p class="kopa-desc"><?php _e('Heading font', kopa_get_domain()); ?></p>
            <?php
            $google_fonts = kopa_get_google_font_array();
            $current_heading_font = get_option('kopa_theme_options_heading_font_family');
            $default_selected = 'selected="selected"';
            $display_none = ' display:none;';
            if ($current_heading_font):
                $default_selected = '';
                $display_none = '';
            endif;
            ?>
            <div class="font-sample" style="<?php echo $display_none;
            if($current_heading_font) echo 'font-family:' . $google_fonts[$current_heading_font]['family'];
            ?>"><?php _e('Sample Heading Font', kopa_get_domain()); ?></div>
            <select id="kopa_theme_options_heading_font_family" name="kopa_theme_options_heading_font_family" autocomplete="off" onchange="on_change_font(jQuery(this));">                
                <?php
                printf('<option value="" %1$s>%2$s</option>', $default_selected, '-- Default --');
                foreach ($google_fonts as $font_id => $font_value) {
                    if ($current_heading_font && $current_heading_font == $font_id) {
                        $selected = 'selected="selected"';
                    }
                    else
                        $selected = '';
                    printf('<option value="%1$s" %3$s>%2$s</option>', $font_id, $font_value['family'], $selected);
                }
                ?>
            </select>
            <p class="kopa-desc"><?php _e('Font size', kopa_get_domain()); ?></p>
            <label class="kopa-label"><?php _e('H1 size:', kopa_get_domain()); ?> </label>
            <input type="text" value="<?php echo get_option('kopa_theme_options_h1_font_size'); ?>" id="kopa_theme_options_h1_font_size" name="kopa_theme_options_h1_font_size" class=" kopa-short-input">
            <label class="kopa-label"><?php _e('px', kopa_get_domain()); ?></label>
            <span class="kopa-spacer"></span>

            <label class="kopa-label"><?php _e('H2 size:', kopa_get_domain()); ?> </label>
            <input type="text" value="<?php echo get_option('kopa_theme_options_h2_font_size'); ?>" id="kopa_theme_options_h2_font_size" name="kopa_theme_options_h2_font_size" class=" kopa-short-input">
            <label class="kopa-label"><?php _e('px', kopa_get_domain()); ?></label>
            <span class="kopa-spacer"></span>

            <label class="kopa-label"><?php _e('H3 size:', kopa_get_domain()); ?> </label>
            <input type="text" value="<?php echo get_option('kopa_theme_options_h3_font_size'); ?>" id="kopa_theme_options_h3_font_size" name="kopa_theme_options_h3_font_size" class=" kopa-short-input">
            <label class="kopa-label"><?php _e('px', kopa_get_domain()); ?></label>
            <span class="kopa-spacer"></span>

            <label class="kopa-label"><?php _e('H4 size:', kopa_get_domain()); ?> </label>
            <input type="text" value="<?php echo get_option('kopa_theme_options_h4_font_size'); ?>" id="kopa_theme_options_h4_font_size" name="kopa_theme_options_h4_font_size" class=" kopa-short-input">
            <label class="kopa-label"><?php _e('px', kopa_get_domain()); ?></label>
            <span class="kopa-spacer"></span>

            <label class="kopa-label"><?php _e('H5 size:', kopa_get_domain()); ?> </label>
            <input type="text" value="<?php echo get_option('kopa_theme_options_h5_font_size'); ?>" id="kopa_theme_options_h5_font_size" name="kopa_theme_options_h5_font_size" class=" kopa-short-input">
            <label class="kopa-label"><?php _e('px', kopa_get_domain()); ?></label>
            <span class="kopa-spacer"></span>

            <label class="kopa-label"><?php _e('H6 size:', kopa_get_domain()); ?> </label>
            <input type="text" value="<?php echo get_option('kopa_theme_options_h6_font_size'); ?>" id="kopa_theme_options_h6_font_size" name="kopa_theme_options_h6_font_size" class=" kopa-short-input">
            <label class="kopa-label"><?php _e('px', kopa_get_domain()); ?></label>
            <span class="kopa-spacer"></span>
            <br>
            <p class="kopa-desc"><?php _e('Font Weight', kopa_get_domain()); ?></p>
            <label class="kopa-label"><?php _e('H1 weight:', kopa_get_domain()); ?> </label>
            <input type="text" value="<?php echo get_option('kopa_theme_options_h1_font_weight'); ?>" id="kopa_theme_options_h1_font_weight" name="kopa_theme_options_h1_font_weight" class=" kopa-short-input">            
            <span class="kopa-spacer"></span>

            <label class="kopa-label"><?php _e('H2 weight:', kopa_get_domain()); ?> </label>
            <input type="text" value="<?php echo get_option('kopa_theme_options_h2_font_weight'); ?>" id="kopa_theme_options_h2_font_weight" name="kopa_theme_options_h2_font_weight" class=" kopa-short-input">           
            <span class="kopa-spacer"></span>

            <label class="kopa-label"><?php _e('H3 weight:', kopa_get_domain()); ?> </label>
            <input type="text" value="<?php echo get_option('kopa_theme_options_h3_font_weight'); ?>" id="kopa_theme_options_h3_font_weight" name="kopa_theme_options_h3_font_weight" class=" kopa-short-input">            
            <span class="kopa-spacer"></span>

            <label class="kopa-label"><?php _e('H4 weight:', kopa_get_domain()); ?> </label>
            <input type="text" value="<?php echo get_option('kopa_theme_options_h4_font_weight'); ?>" id="kopa_theme_options_h4_font_weight" name="kopa_theme_options_h4_font_weight" class=" kopa-short-input">            
            <span class="kopa-spacer"></span>

            <label class="kopa-label"><?php _e('H5 weight:', kopa_get_domain()); ?> </label>
            <input type="text" value="<?php echo get_option('kopa_theme_options_h5_font_weight'); ?>" id="kopa_theme_options_h5_font_weight" name="kopa_theme_options_h5_font_weight" class=" kopa-short-input">           
            <span class="kopa-spacer"></span>

            <label class="kopa-label"><?php _e('H6 weight:', kopa_get_domain()); ?> </label>
            <input type="text" value="<?php echo get_option('kopa_theme_options_h6_font_weight'); ?>" id="kopa_theme_options_h6_font_weight" name="kopa_theme_options_h6_font_weight" class=" kopa-short-input">           
            <span class="kopa-spacer"></span>
        </div><!--kopa-element-box-->
        
        
        
        
        
        
        
        
        <div class="kopa-element-box kopa-theme-options">            
            <p class="kopa-desc"><?php _e('Content font', kopa_get_domain()); ?></p>
            <?php
            $current_content_font = get_option('kopa_theme_options_content_font_family');
            $default_selected = 'selected="selected"';
            $display_none = ' display:none;';
            if ($current_content_font):
                $default_selected = '';
                $display_none = '';
            endif;
            ?>
            <div class="font-sample" style="<?php echo $display_none;
            if ($current_content_font) echo 'font-family:' . $google_fonts[$current_content_font]['family'];
            ?>"><?php _e('Sample Content Font', kopa_get_domain()); ?></div>
            <select id="kopa_theme_options_content_font_family" name="kopa_theme_options_content_font_family" autocomplete="off" onchange="on_change_font(jQuery(this));">                
                <?php
                printf('<option value="" %1$s>%2$s</option>', $default_selected, '-- Default --');
                foreach ($google_fonts as $font_id => $font_value) {
                    if ($current_content_font && $current_content_font == $font_id) {
                        $selected = 'selected="selected"';
                    }
                    else
                        $selected = '';
                    printf('<option value="%1$s" %3$s>%2$s</option>', $font_id, $font_value['family'], $selected);
                }
                ?>
            </select>
            <p class="kopa-desc"><?php _e('Font size', kopa_get_domain()); ?></p>
            <label class="kopa-label"><?php _e('Content font size:', kopa_get_domain()); ?> </label>
            <input type="text" value="<?php echo get_option('kopa_theme_options_content_font_size'); ?>" id="kopa_theme_options_content_font_size" name="kopa_theme_options_content_font_size" class=" kopa-short-input">            
        </div><!--kopa-element-box-->
        
        <div class="kopa-element-box kopa-theme-options">            
            <p class="kopa-desc"><?php _e('Main navigation font', kopa_get_domain()); ?></p>
            <?php
            $current_nav_font = get_option('kopa_theme_options_main_nav_font_family');
            $default_selected = 'selected="selected"';
            $display_none = ' display:none;';
            if ($current_nav_font):
                $default_selected = '';
                $display_none = '';
            endif;
            ?>
            <div class="font-sample" style="<?php echo $display_none;
               if ($current_nav_font)  echo 'font-family:' . $google_fonts[$current_nav_font]['family'];
            ?>"><?php _e('Sample Main Navigation Font', kopa_get_domain()); ?></div>
            <select id="kopa_theme_options_main_nav_font_family" name="kopa_theme_options_main_nav_font_family" autocomplete="off" onchange="on_change_font(jQuery(this));">                
                <?php
                printf('<option value="" %1$s>%2$s</option>', $default_selected, '-- Default --');
                foreach ($google_fonts as $font_id => $font_value) {
                    if ($current_nav_font && $current_nav_font == $font_id) {
                        $selected = 'selected="selected"';
                    }
                    else
                        $selected = '';
                    printf('<option value="%1$s" %3$s>%2$s</option>', $font_id, $font_value['family'], $selected);
                }
                ?>
            </select>
            
            <p class="kopa-desc"><?php _e('Font size', kopa_get_domain()); ?></p>            
            <label class="kopa-label"><?php _e('Main nagivation size:', kopa_get_domain()); ?> </label>
            <input type="text" value="<?php echo get_option('kopa_theme_options_main_nav_font_size'); ?>" id="kopa_theme_options_main_nav_font_size" name="kopa_theme_options_main_nav_font_size" class=" kopa-short-input">
            <label class="kopa-label"><?php _e('px', kopa_get_domain()); ?></label>            
            
            <br>
            
            <p class="kopa-desc"><?php _e('Font Weight', kopa_get_domain()); ?></p>
            <label class="kopa-label"><?php _e('Main nagivation font weight:', kopa_get_domain()); ?> </label>
            <input type="text" value="<?php echo get_option('kopa_theme_options_main_nav_font_weight'); ?>" id="kopa_theme_options_main_nav_font_weight" name="kopa_theme_options_main_nav_font_weight" class=" kopa-short-input">            
            
            <span class="kopa-spacer"></span>
        </div><!--kopa-element-box-->
        
        
        
                                              
        <div class="kopa-element-box kopa-theme-options">            
            <p class="kopa-desc"><?php _e('Widget title font (sidebar)', kopa_get_domain()); ?></p>
            <?php
            $current_wdg_sidebar_font = get_option('kopa_theme_options_wdg_sidebar_font_family');
            $default_selected = 'selected="selected"';
            $display_none = ' display:none;';
            if ($current_wdg_sidebar_font):
                $default_selected = '';
                $display_none = '';
            endif;
            ?>
            <div class="font-sample" style="<?php echo $display_none;
            if ($current_wdg_sidebar_font) echo 'font-family:' . $google_fonts[$current_wdg_sidebar_font]['family'];
            ?>"><?php _e('Sample widget title font (sidebar)', kopa_get_domain()); ?></div>
            <select id="kopa_theme_options_wdg_sidebar_font_family" name="kopa_theme_options_wdg_sidebar_font_family" autocomplete="off" onchange="on_change_font(jQuery(this));">                
                <?php
                printf('<option value="" %1$s>%2$s</option>', $default_selected, '-- Default --');
                foreach ($google_fonts as $font_id => $font_value) {
                    if ($current_wdg_sidebar_font && $current_wdg_sidebar_font == $font_id) {
                        $selected = 'selected="selected"';
                    }
                    else
                        $selected = '';
                    printf('<option value="%1$s" %3$s>%2$s</option>', $font_id, $font_value['family'], $selected);
                }
                ?>
            </select>
            
            <p class="kopa-desc"><?php _e('Font size', kopa_get_domain()); ?></p>
            <label class="kopa-label"><?php _e('Widget title (sidebar) font size:', kopa_get_domain()); ?> </label>
            <input type="text" value="<?php echo get_option('kopa_theme_options_wdg_sidebar_font_size'); ?>" id="kopa_theme_options_wdg_sidebar_font_size" name="kopa_theme_options_wdg_sidebar_font_size" class=" kopa-short-input">
            <label class="kopa-label"><?php _e('px', kopa_get_domain()); ?></label>
            
            <br>
            
            <p class="kopa-desc"><?php _e('Font weight', kopa_get_domain()); ?></p>
            <label class="kopa-label"><?php _e('Widget title (sidebar) font weight:', kopa_get_domain()); ?> </label>
            <input type="text" value="<?php echo get_option('kopa_theme_options_wgd_sidebar_font_weight'); ?>" id="kopa_theme_options_wgd_sidebar_font_weight" name="kopa_theme_options_wgd_sidebar_font_weight" class=" kopa-short-input">            
            
            <span class="kopa-spacer"></span>
            
        </div><!--kopa-element-box-->
        
        
        
        
        
        
        
        <div class="kopa-element-box kopa-theme-options">            
            <p class="kopa-desc"><?php _e('Widget title font (footer)', kopa_get_domain()); ?></p>
            <?php
            $current_wdg_footer_font = get_option('kopa_theme_options_wdg_footer_font_family');
            $default_selected = 'selected="selected"';
            $display_none = ' display:none;';
            if ($current_wdg_footer_font):
                $default_selected = '';
                $display_none = '';
            endif;
            ?>
            <div class="font-sample" style="<?php echo $display_none;
               if ($current_wdg_footer_font) echo 'font-family:' . $google_fonts[$current_wdg_footer_font]['family'];
            ?>"><?php _e('Sample widget title font (footer)', kopa_get_domain()); ?></div>
            <select id="kopa_theme_options_wdg_footer_font_family" name="kopa_theme_options_wdg_footer_font_family" autocomplete="off" onchange="on_change_font(jQuery(this));">                
                <?php
                printf('<option value="" %1$s>%2$s</option>', $default_selected, '-- Default --');
                foreach ($google_fonts as $font_id => $font_value) {
                    if ($current_wdg_footer_font && $current_wdg_footer_font == $font_id) {
                        $selected = 'selected="selected"';
                    }
                    else
                        $selected = '';
                    printf('<option value="%1$s" %3$s>%2$s</option>', $font_id, $font_value['family'], $selected);
                }
                ?>
            </select>
            
            <p class="kopa-desc"><?php _e('Font size', kopa_get_domain()); ?></p>
            <label class="kopa-label"><?php _e('Widget title (footer) font size:', kopa_get_domain()); ?> </label>
            <input type="text" value="<?php echo get_option('kopa_theme_options_wdg_footer_font_size'); ?>" id="kopa_theme_options_wdg_footer_font_size" name="kopa_theme_options_wdg_footer_font_size" class=" kopa-short-input">
            <label class="kopa-label"><?php _e('px', kopa_get_domain()); ?></label>
            
            <br>
            
            <p class="kopa-desc"><?php _e('Font Weight', kopa_get_domain()); ?></p>
            <label class="kopa-label"><?php _e('Widget title (footer) font weight:', kopa_get_domain()); ?> </label>
            <input type="text" value="<?php echo get_option('kopa_theme_options_wgd_footer_font_weight'); ?>" id="kopa_theme_options_wgd_footer_font_weight" name="kopa_theme_options_wgd_footer_font_weight" class=" kopa-short-input">            

        </div><!--kopa-element-box-->    

        <div class="kopa-element-box kopa-theme-options">            
            <p class="kopa-desc"><?php _e('Slider title font', kopa_get_domain()); ?></p>
            <?php
            $current_slider_title_font = get_option('kopa_theme_options_slider_font_family');
            $default_selected = 'selected="selected"';
            $display_none = ' display:none;';
            if ($current_slider_title_font):
                $default_selected = '';
                $display_none = '';
            endif;
            ?>
            <div class="font-sample" style="<?php echo $display_none;
               if ($current_slider_title_font) echo 'font-family:' . $google_fonts[$current_slider_title_font]['family'];
            ?>"><?php _e('Sample slider title font (footer)', kopa_get_domain()); ?></div>
            <select id="kopa_theme_options_slider_font_family" name="kopa_theme_options_slider_font_family" autocomplete="off" onchange="on_change_font(jQuery(this));">                
                <?php
                printf('<option value="" %1$s>%2$s</option>', $default_selected, '-- Default --');
                foreach ($google_fonts as $font_id => $font_value) {
                    if ($current_slider_footer_font && $current_slider_footer_font == $font_id) {
                        $selected = 'selected="selected"';
                    }
                    else
                        $selected = '';
                    printf('<option value="%1$s" %3$s>%2$s</option>', $font_id, $font_value['family'], $selected);
                }
                ?>
            </select>
            
            <p class="kopa-desc"><?php _e('Font size', kopa_get_domain()); ?></p>
            <label class="kopa-label"><?php _e('Slider title font size:', kopa_get_domain()); ?> </label>
            <input type="text" value="<?php echo get_option('kopa_theme_options_slider_font_size'); ?>" id="kopa_theme_options_slider_font_size" name="kopa_theme_options_slider_font_size" class=" kopa-short-input">
            <label class="kopa-label"><?php _e('px', kopa_get_domain()); ?></label>
            
            <br>
            
            <p class="kopa-desc"><?php _e('Font Weight', kopa_get_domain()); ?></p>
            <label class="kopa-label"><?php _e('Slider title font weight:', kopa_get_domain()); ?> </label>
            <input type="text" value="<?php echo get_option('kopa_theme_options_slider_font_weight'); ?>" id="kopa_theme_options_slider_font_weight" name="kopa_theme_options_slider_font_weight" class=" kopa-short-input">            

        </div><!--kopa-element-box-->      
    </div><!--tab-theme-skin-->    
</div><!--tab-container-->