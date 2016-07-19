<div id="tab-single-post" class="kopa-content-box tab-content tab-content-1">    


    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('Facebook Comment', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->
    <div class="kopa-box-body">
        <div class="kopa-element-box kopa-theme-options">  
            <span class="kopa-component-title"><?php _e('Status', kopa_get_domain()); ?></span>
            <?php
            $facebook_comment_status = array(
                'show' => __('Show', kopa_get_domain()),
                'hide' => __('Hide', kopa_get_domain())
            );
            
            $facebook_comment_name = "kopa_theme_options_post_facebook_comment_status";
            
            foreach ($facebook_comment_status as $value => $label):
                $facebook_comment_id = $facebook_comment_name . "_{$value}";
                ?>
                <label  for="<?php echo $facebook_comment_id; ?>" class="kopa-label-for-radio-button"><input type="radio" value="<?php echo $value; ?>" id="<?php echo $facebook_comment_id; ?>" name="<?php echo $facebook_comment_name; ?>" <?php echo ($value == get_option($facebook_comment_name, 'hide')) ? 'checked="checked"' : ''; ?>><?php echo $label; ?></label>
                <?php
            endforeach
            ?>
        </div>          
        
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Number of comments', kopa_get_domain()); ?></span>
            <input type="number" value="<?php echo get_option('kopa_theme_options_post_facebook_comment_limit', 5); ?>" id="kopa_theme_options_post_facebook_comment_limit" name="kopa_theme_options_post_facebook_comment_limit">
        </div>
    </div>

    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('About Author', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->
    <div class="kopa-box-body">
        <div class="kopa-element-box kopa-theme-options">            
            <?php
            $about_author_status = array(
                'show' => __('Show', kopa_get_domain()),
                'hide' => __('Hide', kopa_get_domain())
            );
            $about_author_name = "kopa_theme_options_post_about_author";
            foreach ($about_author_status as $value => $label):
                $about_author_id = $about_author_name . "_{$value}";
                ?>
                <label  for="<?php echo $about_author_id; ?>" class="kopa-label-for-radio-button"><input type="radio" value="<?php echo $value; ?>" id="<?php echo $about_author_id; ?>" name="<?php echo $about_author_name; ?>" <?php echo ($value == get_option($about_author_name, 'show')) ? 'checked="checked"' : ''; ?>><?php echo $label; ?></label>
                <?php
            endforeach
            ?>
        </div>
    </div>

    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('Sharing Buttons', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->
    <div class="kopa-box-body">
        <?php
        $sharing_buttons = array(
            'facebook' => __('Facebook', kopa_get_domain()),
            'twitter' => __('Twitter', kopa_get_domain()),
            'google' => __('Google', kopa_get_domain()),
            'linkedin' => __('LinkedIn', kopa_get_domain()),
            'pinterest' => __('Pinterest', kopa_get_domain()),
            'email' => __('Email', kopa_get_domain())
        );
        $sharing_button_status = array(
            'show' => __('Show', kopa_get_domain()),
            'hide' => __('Hide', kopa_get_domain())
        );

        foreach ($sharing_buttons as $slug => $title):
            ?>
            <div class="kopa-element-box kopa-theme-options">
                <span class="kopa-component-title"><?php echo $title; ?></span>                        
                <?php
                $sharing_button_name = "kopa_theme_options_post_sharing_button_{$slug}";
                foreach ($sharing_button_status as $value => $label):
                    $sharing_button_id = $sharing_button_name . "_{$value}";
                    ?>
                    <label  for="<?php echo $sharing_button_id; ?>" class="kopa-label-for-radio-button"><input type="radio" value="<?php echo $value; ?>" id="<?php echo $sharing_button_id; ?>" name="<?php echo $sharing_button_name; ?>" <?php echo ($value == get_option($sharing_button_name, 'show')) ? 'checked="checked"' : ''; ?>><?php echo $label; ?></label>
                    <?php
                endforeach
                ?>
            </div>
            <?php
        endforeach;
        ?>
    </div>

    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('Related Posts', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->

    <div class="kopa-box-body">

        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Get By', kopa_get_domain()); ?></span>
            <select class="" id="kopa_theme_options_post_related_get_by" name="kopa_theme_options_post_related_get_by">
                <?php
                $post_related_get_by = array(
                    'hide' => __('-- Hide --', kopa_get_domain()),
                    'post_tag' => __('Tag', kopa_get_domain()),
                    'category' => __('Category', kopa_get_domain())
                );
                foreach ($post_related_get_by as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value == get_option('kopa_theme_options_post_related_get_by', 'hide')) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </div>

        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Limit', kopa_get_domain()); ?></span>
            <input type="number" value="<?php echo get_option('kopa_theme_options_post_related_limit', 5); ?>" id="kopa_theme_options_post_related_limit" name="kopa_theme_options_post_related_limit">
        </div>
    </div><!--tab-theme-skin-->  

</div><!--tab-container-->