<?php
add_action('add_meta_boxes', 'kopa_service_meta_box_add');

function kopa_service_meta_box_add() {
    add_meta_box('kopa-service-edit', __( 'Services Meta Box', kopa_get_domain() ), 'kopa_meta_box_service_cb', 'services', 'normal', 'high');
}

function kopa_meta_box_service_cb($post) {
    // for upload custom icon
    $dir = get_template_directory_uri() . '/library/js';
    wp_enqueue_script('kopa-uploader', "{$dir}/uploader.js", array('jquery'), NULL, TRUE);

    $icon_class = get_post_meta($post->ID, 'icon_class');
    $service_external_page = get_post_meta( $post->ID, 'service_external_page', true );
    $service_static_page = get_post_meta( $post->ID, 'service_static_page', true );
    $service_percentage = (int) get_post_meta($post->ID, 'service_percentage', true);
    $service_custom_icon = get_post_meta($post->ID, 'service_custom_icon', true);
    wp_nonce_field('service_meta_box_nonce', 'service_meta_box_nonce');
    $kopa_icon = unserialize(KOPA_ICON);
    ?>
    <p class="kopa_option_box">
        <label for="service_external_page" class="kopa-desc"><?php _e( 'Link to external page:', kopa_get_domain() ); ?></label>
        <input type="url" name="service_external_page" id="service_external_page" value="<?php echo esc_attr( $service_external_page ); ?>" class="regular-text code">
    </p> 
    <p class="kopa_option_box">
        <label for="service_static_page" class="kopa-desc"><?php _e( 'Link to static page:', kopa_get_domain() ); ?></label>
        <?php wp_dropdown_pages( array( 'name' => 'service_static_page', 'show_option_none' => __( '&mdash; Select &mdash;', kopa_get_domain() ), 'option_none_value' => '0', 'selected' => $service_static_page ) ) ; ?>
    </p> 
    <p class="kopa_option_box">
        <label for="service_percentage" class="kopa-desc"><?php _e('Service Expertise', kopa_get_domain()); ?>:</label>
        <select autocomplete="off" name="service_percentage">
            <?php
            for ($i = 1; $i <= 100; $i++) {
                echo '<option value="' . $i . '"';
                if ($i === $service_percentage) {
                    echo ' selected="selected"';
                }
                echo '>' . $i . '</option>';
            }
            ?>
        </select><span>%</span>

    </p>   
    <p class="kopa_option_box">
        <div class="kopa-content-box tab-content">

            <!--tab-logo-favicon-icon-->
            <div class="kopa-box-head">
                <i class="icon-hand-right"></i>
                <span class="kopa-section-title"><?php _e('Custom Icon', kopa_get_domain()); ?></span>
            </div><!--kopa-box-head-->

            <div class="kopa-box-body">
                <div class="kopa-element-box kopa-theme-options">

                    <span class="kopa-component-title"><?php _e('Service Icon', kopa_get_domain()); ?></span>
                    <p class="kopa-desc"><?php _e('Upload your own icon.', kopa_get_domain()); ?></p>                         
                    <div class="clearfix">
                        <input class="left" type="text" value="<?php echo $service_custom_icon; ?>" id="service_custom_icon" name="service_custom_icon">
                        <button class="left btn btn-success upload_image_button" alt="service_custom_icon"><i class="icon-circle-arrow-up"></i><?php _e('Upload', kopa_get_domain()); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </p>   
    <p class="kopa_option_box">
        <label for="icon_class" class="kopa-desc"><?php _e('Choose icon:', kopa_get_domain()); ?></label><br>
    <ul class="select-icon clearfix">
        <?php
        foreach ($kopa_icon as $keys => $value) {
            echo '<li';
            if ($keys == $icon_class[0]) {
                echo ' class="selected"';
            }
            echo '><span lang="' . $keys . '" onclick="on_change_icon(jQuery(this));" class="icon-sample" data-icon="' . $value . '"></span></li>';
        }
        ?>
    </ul>
    <input type="hidden" autocomplete="off" name="icon_class" class="icon_class" value="<?php echo $icon_class[0]; ?>">
    </p>
    <?php
}

add_action('save_post', 'kopa_save_service_data');

function kopa_save_service_data($post_id) {

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    if (!isset($_POST['service_meta_box_nonce']) || !wp_verify_nonce($_POST['service_meta_box_nonce'], 'service_meta_box_nonce'))
        return;
    if (!current_user_can('edit_post'))
        $allowed = array(
            'a' => array(
                'href' => array()
            )
        );

    if (isset($_POST['icon_class'])) {
        update_post_meta($post_id, 'icon_class', wp_kses($_POST['icon_class'], $allowed));
    }

    if (isset($_POST['service_percentage'])) {
        update_post_meta($post_id, 'service_percentage', wp_kses($_POST['service_percentage'], $allowed));
    }
    
    if (isset($_POST['service_custom_icon'])) {
        update_post_meta($post_id, 'service_custom_icon', wp_kses($_POST['service_custom_icon'], $allowed));
    }

    if ( isset( $_POST['service_external_page'] ) ) {
        update_post_meta($post_id, 'service_external_page', $_POST['service_external_page']);
    }

    if ( isset( $_POST['service_static_page'] ) ) {
        update_post_meta($post_id, 'service_static_page', $_POST['service_static_page']);
    }
}