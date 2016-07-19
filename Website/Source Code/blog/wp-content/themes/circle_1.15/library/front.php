<?php
add_action('after_setup_theme', 'kopa_front_after_setup_theme');

function kopa_front_after_setup_theme() {
    add_theme_support('post-formats', array('gallery', 'audio', 'video', 'quote', 'aside'));
    add_theme_support('post-thumbnails');
    add_theme_support('loop-pagination');
    add_theme_support('automatic-feed-links');
    add_theme_support('editor_style');
    add_editor_style('editor-style.css');

    global $content_width;
    if (!isset($content_width))
        $content_width = 700;

    register_nav_menus(array(
        'main-nav' => __('Main Menu', kopa_get_domain())
    ));

    if (!is_admin()) {
        add_action('wp_enqueue_scripts', 'kopa_front_enqueue_scripts');
        add_action('wp_footer', 'kopa_footer');
        add_action('wp_head', 'kopa_head');
        add_filter('widget_text', 'do_shortcode');
        add_filter('the_category', 'kopa_the_category');
        add_filter('get_the_excerpt', 'kopa_get_the_excerpt');
        add_filter('post_class', 'kopa_post_class');
        add_filter('body_class', 'kopa_body_class');
        add_filter('wp_nav_menu_items', 'kopa_add_icon_home_menu', 10, 2);
        add_filter('comment_reply_link', 'kopa_comment_reply_link');
        add_filter('edit_comment_link', 'kopa_edit_comment_link');

        add_filter('wp_tag_cloud', 'kopa_tag_cloud');
    } else {
        add_action('show_user_profile', 'kopa_edit_user_profile');
        add_action('edit_user_profile', 'kopa_edit_user_profile');
        add_action('personal_options_update', 'kopa_edit_user_profile_update');
        add_action('edit_user_profile_update', 'kopa_edit_user_profile_update');
        add_filter('image_size_names_choose', 'kopa_image_size_names_choose');
    }

    kopa_add_image_sizes();
}

function kopa_tag_cloud($out) {
    $matches = array();
    $pattern = '/<a[^>]*?>([\\s\\S]*?)<\/a>/';
    preg_match_all($pattern, $out, $matches);

    $htmls = $matches[0];
    $texts = $matches[1];
    $new_html = '';
    for ($index = 0; $index < count($htmls); $index++) {
        $new_text = '<span class="kp-tag-left"></span>';
        $new_text.= '<span class="kp-tag-rounded"></span>';
        $new_text.= '<span class="kp-tag-text">' . $texts[$index] . '</span>';
        $new_text.= '<span class="kp-tag-right"></span>';

        $new_html.= preg_replace('#(<a.*?>).*?(</a>)#', '$1' . $new_text . '$2', $htmls[$index]);
    }
    return $new_html;
}

function kopa_comment_reply_link($link) {
    return str_replace('comment-reply-link', 'comment-reply-link small-button green-button', $link);
}

function kopa_edit_comment_link($link) {
    return str_replace('comment-edit-link', 'comment-edit-link small-button green-button', $link);
}

function kopa_post_class($classes) {
    if (is_single()) {
        $classes[] = 'entry-box';
        $classes[] = 'clearfix';
    }
    return $classes;
}

function kopa_body_class($classes) {
    $template_setting = kopa_get_template_setting();

    $classes[] = get_option('kopa_theme_options_footer_style', 'dark-footer');

    if (is_front_page()) {
        $classes[] = 'home-page';
    } else {
        $classes[] = 'sub-page';
    }

    switch ($template_setting['layout_id']) {
        case 'front-page-right-sidebar':
            $classes[] = 'kp-home-2';
            break;
        case 'front-page-full-width':
            $classes[] = 'kp-home-3';
            break;
        case 'blog-1-left-sidebar':
            $classes[] = 'kp-cat-1';
            $classes[] = 'kp-left-sidebar';
            break;
        case 'blog-1-right-sidebar':
            $classes[] = 'kp-cat-1';
            $classes[] = 'kp-right-sidebar';
            break;
        case 'blog-1-two-sidebar':
            $classes[] = 'kp-cat-1';
            $classes[] = 'two-sidebar';
            break;
        case 'blog-2':
            $classes[] = 'kp-cat-2';
            break;
        case 'blog-3-one-sidebar':
            $classes[] = 'kp-cat-3';
            break;
        case 'blog-3-two-sidebar':
            $classes[] = 'kp-cat-3';
            $classes[] = 'two-sidebar';
            break;
        case 'single-1-right-sidebar':
            $classes[] = 'kp-single-1';
            $classes[] = 'kp-right-sidebar';
            break;
        case 'single-1-left-sidebar':
            $classes[] = 'kp-single-1';
            $classes[] = 'kp-left-sidebar';
            break;
        case 'single-1-two-sidebar':
            $classes[] = 'kp-single-1';
            $classes[] = 'two-sidebar';
            break;
        case 'single-1-full-width':
            $classes[] = 'kp-single-1 kp-single-3';
            break;
        case 'single-2-right-sidebar':
            $classes[] = 'kp-single-2';
            $classes[] = 'kp-right-sidebar';
            break;
        case 'page-1-left-sidebar':
            $classes[] = 'kp-left-sidebar';
            break;
        case 'page-2-right-sidebar':
            $classes[] = 'kp-right-sidebar';
            break;
        case 'page-3-two-sidebar':
            $classes[] = 'two-sidebar';
            break;
        case 'portfolio-1':
            $classes[] = 'kp-pf-1col';
            break;
        case 'portfolio-2':
            $classes[] = 'kp-pf-2col';
            break;
        case 'portfolio-3':
            $classes[] = 'kp-pf-3col';
            break;
        case 'portfolio-details':
            $classes[] = 'kp-pf-detail';
            break;
        case 'search-left-sidebar':
            $classes[] = 'kp-left-sidebar';
            break;
        case 'search-right-sidebar':
            $classes[] = 'kp-right-sidebar';
            break;
        case 'search-two-sidebar':
            $classes[] = 'two-sidebar';
            break;
    }

    return $classes;
}

function kopa_footer() {
    wp_nonce_field('kopa_change_like_status', 'kopa_change_like_status_wpnonce', false);
    wp_nonce_field('kopa_set_view_count', 'kopa_set_view_count_wpnonce', false);
    wp_nonce_field('kopa_sharing_button', 'kopa_sharing_button_wpnonce');
    ?>
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id))
                return;
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
    <?php
}

function kopa_front_enqueue_scripts() {
    if (!is_admin()) {
        global $wp_styles, $is_IE;

        $dir = get_template_directory_uri();

        /* STYLESHEETs */

        wp_enqueue_style('kopa-reset', $dir . '/css/reset.css', array(), NULL);
        wp_enqueue_style('kopa-retina.less', $dir . '/css/retina.less', array(), NULL);
        wp_enqueue_style('kopa-bootstrap', $dir . '/css/bootstrap.css', array(), NULL);
        wp_enqueue_style('kopa-circle-hover', $dir . '/css/circle-hover.css', array(), NULL);
        wp_enqueue_style('kopa-flexslider', $dir . '/css/flexslider.css', array(), NULL);
        wp_enqueue_style('kopa-icoMoon', $dir . '/css/icoMoon.css', array(), NULL);
        wp_enqueue_style('kopa-prettyPhoto', $dir . '/css/prettyPhoto.css', array(), NULL);
        wp_enqueue_style('kopa-sequencejs-theme.modern-slide-in', $dir . '/css/sequencejs-theme.modern-slide-in.css', array(), NULL);
        wp_enqueue_style('kopa-superfish', $dir . '/css/superfish.css', array(), NULL);
        wp_enqueue_style('kopa-style', get_stylesheet_uri(), array(), NULL);
        wp_enqueue_style('kopa-bootstrap-responsive', $dir . '/css/bootstrap-responsive.css', array(), NULL);
        wp_enqueue_style('kopa-responsive', $dir . '/css/responsive.css', array(), NULL);
        wp_enqueue_style('kopa-jquery-pie-chart-style', $dir . '/css/jquery.easy-pie-chart.css', array(), NULL);


        $google_fonts = kopa_get_google_font_array();
        $current_heading_font = get_option('kopa_theme_options_heading_font_family', array(), NULL);
        $current_content_font = get_option('kopa_theme_options_content_font_family');
        $current_main_nav_font = get_option('kopa_theme_options_main_nav_font_family');
        $current_wdg_sidebar_font = get_option('kopa_theme_options_wdg_sidebar_font_family');
        $current_wdg_main_font = get_option('kopa_theme_options_wdg_main_font_family');
        $current_wdg_footer_font = get_option('kopa_theme_options_wdg_footer_font_family');
        $current_slider_font = get_option('kopa_theme_options_slider_font_family');

        $load_font_array = array();
        if ($current_heading_font) {
            array_push($load_font_array, $current_heading_font);
        }
        if ($current_content_font && !in_array($current_content_font, $load_font_array)) {
            array_push($load_font_array, $current_content_font);
        }
        if ($current_main_nav_font && !in_array($current_main_nav_font, $load_font_array)) {
            array_push($load_font_array, $current_main_nav_font);
        }
        if ($current_wdg_sidebar_font && !in_array($current_wdg_sidebar_font, $load_font_array)) {
            array_push($load_font_array, $current_wdg_sidebar_font);
        }


        if ($current_wdg_main_font && !in_array($current_wdg_main_font, $load_font_array)) {
            array_push($load_font_array, $current_wdg_main_font);
        }

        if ($current_wdg_footer_font && !in_array($current_wdg_footer_font, $load_font_array)) {
            array_push($load_font_array, $current_wdg_footer_font);
        }
        if ($current_slider_font && !in_array($current_slider_font, $load_font_array)) {
            array_push($load_font_array, $current_slider_font);
        }

        foreach ($load_font_array as $current_font) {

            if ($current_font != '') {
                $google_font_family = $google_fonts[$current_font]['family'];
                $temp_font_name = str_replace(' ', '+', $google_font_family);
                $font_url = 'http://fonts.googleapis.com/css?family=' . $temp_font_name . ':300,300italic,400,400italic,700,700italic&subset=latin';
                wp_enqueue_style('Google-Font-' . $temp_font_name, $font_url, array(), NULL);
            }
        }

        if ($is_IE) {
            wp_register_style('kopa-ie', $dir . '/css/ie.css', array(), NULL);
            wp_enqueue_style('kopa-ie');
            $wp_styles->add_data('kopa-ie', 'conditional', 'lt IE 9');

            wp_register_style('kopa-ie7', $dir . '/css/ie7.css', array(), NULL);
            wp_enqueue_style('kopa-ie7');
            $wp_styles->add_data('kopa-ie7', 'conditional', 'IE 7');

            wp_register_style('kopa-sequencejs-theme.modern-slide-in.ie', $dir . '/css/sequencejs-theme.modern-slide-in.ie.css', array(), NULL);
            $wp_styles->add_data('kopa-sequencejs-theme.modern-slide-in.ie', 'conditional', 'IE');
            wp_enqueue_style('kopa-sequencejs-theme.modern-slide-in.ie');
        }


        /* JAVASCRIPTs */
        wp_enqueue_script('kopa-google-api', 'http://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js', array(), NULL, TRUE);
        wp_enqueue_script('kopa-google-fonts', $dir . '/js/google-fonts.js', array('kopa-google-api'), NULL, TRUE);

        wp_enqueue_script('jquery');
        wp_localize_script('jquery', 'kopa_front_variable', kopa_front_localize_script());

        wp_enqueue_script('kopa-bootstrap', $dir . '/js/bootstrap.min.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-bootstrap-tooltip', $dir . '/js/bootstrap-tooltip.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-jquery-jflickrfeed', $dir . '/js/jflickrfeed.min.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-jquery-blackandwhite', $dir . '/js/jQuery.BlackAndWhite.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-jquery-caroufredsel', $dir . '/js/jquery.carouFredSel-6.0.4-packed.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-jquery-easing', $dir . '/js/jquery.easing.1.3.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-jquery-eislideshow', $dir . '/js/jquery.eislideshow.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-jquery-flexslider', $dir . '/js/jquery.flexslider-min.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-jquery-form', $dir . '/js/jquery.form.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-jquery-hoverdir', $dir . '/js/jquery.hoverdir.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-jquery-isotope', $dir . '/js/jquery.isotope.min.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-jquery-prettyphoto', $dir . '/js/jquery.prettyPhoto.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-jquery-sticky', $dir . '/js/jquery.sticky.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-jquery-tweet', $dir . '/js/jquery.tweet.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-jquery-validate', $dir . '/js/jquery.validate.min.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-jquery-sequence', $dir . '/js/sequence.jquery-min.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-less', $dir . '/js/less.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-modernizr-transitions', $dir . '/js/modernizr-transitions.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-retina', $dir . '/js/retina.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-jquery-superfish', $dir . '/js/superfish.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-jquery-pie-chart', $dir . '/js/jquery.easy-pie-chart.js', array('jquery'), NULL, TRUE);
        wp_enqueue_script('kopa-custom', $dir . '/js/custom.js', array('jquery'), NULL, TRUE);
        // send localization to frontend
        wp_localize_script('kopa-custom', 'kopa_custom_front_localization', kopa_custom_front_localization());

        if (is_single() || is_page()) {
            wp_enqueue_script('comment-reply');
        }
    }
}

function kopa_front_localize_script() {
    $kopa_variable = array(
        'ajax' => array(
            'url' => admin_url('admin-ajax.php')
        ),
        'template' => array(
            'post_id' => (is_singular()) ? get_queried_object_id() : 0
        )
    );
    return $kopa_variable;
}

/**
 * Send the translated texts to frontend
 * @package Circle
 * @since Circle 1.12
 */
function kopa_custom_front_localization() {
    $front_localization = array(
        'validate' => array(
            'form' => array(
                'submit' => __('Submit', kopa_get_domain()),
                'sending' => __('Sending...', kopa_get_domain())
            ),
            'name' => array(
                'required' => __('Please enter your name.', kopa_get_domain()),
                'minlength' => __('At least {0} characters required.', kopa_get_domain())
            ),
            'email' => array(
                'required' => __('Please enter your email.', kopa_get_domain()),
                'email' => __('Please enter a valid email.', kopa_get_domain())
            ),
            'url' => array(
                'required' => __('Please enter your url.', kopa_get_domain()),
                'url' => __('Please enter a valid url.', kopa_get_domain())
            ),
            'message' => array(
                'required' => __('Please enter a message.', kopa_get_domain()),
                'minlength' => __('At least {0} characters required.', kopa_get_domain())
            )
        )
    );

    return $front_localization;
}

function kopa_the_category($thelist) {
    return $thelist;
}

/* FUNCTION */

function kopa_print_page_title() {
    global $page, $paged;
    wp_title('|', TRUE, 'right');
    bloginfo('name');
    $site_description = get_bloginfo('description', 'display');
    if ($site_description && ( is_home() || is_front_page() ))
        echo " | $site_description";
    if ($paged >= 2 || $page >= 2)
        echo ' | ' . sprintf(__('Page %s', kopa_get_domain()), max($paged, $page));
}

function kopa_get_image_sizes() {
    $sizes = array(
        'kopa-image-size-0' => array(300, 300, TRUE, __('Small (Kopatheme)', kopa_get_domain())),
        'kopa-image-size-1' => array(385, 232, TRUE, __('Medium (Kopatheme)', kopa_get_domain())),
        'kopa-image-size-2' => array(795, 413, TRUE, __('Elastic Slide (Kopatheme)', kopa_get_domain())),
        'kopa-image-size-3' => array(518, 369, TRUE, __('Squence Slide (Kopatheme)', kopa_get_domain())),
        'kopa-image-size-4' => array(160, 30, TRUE, __('Client Logo (Kopatheme)', kopa_get_domain())),
        'kopa-image-size-5' => array(63, 63, TRUE, __('Staff Avatar (Kopatheme)', kopa_get_domain())),
        'kopa-image-size-6' => array(255, 255, TRUE, __('255x255 (Kopatheme)', kopa_get_domain())),
            //  'kopa-image-size-6' => array(260, 166, TRUE, __('Staff Avatar (Kopatheme)', kopa_get_domain())),
    );

    return apply_filters('kopa_get_image_sizes', $sizes);
}

function kopa_add_image_sizes() {
    $sizes = kopa_get_image_sizes();
    foreach ($sizes as $slug => $details) {
        add_image_size($slug, $details[0], $details[1], $details[2]);
    }
}

function kopa_image_size_names_choose($sizes) {
    $kopa_sizes = kopa_get_image_sizes();
    foreach ($kopa_sizes as $size => $image) {
        $width = ($image[0]) ? $image[0] : __('auto', kopa_get_domain());
        $height = ($image[1]) ? $image[1] : __('auto', kopa_get_domain());
        $sizes[$size] = $image[3] . " ({$width} x {$height})";
    }
    return $sizes;
}

function kopa_set_view_count($post_id) {
    $new_view_count = 0;
    $meta_key = 'kopa_' . kopa_get_domain() . '_total_view';

    $current_views = (int) get_post_meta($post_id, $meta_key, true);

    if ($current_views) {
        $new_view_count = $current_views + 1;
        update_post_meta($post_id, $meta_key, $new_view_count);
    } else {
        $new_view_count = 1;
        add_post_meta($post_id, $meta_key, $new_view_count);
    }
    return $new_view_count;
}

function kopa_get_view_count($post_id) {
    $key = 'kopa_' . kopa_get_domain() . '_total_view';
    return kopa_get_post_meta($post_id, $key, true, 'Int');
}

function kopa_breadcrumb() {
    if (is_main_query()) {
        global $post, $wp_query;

        $prefix = '<span>&nbsp;&raquo;&nbsp;</span>';
        $current_class = 'current-page';
        $description = '';
        $breadcrumb_before = '<div id="breadcrumb-wrapper"><div class="wrapper"><div class="row-fluid"><div class="span12"><div class="breadcrumb">';
        $breadcrumb_after = '</div></div></div></div></div>';
        $breadcrumb_home = '<a href="' . home_url() . '">' . __('Home', kopa_get_domain()) . '</a>';
        $breadcrumb = '';
        ?>

        <?php
        if (is_home()) {
            $breadcrumb.= $breadcrumb_home;
            $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, __('Latest News', kopa_get_domain()));
        } else if (is_tag()) {
            $breadcrumb.= $breadcrumb_home;

            $term = get_term(get_queried_object_id(), 'post_tag');
            $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, $term->name);
        } else if (is_category()) {
            $breadcrumb.= $breadcrumb_home;

            $category_id = get_queried_object_id();
            $terms_link = explode($prefix, substr(get_category_parents(get_queried_object_id(), TRUE, $prefix), 0, (strlen($prefix) * -1)));
            $n = count($terms_link);
            if ($n > 1) {
                for ($i = 0; $i < ($n - 1); $i++) {
                    $breadcrumb.= $prefix . $terms_link[$i];
                }
            }
            $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, get_the_category_by_ID(get_queried_object_id()));

            $description = category_description($category_id);
            if ($description)
                $breadcrumb.= '</div><div class="kp-intro">' . $description . '</div>';
        } else if (is_single()) {
            $breadcrumb.= $breadcrumb_home;

            $categories = get_the_category(get_queried_object_id());
            if ($categories) {
                foreach ($categories as $category) {
                    $breadcrumb.= $prefix . sprintf('<a href="%1$s">%2$s</a>', get_category_link($category->term_id), $category->name);
                }
            }

            $post_id = get_queried_object_id();
            $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</a>', $current_class, get_the_title($post_id));

            if (has_excerpt($post_id)) {
                $the_post = get_post($post_id);
                $description = $the_post->post_excerpt;
                $breadcrumb.= '</div><div class="kp-intro">' . $description . '</div>';
            }
        } else if (is_page()) {
            if (!is_front_page()) {
                if (has_excerpt(get_queried_object_id())) {
                    $page = get_post(get_queried_object_id());

                    if (property_exists($page, 'post_excerpt'))
                        $description = $page->post_excerpt;
                    else
                        $description = '';
                } else {
                    $description = '';
                } // endif has_excerpt

                $breadcrumb.= $breadcrumb_home;
                $post_ancestors = get_post_ancestors($post);
                if ($post_ancestors) {
                    $post_ancestors = array_reverse($post_ancestors);
                    foreach ($post_ancestors as $crumb)
                        $breadcrumb.= $prefix . sprintf('<a href="%1$s">%2$s</a>', get_permalink($crumb), get_the_title($crumb));
                }
                $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, get_the_title(get_queried_object_id()));
                if (!empty($description))
                    $breadcrumb.= '</div><div class="kp-intro">' . $description . '</div>';
            }
        } else if (is_year() || is_month() || is_day()) {
            $breadcrumb.= $breadcrumb_home;

            $m = get_query_var('m');
            $date = array('y' => NULL, 'm' => NULL, 'd' => NULL);

            if (strlen($m) >= 4)
                $date['y'] = substr($m, 0, 4);
            if (strlen($m) >= 6)
                $date['m'] = substr($m, 4, 2);
            if (strlen($m) >= 8)
                $date['d'] = substr($m, 6, 2);

            if ($date['y'])
                if (is_year()) {
                    $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, $date['y']);
                } else {
                    $breadcrumb.= $prefix . sprintf('<a href="%1$s">%2$s</a>', get_year_link($date['y']), $date['y']);
                }
            if ($date['m'])
                if (is_month()) {
                    $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, date('F', mktime(0, 0, 0, $date['m'])));
                } else {
                    $breadcrumb.= $prefix . sprintf('<a href="%1$s">%2$s</a>', get_month_link($date['y'], $date['m']), date('F', mktime(0, 0, 0, $date['m'])));
                }
            if ($date['d'])
                if (is_day()) {
                    $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, $date['d']);
                } else {
                    $breadcrumb.= $prefix . sprintf('<a href="%1$s">%2$s</a>', get_day_link($date['y'], $date['m'], $date['d']), $date['d']);
                }
        } else if (is_search()) {
            $breadcrumb.= $breadcrumb_home;

            $s = get_search_query();
            $c = $wp_query->found_posts;

            $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</a>', $current_class, __('Search', kopa_get_domain()));
            $description = sprintf(__('Your search for "<span>%1$s</span>" returned "<span>%2$s</span>" posts', kopa_get_domain()), $s, $c);
            $breadcrumb.= '</div><div class="kp-intro">' . $description . '</div>';
        } else if (is_author()) {
            $breadcrumb.= $breadcrumb_home;
            $author_id = get_queried_object_id();
            $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</a>', $current_class, sprintf(__('Posts created by %1$s', kopa_get_domain()), get_the_author_meta('display_name', $author_id)));
        } else if (is_404()) {
            $breadcrumb.= $breadcrumb_home;
            $breadcrumb.= $prefix . sprintf('<span class="%1$s">%2$s</span>', $current_class, __('Page not found', kopa_get_domain()));
        }

        if ($breadcrumb)
            echo apply_filters('kopa_breadcrumb', $breadcrumb_before . $breadcrumb . $breadcrumb_after);
    }
}

function kopa_get_related_articles() {
    if (is_single()) {
        $get_by = get_option('kopa_theme_options_post_related_get_by', 'hide');
        if ('hide' != $get_by) {
            $limit = (int) get_option('kopa_theme_options_post_related_limit', 5);
            if ($limit > 0) {
                global $post;
                $taxs = array();
                if ('category' == $get_by) {
                    $cats = get_the_category(($post->ID));
                    if ($cats) {
                        $ids = array();
                        foreach ($cats as $cat) {
                            $ids[] = $cat->term_id;
                        }
                        $taxs [] = array(
                            'taxonomy' => 'category',
                            'field' => 'id',
                            'terms' => $ids
                        );
                    }
                } else {
                    $tags = get_the_tags($post->ID);
                    if ($tags) {
                        $ids = array();
                        foreach ($tags as $tag) {
                            $ids[] = $tag->term_id;
                        }
                        $taxs [] = array(
                            'taxonomy' => 'post_tag',
                            'field' => 'id',
                            'terms' => $ids
                        );
                    }
                }

                if ($taxs) {
                    $related_args = array(
                        'tax_query' => $taxs,
                        'post__not_in' => array($post->ID),
                        'posts_per_page' => $limit
                    );
                    $related_posts = new WP_Query($related_args);
                    $carousel_id = ($related_posts->post_count > 3) ? 'related-widget' : 'related-widget-no-carousel';
                    if ($related_posts->have_posts()):
                        ?>
                        <div class="related-article">
                            <h3><span><?php _e('Related articles', kopa_get_domain()); ?></span></h3>                            
                            <div class="list-carousel responsive">
                                <ul id="<?php echo $carousel_id; ?>">
                                    <?php
                                    while ($related_posts->have_posts()):
                                        $related_posts->the_post();
                                        $post_url = get_permalink();
                                        $post_title = get_the_title();
                                        ?>       
                                        <li style="width: 245px;">
                                            <article class="entry-item clearfix">
                                                <div class="entry-thumb hover-effect">

                                                    <?php
                                                    switch (get_post_format()) :

                                                        // video post format
                                                        case 'video':
                                                            $video = kopa_content_get_video(get_the_content());
                                                            if (!empty($video)) :
                                                                $video = $video[0];
                                                                $video_thumbnail = kopa_get_video_thumbnails_url($video['type'], $video['url']);
                                                                ?>

                                                                <div class="mask">
                                                                    <a class="link-detail" href="<?php echo $video['url']; ?>" rel="prettyPhoto" data-icon="&#xe022;"></a>
                                                                </div>

                                                                <?php
                                                                if (has_post_thumbnail())
                                                                    the_post_thumbnail('kopa-image-size-1');
                                                                else
                                                                    printf('<img src="%1$s">', $video_thumbnail);

                                                            endif; // ! empty( $video )
                                                            break;

                                                        // gallery post format
                                                        case 'gallery':
                                                            $gallery = kopa_content_get_gallery(get_the_content());
                                                            $shortcode = $gallery[0]['shortcode'];

                                                            // get gallery string ids
                                                            preg_match_all('/ids=\"(?:\d+,*)+\"/', $shortcode, $gallery_string_ids);
                                                            $gallery_string_ids = $gallery_string_ids[0][0];

                                                            // get array of image id
                                                            preg_match_all('/\d+/', $gallery_string_ids, $gallery_ids);
                                                            $gallery_ids = $gallery_ids[0];

                                                            $first_image_id = array_shift($gallery_ids);
                                                            $first_image_src = wp_get_attachment_image_src($first_image_id, 'kopa-image-size-2');

                                                            $slug = 'gallery-' . get_the_ID();
                                                            ?>
                                                            <div class="mask">
                                                                <a class="link-detail" href="<?php echo $first_image_src[0]; ?>" rel="prettyPhoto[<?php echo $slug; ?>]" data-icon="&#xe01d;"></a>
                                                            </div>
                                                            <?php
                                                            foreach ($gallery_ids as $gallery_id) :
                                                                $image_src = wp_get_attachment_image_src($gallery_id, 'kopa-image-size-2');
                                                                ?>
                                                                <a style="display: none" href="<?php echo $image_src[0]; ?>" rel="prettyPhoto[<?php echo $slug; ?>]"></a>
                                                                <?php
                                                            endforeach;

                                                            if (has_post_thumbnail())
                                                                the_post_thumbnail('kopa-image-size-1');
                                                            else
                                                                printf('<img src="%1$s">', get_template_directory_uri() . '/images/kopa-image-size-1.png');

                                                            break;

                                                        // default post format
                                                        default:
                                                            if (get_post_format() == 'quote')
                                                                $data_icon = '&#xe075;';
                                                            elseif (get_post_format() == 'audio')
                                                                $data_icon = '&#xe020;';
                                                            else
                                                                $data_icon = '&#xe0c2;';
                                                            ?>
                                                            <div class="mask">
                                                                <a class="link-detail" data-icon="<?php echo $data_icon; ?>" href="<?php the_permalink(); ?>"></a>
                                                            </div>
                                                            <?php
                                                            if (has_post_thumbnail())
                                                                the_post_thumbnail('kopa-image-size-1');
                                                            else
                                                                printf('<img src="%1$s">', get_template_directory_uri() . '/images/kopa-image-size-1.png');

                                                            break;

                                                    endswitch;
                                                    ?>
                                                </div>
                                                <div class="entry-content">
                                                    <h3 class="entry-title"><a href="<?php echo $post_url; ?>"><?php echo $post_title; ?></a></h3>
                                                    <p class="entry-meta">
                                                        <span class="entry-date"><span class="icon-clock-4 entry-icon" aria-hidden="true"></span><span><?php echo get_the_date(); ?></span></span>
                                                        <span class="entry-comment"><span class="icon-bubbles-4 entry-icon" aria-hidden="true"></span><?php comments_popup_link(__('Reply', kopa_get_domain()), __('1', kopa_get_domain()), __('%', kopa_get_domain()), '', __('Comments Off', kopa_get_domain())); ?></span>
                                                    </p>                                                    
                                                </div><!--entry-content-->
                                            </article><!--entry-item-->
                                        </li>
                                        <?php
                                    endwhile;
                                    ?>
                                </ul>
                                <div class="clearfix"></div>
                                <?php if ($related_posts->post_count > 3): ?>
                                    <div class="carousel-nav clearfix">
                                        <a id="prev-1" class="carousel-prev" href="#">&lt;</a>
                                        <a id="next-1" class="carousel-next" href="#">&gt;</a>
                                    </div><!--end:carousel-nav-->
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php
                    endif;
                    wp_reset_postdata();
                }
            }
        }
    }
}

function kopa_get_facebook_comment() {
    $status = get_option('kopa_theme_options_post_facebook_comment_status', 'hide');
    if ('show' == $status) {
        global $post, $content_width;
        $limit = (int) get_option('kopa_theme_options_post_facebook_comment_limit', 5);
        $colors_scheme = 'light';
        ?>
        <div class="fb-comments" data-href="<?php echo get_permalink($post->ID); ?>" data-colorscheme="<?php echo $colors_scheme; ?>" data-numposts="<?php echo $limit; ?>" data-width="<?php echo $content_width; ?>"></div>
        <?php
    }
}

function kopa_get_related_portfolio() {
    if (is_singular('portfolio')) {
        $get_by = get_option('kopa_theme_options_portfolio_related_get_by', 'hide');
        if ('hide' != $get_by) {
            $limit = (int) get_option('kopa_theme_options_portfolio_related_limit', 3);
            if ($limit > 0) {
                global $post;
                $taxs = array();

                $terms = wp_get_post_terms($post->ID, $get_by);
                if ($terms) {
                    $ids = array();
                    foreach ($terms as $term) {
                        $ids[] = $term->term_id;
                    }
                    $taxs [] = array(
                        'taxonomy' => $get_by,
                        'field' => 'id',
                        'terms' => $ids
                    );
                }

                if ($taxs) {
                    $related_args = array(
                        'post_type' => 'portfolio',
                        'tax_query' => $taxs,
                        'post__not_in' => array($post->ID),
                        'posts_per_page' => $limit
                    );
                    $related_portfolios = new WP_Query($related_args);
                    if ($related_portfolios->have_posts()):
                        $index = 1;
                        ?>
                        <div class="more-pf-box">
                            <h3><?php _e('Related Project', kopa_get_domain()); ?></h3>
                            <ul class="pf-box clearfix">
                                <?php
                                while ($related_portfolios->have_posts()):
                                    $related_portfolios->the_post();
                                    $post_url = get_permalink();
                                    $post_title = get_the_title();
                                    ?>   
                                    <li class="<?php echo "portfolio-box-position-{$index}"; ?>">
                                        <article>
                                            <div class="bwWrapper" >
                                                <?php
                                                if (has_post_thumbnail()):
                                                    the_post_thumbnail('kopa-image-size-1');
                                                else:
                                                    printf('<img src="%1$s">', get_template_directory_uri() . '/images/kopa-image-size-1.png');
                                                endif;
                                                ?>
                                                <a href="<?php echo $post_url; ?>" class="kp-pf-detail">+</a>
                                            </div>
                                            <div class="pf-info">
                                                <span class="entry-view"><span class="icon-eye-4 entry-icon"></span><?php printf(__('%1$s Views', kopa_get_domain()), kopa_get_view_count($post->ID)); ?></span>
                                                <span class="entry-like"><a class="icon-heart-3 entry-icon <?php echo 'kopa_like_button_' . kopa_get_like_permission($post->ID); ?>" href="#" onclick="return kopa_like_button_click(jQuery(this),<?php echo $post->ID; ?>);"></a><span class="kopa_like_count"><?php printf(__('%1$s Likes', kopa_get_domain()), kopa_get_like_count($post->ID)); ?></span></span>
                                                <a class="pf-name" href="<?php echo $post_url; ?>"><?php echo $post_title; ?></a>
                                            </div>                                                
                                        </article><!--element-->
                                    </li>
                                    <?php
                                    $index++;
                                    if (4 == $index) {
                                        $index = 1;
                                        echo '<li class="portfolio-box-space"></li>';
                                    }
                                endwhile;
                                ?>
                            </ul>
                        </div>
                        <?php
                    endif;
                    wp_reset_postdata();
                }
            }
        }
    }
}

function kopa_get_socials_link() {

    $display_facebook_sharing_button = get_option('kopa_theme_options_post_sharing_button_facebook', 'show');
    $display_twitter_sharing_button = get_option('kopa_theme_options_post_sharing_button_twitter', 'show');
    $display_google_sharing_button = get_option('kopa_theme_options_post_sharing_button_google', 'show');
    $display_linkedin_sharing_button = get_option('kopa_theme_options_post_sharing_button_linkedin', 'show');
    $display_pinterest_sharing_button = get_option('kopa_theme_options_post_sharing_button_pinterest', 'show');
    $display_email_sharing_button = get_option('kopa_theme_options_post_sharing_button_email', 'show');

    if ($display_facebook_sharing_button == 'show' ||
            $display_twitter_sharing_button == 'show' ||
            $display_google_sharing_button == 'show' ||
            $display_linkedin_sharing_button == 'show' ||
            $display_pinterest_sharing_button == 'show' ||
            $display_email_sharing_button == 'show') :

        $title = htmlspecialchars(get_the_title());
        $email_subject = htmlspecialchars(get_bloginfo('name')) . ': ' . $title;
        $email_body = __('I recommend this page: ', kopa_get_domain()) . $title . __('. You can read it on: ', kopa_get_domain()) . get_permalink();

        if (has_post_thumbnail()) {
            $post_thumbnail_id = get_post_thumbnail_id(get_the_ID());
            $thumbnail = wp_get_attachment_image_src($post_thumbnail_id);
        }
        ?>
        <div class="socials-link-container clearfix">
            <ul class="socials-link clearfix">

                <?php if ($display_facebook_sharing_button == 'show') : ?>
                    <li class="facebook-icon"><a href="http://www.facebook.com/share.php?u=<?php echo urlencode(get_permalink()); ?>" title="Facebook" target="_blank"><span class="icon-facebook" aria-hidden="true"></span></a></li>
                <?php endif; ?>

                <?php if ($display_twitter_sharing_button == 'show') : ?>
                    <li class="twitter-icon"><a href="http://twitter.com/home?status=<?php echo get_the_title() . ':+' . urlencode(get_permalink()); ?>" title="Twitter" target="_blank"><span class="icon-twitter" aria-hidden="true"></span></a></li>
                <?php endif; ?>

                <?php if ($display_google_sharing_button == 'show') : ?>
                    <li class="google-icon"><a href="https://plus.google.com/share?url=<?php echo urlencode(get_permalink()); ?>" title="Google" target="_blank"><span class="icon-google-plus" aria-hidden="true"></span></a></li>
                <?php endif; ?>

                <?php if ($display_linkedin_sharing_button == 'show') : ?>
                    <li class="linkedin-icon"><a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo urlencode(get_permalink()); ?>&amp;title=<?php echo urlencode(get_the_title()); ?>&amp;summary=<?php echo urlencode(get_the_excerpt()); ?>&amp;source=<?php echo urlencode(get_bloginfo('name')); ?>" title="Linkedin" target="_blank"><span class="icon-linkedin" aria-hidden="true"></span></a></li>
                <?php endif; ?>

                <?php if ($display_pinterest_sharing_button == 'show') : ?>
                    <li class="pinterest-icon"><a href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink()); ?>&amp;media=<?php echo isset($thumbnail[0]) ? urlencode($thumbnail[0]) : ''; ?>&amp;description=<?php the_title(); ?>" title="Pinterest" target="_blank"><span class="icon-pinterest" aria-hidden="true"></span></a></li>
                <?php endif; ?>

                <?php if ($display_email_sharing_button == 'show') : ?>
                    <li class="facebook-icon"><a href="mailto:?subject=<?php echo rawurlencode($email_subject); ?>&amp;body=<?php echo rawurlencode($email_body); ?>" title="Email" target="_self"><span class="icon-envelope" aria-hidden="true"></span></a></li>
                <?php endif; ?>

            </ul>
        </div>
        <?php
    endif;
}

function kopa_get_about_author() {
    if ('show' == get_option('kopa_theme_options_post_about_author', 'hide')) {
        global $post;
        $user_id = $post->post_author;
        $description = get_the_author_meta('description', $user_id);
        $email = get_the_author_meta('user_email', $user_id);
        $name = get_the_author_meta('display_name', $user_id);
        $link = trim(get_the_author_meta('user_url', $user_id));
        ?>

        <div class="about-author clearfix">
            <a class="avatar-thumb" href="<?php echo $link; ?>"><?php echo get_avatar($email, 90); ?></a>                                            
            <div class="author-content">
                <header class="clearfix">
                    <h4><?php _e('Posted by:', kopa_get_domain()); ?></h4>                    
                    <a class="author-name" href="<?php echo $link; ?>"><?php echo $name; ?></a>
                    <?php
                    $social_links['facebook'] = get_user_meta($user_id, 'facebook', true);
                    $social_links['twitter'] = get_user_meta($user_id, 'twitter', true);
                    $social_links['google-plus'] = get_user_meta($user_id, 'google-plus', true);

                    if ($social_links['facebook'] || $social_links['twitter'] || $social_links['google-plus']):
                        ?>                  
                        <ul class="clearfix social-link">                      
                            <li><?php _e('Follow:', kopa_get_domain()); ?></li>

                            <?php if ($social_links['facebook']): ?>
                                <li class="facebook-icon"><a target="_blank" data-icon="&#xe168;" title="<?php _e('Facebook', kopa_get_domain()); ?>" href="<?php echo $social_links['facebook']; ?>"></a></li>
                            <?php endif; ?>

                            <?php if ($social_links['twitter']): ?>
                                <li class="twitter-icon"><a target="_blank" data-icon="&#xe16c;" title="<?php _e('Twitter', kopa_get_domain()); ?>" class="twitter" href="<?php echo $social_links['twitter']; ?>"></a></li>
                            <?php endif; ?>

                            <?php if ($social_links['google-plus']): ?>
                                <li class="gplus-icon"><a target="_blank" data-icon="&#xe163;" title="<?php _e('Google+', kopa_get_domain()); ?>" class="twitter" href="<?php echo $social_links['google-plus']; ?>"></a></li>
                            <?php endif; ?>                            

                        </ul><!--social-link-->
                    <?php endif; ?>
                </header>
                <div><?php echo $description; ?></div>
            </div><!--author-content-->
        </div><!--about-author-->
        <?php
    }
}

function kopa_edit_user_profile($user) {
    ?>   
    <table class="form-table">
        <tr>
            <th><label for="facebook"><?php _e('Facebook', kopa_get_domain()); ?></label></th>
            <td>
                <input type="url" name="facebook" id="facebook" value="<?php echo esc_attr(get_the_author_meta('facebook', $user->ID)); ?>" class="regular-text" /><br />
                <span class="description"><?php _e('Please enter your Facebook URL', kopa_get_domain()); ?></span>
            </td>
        </tr>
        <tr>
            <th><label for="twitter"><?php _e('Twitter', kopa_get_domain()); ?></label></th>
            <td>
                <input type="url" name="twitter" id="twitter" value="<?php echo esc_attr(get_the_author_meta('twitter', $user->ID)); ?>" class="regular-text" /><br />
                <span class="description"><?php _e('Please enter your Twitter URL', kopa_get_domain()); ?></span>
            </td>
        </tr>       
        <tr>
            <th><label for="google-plus"><?php _e('Google Plus', kopa_get_domain()); ?></label></th>
            <td>
                <input type="url" name="google-plus" id="google-plus" value="<?php echo esc_attr(get_the_author_meta('google-plus', $user->ID)); ?>" class="regular-text" /><br />
                <span class="description"><?php _e('Please enter your Google Plus URL', kopa_get_domain()); ?></span>
            </td>
        </tr>
    </table>
    <?php
}

function kopa_edit_user_profile_update($user_id) {
    if (!current_user_can('edit_user', $user_id))
        return false;

    update_user_meta($user_id, 'facebook', $_POST['facebook']);
    update_user_meta($user_id, 'twitter', $_POST['twitter']);
    update_user_meta($user_id, 'google-plus', $_POST['google-plus']);
}

function kopa_get_the_excerpt($excerpt) {
    if (is_main_query()) {
        if (is_search()) {
            $keys = implode('|', explode(' ', get_search_query()));
            return preg_replace('/(' . $keys . ')/iu', '<span class="kopa-search-keyword">\0</span>', $excerpt);
        } else {
            return $excerpt;
        }
    }
}

function kopa_get_template_setting() {
    $kopa_setting = get_option('kopa_setting');
    $setting = array();

    if (is_home()) {
        $setting = $kopa_setting['home'];
    } else if (is_archive()) {
        if (is_category() || is_tag()) {
            $setting = get_option("kopa_category_setting_" . get_queried_object_id(), $kopa_setting['taxonomy']);
        } else if (is_tax('portfolio_project') || is_tax('portfolio_tag')) {
            $setting = $kopa_setting['portfolio'];
        } else {
            $setting = get_option("kopa_category_setting_" . get_queried_object_id(), $kopa_setting['archive']);
        }
    } else if (is_singular()) {
        if (is_singular('post')) {
            $setting = get_option("kopa_post_setting_" . get_queried_object_id(), $kopa_setting['post']);
        } else if (is_singular('portfolio')) {
            $setting = $kopa_setting['portfolio-details'];
        } else if (is_page()) {

            $setting = get_option("kopa_page_setting_" . get_queried_object_id());
            if (!$setting) {
                if (is_front_page()) {
                    $setting = $kopa_setting['front-page'];
                } else {
                    $setting = $kopa_setting['page'];
                }
            }
        } else {
            $setting = $kopa_setting['post'];
        }
    } else if (is_404()) {
        $setting = $kopa_setting['_404'];
    } else if (is_search()) {
        $setting = $kopa_setting['search'];
    }

    return $setting;
}

function kopa_content_get_gallery($content, $enable_multi = false) {
    return kopa_content_get_media($content, $enable_multi, array('gallery'));
}

function kopa_content_get_video($content, $enable_multi = false) {
    return kopa_content_get_media($content, $enable_multi, array('vimeo', 'youtube'));
}

function kopa_content_get_audio($content, $enable_multi = false) {
    return kopa_content_get_media($content, $enable_multi, array('audio', 'soundcloud'));
}

function kopa_content_get_media($content, $enable_multi = false, $media_types = array()) {
    $media = array();
    $regex_matches = '';
    $regex_pattern = get_shortcode_regex();
    preg_match_all('/' . $regex_pattern . '/s', $content, $regex_matches);
    foreach ($regex_matches[0] as $shortcode) {
        $regex_matches_new = '';
        preg_match('/' . $regex_pattern . '/s', $shortcode, $regex_matches_new);

        if (in_array($regex_matches_new[2], $media_types)) :
            $media[] = array(
                'shortcode' => $regex_matches_new[0],
                'type' => $regex_matches_new[2],
                'url' => $regex_matches_new[5]
            );
            if (false == $enable_multi) {
                break;
            }
        endif;
    }

    return $media;
}

function kopa_get_video_thumbnails_url($type, $url) {
    $thubnails = '';
    $matches = array();
    if ('youtube' === $type) {
        preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $url, $matches);
        $file_url = "http://gdata.youtube.com/feeds/api/videos/" . $matches[0] . "?v=2&alt=jsonc";
        $json = json_decode(file_get_contents($file_url));
        $thubnails = $json->data->thumbnail->hqDefault;
    } else if ('vimeo' === $type) {
        preg_match_all('#(http://vimeo.com)/([0-9]+)#i', $url, $matches);
        $imgid = $matches[2][0];
        $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$imgid.php"));
        $thubnails = $hash[0]['thumbnail_large'];
    }
    return $thubnails;
}

function kopa_get_client_IP() {
    $IP = NULL;

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        //check if IP is from shared Internet
        $IP = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //check if IP is passed from proxy
        $ip_array = explode(",", $_SERVER['HTTP_X_FORWARDED_FOR']);
        $IP = trim($ip_array[count($ip_array) - 1]);
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        //standard IP check
        $IP = $_SERVER['REMOTE_ADDR'];
    }
    return $IP;
}

function kopa_get_post_meta($pid, $key = '', $single = false, $type = 'String', $default = '') {
    $data = get_post_meta($pid, $key, $single);
    switch ($type) {
        case 'Int':
            $data = (int) $data;
            return ($data >= 0) ? $data : $default;
            break;
        default:
            return ($data) ? $data : $default;
            break;
    }
}

function kopa_get_like_permission($pid) {
    $permission = 'disable';

    $key = 'kopa_' . kopa_get_domain() . '_like_by_' . kopa_get_client_IP();
    $is_voted = kopa_get_post_meta($pid, $key, true, 'Int');

    if (!$is_voted)
        $permission = 'enable';

    return $permission;
}

function kopa_get_like_count($pid) {
    $key = 'kopa_' . kopa_get_domain() . '_total_like';
    return kopa_get_post_meta($pid, $key, true, 'Int');
}

function kopa_total_post_count_by_month($month, $year) {
    $args = array(
        'monthnum' => (int) $month,
        'year' => (int) $year,
    );
    $the_query = new WP_Query($args);
    return $the_query->post_count;
    ;
}

function kopa_head() {
    $logo_margin_top = get_option('kopa_theme_options_logo_margin_top', 0);
    $logo_margin_left = get_option('kopa_theme_options_logo_margin_left', 0);
    $logo_margin_right = get_option('kopa_theme_options_logo_margin_right', 0);
    $logo_margin_bottom = get_option('kopa_theme_options_logo_margin_bottom', 0);
    $kopa_theme_options_color_code = get_option('kopa_theme_options_color_code', '#91C448');

    echo "<style>
        #logo-image{
            margin-top:{$logo_margin_top}px;
            margin-left:{$logo_margin_left}px;
            margin-right:{$logo_margin_right}px;
            margin-bottom:{$logo_margin_bottom}px;
        } 

            .more-link {
                color: {$kopa_theme_options_color_code};
            }
            .load-more-gallery {
                background-color:{$kopa_theme_options_color_code};
            }
            .time-to-filter .timeline-filter {
                color:{$kopa_theme_options_color_code};
            }
            .kp-filter ul.ss-links {
                background-color:{$kopa_theme_options_color_code};
            }
            .sidebar .widget .entry-category a:hover,
            .sidebar .widget .entry-comment a:hover {
                color:{$kopa_theme_options_color_code};
            }
            .sidebar .widget .kp-latest-comment li .entry-content .comment-name{
                color: {$kopa_theme_options_color_code};
            }
            .list-container-2 ul li.active a, 
            .list-container-2 ul li:hover a,
            .list-container-3 ul li.active a, 
            .list-container-3 ul li:hover a {
                border-top:1px solid {$kopa_theme_options_color_code};
                color: #333;
                text-decoration: none;
            }
            .tweet_list li .tweet_time a:hover,
            .tweet_list li .tweet_text a {
                color:{$kopa_theme_options_color_code};
            }
            .widget_archive ul li a:hover,
            .widget_recent_comments ul li a:hover,
            .widget_recent_entries ul li a:hover,
            .widget_rss ul li a:hover,
            .widget_meta ul li a:hover,
            .widget_categories ul li a:hover, 
            .widget_archive ul li a:hover{
                color:{$kopa_theme_options_color_code};
            }
            .widget_tag_cloud a:hover {
                background-color:{$kopa_theme_options_color_code};
            }
            .tagcloud a:hover {
                background-color:{$kopa_theme_options_color_code};
            }
            .tagcloud a:hover .kp-tag-left {
                border-right: 13px solid {$kopa_theme_options_color_code};
            }
            #bottom-sidebar .kp-latest-comment li a.comment-name {
                color:{$kopa_theme_options_color_code};
            }
            #back-top a {
                background-color:{$kopa_theme_options_color_code};
            }
            .dark-footer #bottom-sidebar {
                border-top:4px solid {$kopa_theme_options_color_code};
            }
            .dark-footer #bottom-sidebar .tagcloud a:hover {
                background-color:{$kopa_theme_options_color_code};
            }
            .dark-footer #bottom-sidebar .tagcloud a:hover .kp-tag-left {
                border-right: 13px solid {$kopa_theme_options_color_code};
            }
            .isotop-header #filters {
                background-color: {$kopa_theme_options_color_code};
            }
            .m-wrapper {
                background-color:{$kopa_theme_options_color_code};
            }
            .m-isotop-header #m-pf-filters {
                background-color: {$kopa_theme_options_color_code};
            }
            #pf-filters li a.selected {
                background-color:{$kopa_theme_options_color_code};
            }
            .pagination ul > li:hover > a,
            .pagination ul > li span.current {
                background-color:{$kopa_theme_options_color_code};
            }
            .pagination ul > li span.dots {
                color:{$kopa_theme_options_color_code};
            }
            .entry-view span.entry-icon,
            .entry-tag span.entry-icon,
            .entry-category span.entry-icon {
                color:{$kopa_theme_options_color_code};
            }
            .entry-like a {
                color:{$kopa_theme_options_color_code};
            }
            #pf-items .pf-info .pf-name:hover,
            .pf-box li .pf-info .pf-name:hover {
                color:{$kopa_theme_options_color_code};
            }

            .pf-des-1col header .pf-name:hover {
                color:{$kopa_theme_options_color_code};
            }
            #breadcrumb-wrapper {
                background-color:{$kopa_theme_options_color_code};
            }
            #toggle-view li span {
                background-color:{$kopa_theme_options_color_code};
            }
            .accordion-title span {
                background-color:{$kopa_theme_options_color_code};
            }
            .article-list li article .entry-title a:hover {
                color:{$kopa_theme_options_color_code};
            }
            .article-list li article .entry-comment a:hover,
            .article-list li article .entry-category a:hover,
            .article-list li article .entry-author a:hover {
                color:{$kopa_theme_options_color_code};
            }
            .article-list-wrapper .article-bullet {
                background-color:{$kopa_theme_options_color_code};
            }
            .article-list-wrapper .article-list-line {
                background-color:{$kopa_theme_options_color_code};
            }
            .kp-cat-2 .article-list li article .entry-meta-box .entry-meta-circle {
                background-color:{$kopa_theme_options_color_code};
            }
            .kp-cat-2 .article-list li article .entry-meta-box .entry-meta-icon {
                background-color:{$kopa_theme_options_color_code};
            }
            .entry-date span.entry-icon,
            .entry-author span.entry-icon,
            .entry-category span.entry-icon,
            .entry-comment span.entry-icon {
                color:{$kopa_theme_options_color_code};
            }
            .entry-comment a:hover,
            .entry-category a:hover,
            .entry-author a:hover,
            .entry-tag a:hover {
                color:{$kopa_theme_options_color_code};
            }
            .entry-box .pagination li a:hover {
                color:{$kopa_theme_options_color_code};
            }
            .entry-box footer .prev-post {
                float:left;
            }
            .entry-box footer .next-post {
                float:right;
            }
            .entry-box footer .prev-post a,
            .entry-box footer .next-post a {
                color:{$kopa_theme_options_color_code};
            }
            .about-author header .author-name {
                color:{$kopa_theme_options_color_code};
            }
            .about-author header .social-link li a:hover {
                color:{$kopa_theme_options_color_code};
            }
            .kopa-comment-pagination a:hover {
                color:{$kopa_theme_options_color_code};
            }
            .kopa-comment-pagination .current {
                color:{$kopa_theme_options_color_code};
            }
            #comments-form label.required span {
                color:{$kopa_theme_options_color_code};
            }
            #comments-form #submit-comment {
                background-color:{$kopa_theme_options_color_code};
                border:1px solid {$kopa_theme_options_color_code};
            }
            #comments-form #comment_name:focus,
            #comments-form #comment_email:focus,
            #comments-form #comment_url:focus,
            #comments-form #comment_message:focus {
                border:1px solid {$kopa_theme_options_color_code};
            }
            .related-article .entry-item:hover {
                border-bottom:1px solid {$kopa_theme_options_color_code};
            }
            .related-article .entry-item .entry-content .entry-title a:hover {
                color:{$kopa_theme_options_color_code};
            }
            .carousel-nav a {
                background-color:{$kopa_theme_options_color_code};
                border:1px solid {$kopa_theme_options_color_code};
            }
            .single-line {
                background-color:{$kopa_theme_options_color_code};
            }
            .single-bullet {
                background-color:{$kopa_theme_options_color_code};
            }
            .kp-single-2 .entry-box .entry-meta-icon {
                background-color: {$kopa_theme_options_color_code};
            }
            .kp-single-2 .entry-box .entry-meta-circle {
                background-color: {$kopa_theme_options_color_code};
            }
            #main-col .widget .widget-title span {
                border-bottom:2px solid {$kopa_theme_options_color_code};
            }
            .featured-widget .pagination a.selected {
                background-color:{$kopa_theme_options_color_code};
            }
            #contact_message:focus,
            #contact_name:focus, 
            #contact_email:focus, 
            #contact_url:focus, 
            #contact_subject:focus {
                border:1px solid {$kopa_theme_options_color_code}!important
            }
            #contact-form #submit-contact {
                background-color:{$kopa_theme_options_color_code};
                border:1px solid {$kopa_theme_options_color_code};
            }
            #contact-info i {
                color:{$kopa_theme_options_color_code};
            }
            #contact-info a:hover {
                color:{$kopa_theme_options_color_code};
            }

            .contact-top li i {
                color:{$kopa_theme_options_color_code};
                font-size:16px;
                margin:0px 5px 0 0;
                background:none;
            }
            .contact-top li a:hover {
                color:{$kopa_theme_options_color_code};
            }
            .kp-our-work .carousel-nav a:hover {
                background-color:{$kopa_theme_options_color_code};
                border:1px solid {$kopa_theme_options_color_code};
            }

            .kp-our-work ul li .entry-item {
                border-bottom:2px solid {$kopa_theme_options_color_code};
            }
            .kp-our-experient .entry-item .entry-date {
                border:2px solid {$kopa_theme_options_color_code};
            }
            .kp-our-experient .entry-item .entry-date p {
                border-bottom:1px solid {$kopa_theme_options_color_code};
            }
            .kp-our-experient .entry-item .entry-date strong {
                color:{$kopa_theme_options_color_code};
            }
            .kp-our-experient .entry-item .entry-title a:hover {
                color:{$kopa_theme_options_color_code};
            }
            .kp-home-3 .entry-comment a:hover, 
            .kp-home-3 .entry-category a:hover, 
            .kp-home-3 .entry-author a:hover {
                color: {$kopa_theme_options_color_code};
            }
            .kp-testimonial .testimonial-author a {
                color:{$kopa_theme_options_color_code};
            }
            .kp-testimonial .carousel-nav a:hover {
                background-color: {$kopa_theme_options_color_code};
                border: 1px solid {$kopa_theme_options_color_code};
            }
            .bottom-twitter .twitter-widget {
                background:{$kopa_theme_options_color_code};
            }
            .about-list-container {
                background-color:{$kopa_theme_options_color_code};
            }
            .about-tabs li.active a,
            .about-tabs li a:hover {
                color:{$kopa_theme_options_color_code};
            }
            .about-team article {
                border-bottom:2px solid {$kopa_theme_options_color_code};
            }
            .pf-detail-nav a:hover {
                border:1px solid {$kopa_theme_options_color_code};
                background-color:{$kopa_theme_options_color_code};
            }
            .kp-cat-3 .entry-meta-column p {
                background-color:{$kopa_theme_options_color_code};
            }
            .kp-cat-3 .entry-meta-column a {
                color:{$kopa_theme_options_color_code};
            }
            .error-404 .left-col p{
                color:{$kopa_theme_options_color_code};
            }
            .error-404 .right-col h1{
                color:{$kopa_theme_options_color_code};
            }
            .error-404 .right-col a {
                color:{$kopa_theme_options_color_code};
            }
            .kopa-pagelink a{	
                color:{$kopa_theme_options_color_code};
            }
            .pf-detail-slider .flex-direction-nav .flex-next:hover,
            .pf-detail-slider .flex-direction-nav .flex-prev:hover,
            .blogpost-slider .flex-direction-nav .flex-next:hover,
            .blogpost-slider .flex-direction-nav .flex-prev:hover,
            .kp-single-slider .flex-direction-nav .flex-next:hover,
            .kp-single-slider .flex-direction-nav .flex-prev:hover,
            .kp-single-carousel .flex-direction-nav .flex-next:hover,
            .kp-single-carousel .flex-direction-nav .flex-prev:hover {
                    background-color:{$kopa_theme_options_color_code};
            }

            .ch-info .ch-info-front a {
                    background-color:{$kopa_theme_options_color_code};
            }
            .next:hover,
            .prev:hover {
                    background-color:{$kopa_theme_options_color_code};
            }
            .sequence li h2 {
                    background-color:{$kopa_theme_options_color_code};
            }

            .sequence li .more-link {
                    color:{$kopa_theme_options_color_code};
            }
            #main-menu > li.current-menu-item, 
            #main-menu > li.current-menu-parent,
            .search-box .search-form .search-submit,
            .ei-title h2 a, 
            .ei-slider-thumbs li a:hover,
            .ei-slider-thumbs li.ei-slider-element,
            .hover-effect .mask a.link-detail,
            #main-menu > li.menu-home-icon.current-menu-item > a,
            .timeline-item .timeline-icon div .post-type,
            .time-to-filter .top-ring,
            .time-to-filter .bottom-ring,
            .timeline-item .timeline-icon .circle-inner,
            #time-line,
            .timeline-container .tooltip-inner{
                background-color:{$kopa_theme_options_color_code};
            }
            #page-header, .ei-title h3,
            .timeline-item .timeline-icon .circle-outer,
            .timeline-item .timeline-icon .dotted-horizon,
            .timeline-container .tooltip.top .tooltip-arrow {
                border-color:{$kopa_theme_options_color_code};
            }
            .kp-title{
                 background-color:{$kopa_theme_options_color_code};
                 border-color:{$kopa_theme_options_color_code};
            }
            .timeline-container .load-more{
                border-color:{$kopa_theme_options_color_code};
                color:{$kopa_theme_options_color_code};
            }
            .kp-our-experient .entry-item .entry-date{
                color:{$kopa_theme_options_color_code};
            }
			#toggle-view-menu > li {
				background-color: #222;
				border-bottom: 1px solid #333;
				border-top: none;				
			}
			#toggle-view-menu span {
				background-color:{$kopa_theme_options_color_code};
				color: #fff;
			}
            
    </style>";

    // update in 8/7/2013
    if ($kopa_theme_options_color_code != '#91c448') {
        echo "<style>
            h1 a:hover, h2 a:hover, h3 a:hover, h4 a:hover, h5 a:hover, h6 a:hover {
                color: {$kopa_theme_options_color_code};
            }

            .kp-filter div > a,
            .kp-filter ul.ss-links li a:hover,
            .kp-filter ul.ss-links li:hover,
            .comment-reply-link,
            .comment-edit-link
            {
                background-color: {$kopa_theme_options_color_code};
            }

            .search-box .search-form .search-text:focus,
            blockquote,
            .kp-home-2 .widget-area-14 .widget .widget-title span {
                border-color: {$kopa_theme_options_color_code};
            }

            .ei-title h2 a:hover {
                color: white;
            }
        </style>";
    }

    /* =========================================================
      Font size
      ============================================================ */
    $kopa_theme_options_sub_nav_font_size = get_option('kopa_theme_options_sub_nav_font_size');
    if ($kopa_theme_options_sub_nav_font_size) {
        echo "<style>
                    #main-menu li ul li a{
                       font-size:{$kopa_theme_options_sub_nav_font_size}px;
                   }
                   </style>";
    }
    $kopa_theme_options_slider_font_size = get_option('kopa_theme_options_slider_font_size');
    if ($kopa_theme_options_slider_font_size) {
        echo "<style>
                    .ei-title h2 a{
                       font-size:{$kopa_theme_options_slider_font_size}px;
                   }
                   </style>";
    }
    $kopa_theme_options_content_font_size = get_option('kopa_theme_options_content_font_size');
    if ($kopa_theme_options_content_font_size) {
        echo "<style>
                    body{
                       font-size:{$kopa_theme_options_content_font_size}px;
                   }
                   </style>";
    }
    $kopa_theme_options_h1_font_size = get_option('kopa_theme_options_h1_font_size');
    if ($kopa_theme_options_h1_font_size) {
        echo "<style>
                    h1{
                       font-size:{$kopa_theme_options_h1_font_size}px;
                   }
                   </style>";
    }
    $kopa_theme_options_h2_font_size = get_option('kopa_theme_options_h2_font_size');
    if ($kopa_theme_options_h2_font_size) {
        echo "<style>
                    h2{
                       font-size:{$kopa_theme_options_h2_font_size}px;
                   }
                   </style>";
    }
    $kopa_theme_options_h3_font_size = get_option('kopa_theme_options_h3_font_size');
    if ($kopa_theme_options_h3_font_size) {
        echo "<style>
                    h3{
                       font-size:{$kopa_theme_options_h3_font_size}px;
                   }
                   </style>";
    }
    $kopa_theme_options_h4_font_size = get_option('kopa_theme_options_h4_font_size');
    if ($kopa_theme_options_h4_font_size) {
        echo "<style>
                    h4{
                       font-size:{$kopa_theme_options_h4_font_size}px;
                   }
                   </style>";
    }
    $kopa_theme_options_h5_font_size = get_option('kopa_theme_options_h5_font_size');
    if ($kopa_theme_options_h5_font_size) {
        echo "<style>
                    h5{
                       font-size:{$kopa_theme_options_h5_font_size}px;
                   }
                   </style>";
    }
    $kopa_theme_options_h6_font_size = get_option('kopa_theme_options_h6_font_size');
    if ($kopa_theme_options_h6_font_size) {
        echo "<style>
                    h6{
                       font-size:{$kopa_theme_options_h6_font_size}px;
                   }
                   </style>";
    }
    $kopa_theme_options_main_nav_font_size = get_option('kopa_theme_options_main_nav_font_size');
    if ($kopa_theme_options_main_nav_font_size) {
        echo "<style>
         #main-menu > li > a {
            font-size: {$kopa_theme_options_main_nav_font_size}px;
        }
        </style>";
    }
    $kopa_theme_options_wdg_footer_font_size = get_option('kopa_theme_options_wdg_footer_font_size');
    if ($kopa_theme_options_wdg_footer_font_size) {
        echo "<style>
         #bottom-sidebar .widget .widget-title {
            font-size: {$kopa_theme_options_wdg_footer_font_size}px;
        }
        </style>";
    }
    $kopa_theme_options_wdg_main_font_size = get_option('kopa_theme_options_wdg_main_font_size');
    if ($kopa_theme_options_wdg_main_font_size) {
        echo "<style>
         #main-col .widget .widget-title > span {
            font-size: {$kopa_theme_options_wdg_main_font_size}px;
        }
        </style>";
    }
    $kopa_theme_options_wdg_sidebar_font_size = get_option('kopa_theme_options_wdg_sidebar_font_size');
    if ($kopa_theme_options_wdg_sidebar_font_size) {
        echo "<style>
         .sidebar .widget .widget-title{
            font-size: {$kopa_theme_options_wdg_sidebar_font_size}px;
        }
        </style>";
    }
    /* =========================================================
      Font family
      ============================================================ */
    $google_fonts = kopa_get_google_font_array();
    $current_heading_font = get_option('kopa_theme_options_heading_font_family');
    $current_content_font = get_option('kopa_theme_options_content_font_family');
    $current_main_nav_font = get_option('kopa_theme_options_main_nav_font_family');
    $current_wdg_sidebar_font = get_option('kopa_theme_options_wdg_sidebar_font_family');
    $current_wdg_main_font = get_option('kopa_theme_options_wdg_main_font_family');
    $current_wdg_footer_font = get_option('kopa_theme_options_wdg_footer_font_family');
    $current_slider_font = get_option('kopa_theme_options_slider_font_family');
    $current_dropdown_font = get_option('kopa_theme_options_sub_nav_font_family');
    if ($current_dropdown_font) {
        $google_font_family = $google_fonts[$current_dropdown_font]['family'];
        echo "<style>
         #main-menu li ul li a{
            font-family: '{$google_font_family}', sans-serif;
        }
        </style>";
    }
    if ($current_wdg_sidebar_font) {
        $google_font_family = $google_fonts[$current_wdg_sidebar_font]['family'];
        echo "<style>
         .sidebar .widget .widget-title, .list-container ul li a{
            font-family: '{$google_font_family}', sans-serif;
        }
        </style>";
    }
    if ($current_wdg_main_font) {
        $google_font_family = $google_fonts[$current_wdg_main_font]['family'];
        echo "<style>
         #main-col .widget .widget-title > span{
            font-family: '{$google_font_family}', sans-serif;
        }
        </style>";
    }
    if ($current_wdg_footer_font) {
        $google_font_family = $google_fonts[$current_wdg_footer_font]['family'];
        echo "<style>
         #bottom-sidebar .widget .widget-title{
            font-family: '{$google_font_family}', sans-serif;
        }
        </style>";
    }
    if ($current_heading_font) {
        $google_font_family = $google_fonts[$current_heading_font]['family'];
        echo "<style>
         h1, h2, h3, h4, h5, h6 {
            font-family: '{$google_font_family}', sans-serif;
        }
        </style>";
    }
    if ($current_content_font) {
        $google_font_family = $google_fonts[$current_content_font]['family'];
        echo "<style>
         body {
            font-family: '{$google_font_family}', sans-serif;
        }
        </style>";
    }
    if ($current_main_nav_font) {
        $google_font_family = $google_fonts[$current_main_nav_font]['family'];
        echo "<style>
         #main-menu > li > a {
            font-family: '{$google_font_family}', sans-serif;
        }
        </style>";
    }
    if ($current_slider_font) {
        $google_font_family = $google_fonts[$current_slider_font]['family'];
        echo "<style>
         .ei-title h2 a{
            font-family: '{$google_font_family}', sans-serif;
        }
        </style>";
    }
    /* ================================================================================
     * Font weight
      ================================================================================ */
    $kopa_theme_options_sub_nav_font_weight = get_option('kopa_theme_options_sub_nav_font_weight');
    if ($kopa_theme_options_sub_nav_font_weight) {
        echo "<style>
         #main-menu li ul li a{
            font-weight: {$kopa_theme_options_sub_nav_font_weight};
        }
        </style>";
    }
    $kopa_theme_options_slider_font_weight = get_option('kopa_theme_options_slider_font_weight');
    if ($kopa_theme_options_slider_font_weight) {
        echo "<style>
         .ei-title h2 a{
            font-weight: {$kopa_theme_options_slider_font_weight};
        }
        </style>";
    }
    $kopa_theme_options_wgd_footer_font_weight = get_option('kopa_theme_options_wgd_footer_font_weight');
    if ($kopa_theme_options_wgd_footer_font_weight) {
        echo "<style>
         #bottom-sidebar .widget .widget-title{
            font-weight: {$kopa_theme_options_wgd_footer_font_weight};
        }
        </style>";
    }

    $kopa_theme_options_wgd_main_font_weight = get_option('kopa_theme_options_wgd_main_font_weight');
    if ($kopa_theme_options_wgd_main_font_weight) {
        echo "<style>
         #main-col .widget .widget-title > span{
            font-weight: {$kopa_theme_options_wgd_main_font_weight};
        }
        </style>";
    }
    $kopa_theme_options_wgd_sidebar_font_weight = get_option('kopa_theme_options_wgd_sidebar_font_weight');
    if ($kopa_theme_options_wgd_sidebar_font_weight) {
        echo "<style>
         .sidebar .widget .widget-title{
            font-weight: {$kopa_theme_options_wgd_sidebar_font_weight};
        }
        </style>";
    }
    $kopa_theme_options_h1_font_weight = get_option('kopa_theme_options_h1_font_weight');
    if ($kopa_theme_options_h1_font_weight) {
        echo "<style>
         h1{
            font-weight: {$kopa_theme_options_h1_font_weight};
        }
        </style>";
    }
    $kopa_theme_options_h2_font_weight = get_option('kopa_theme_options_h2_font_weight');
    if ($kopa_theme_options_h2_font_weight) {
        echo "<style>
         h2{
            font-weight: {$kopa_theme_options_h2_font_weight};
        }
        </style>";
    }
    $kopa_theme_options_h3_font_weight = get_option('kopa_theme_options_h3_font_weight');
    if ($kopa_theme_options_h3_font_weight) {
        echo "<style>
         h3{
            font-weight: {$kopa_theme_options_h3_font_weight};
        }
        </style>";
    }
    $kopa_theme_options_h4_font_weight = get_option('kopa_theme_options_h4_font_weight');
    if ($kopa_theme_options_h4_font_weight) {
        echo "<style>
         h4{
            font-weight: {$kopa_theme_options_h4_font_weight};
        }
        </style>";
    }
    $kopa_theme_options_h5_font_weight = get_option('kopa_theme_options_h5_font_weight');
    if ($kopa_theme_options_h5_font_weight) {
        echo "<style>
         h5{
            font-weight: {$kopa_theme_options_h5_font_weight};
        }
        </style>";
    }
    $kopa_theme_options_h6_font_weight = get_option('kopa_theme_options_h6_font_weight');
    if ($kopa_theme_options_h6_font_weight) {
        echo "<style>
         h6{
            font-weight: {$kopa_theme_options_h6_font_weight};
        }
        </style>";
    }
    $kopa_theme_options_main_nav_font_weight = get_option('kopa_theme_options_main_nav_font_weight');
    if ($kopa_theme_options_main_nav_font_weight) {
        echo "<style>
         #main-menu > li > a{
            font-weight: {$kopa_theme_options_main_nav_font_weight};
        }
        </style>";
    }

    /* ==================================================================================================
     * Custom heading color
     * ================================================================================================= */
    $kopa_theme_options_wdg_sidebar_color_code = get_option('kopa_theme_options_wdg_sidebar_color_code', '#333333');
    $kopa_theme_options_wdg_main_color_code = get_option('kopa_theme_options_wdg_main_color_code', '#333333');
    $kopa_theme_options_wdg_footer_color_code = get_option('kopa_theme_options_wdg_footer_color_code', '#333333');
    $kopa_theme_options_h1_color_code = get_option('kopa_theme_options_h1_color_code', '#333333');
    $kopa_theme_options_h2_color_code = get_option('kopa_theme_options_h2_color_code', '#333333');
    $kopa_theme_options_h3_color_code = get_option('kopa_theme_options_h3_color_code', '#333333');
    $kopa_theme_options_h4_color_code = get_option('kopa_theme_options_h4_color_code', '#333333');
    $kopa_theme_options_h5_color_code = get_option('kopa_theme_options_h5_color_code', '#333333');
    $kopa_theme_options_h6_color_code = get_option('kopa_theme_options_h6_color_code', '#333333');
    echo "<style>
         h1 {
            color: {$kopa_theme_options_h1_color_code};
        }
        h2{
            color: {$kopa_theme_options_h2_color_code};
        }
         h3{
            color: {$kopa_theme_options_h3_color_code};
        }
         h4{
            color: {$kopa_theme_options_h4_color_code};
        }
         h5{
            color: {$kopa_theme_options_h5_color_code};
        }
         h6{
            color: {$kopa_theme_options_h6_color_code};
        }
        .widget .widget-title {
            color: {$kopa_theme_options_wdg_sidebar_color_code};
        }
        .sidebar .widget .widget-title{
            color: {$kopa_theme_options_wdg_sidebar_color_code};
        }
        #main-col .widget .widget-title > span{
            color: {$kopa_theme_options_wdg_main_color_code};
        }
        #bottom-sidebar .widget .widget-title{
            color: {$kopa_theme_options_wdg_footer_color_code};
        }
        </style>";
    /* ==================================================================================================
     * Custom CSS
     * ================================================================================================= */
    $kopa_theme_options_custom_css = htmlspecialchars_decode(stripslashes(get_option('kopa_theme_options_custom_css')));
    if ($kopa_theme_options_custom_css)
        echo "<style>{$kopa_theme_options_custom_css}</style>";

    /* ==================================================================================================
     * IE
     * ================================================================================================= */
    global $is_IE;
    $dir = get_template_directory_uri();
    if ($is_IE)
        echo "<style>
            .kp-dropcap.color,
            .more-link-icon i,
            .hover-effect .mask a.link-detail,
            .hover-effect .mask a.link-gallery,
            .bwWrapper a.kp-pf-detail,
            .socials-link li,
            .search-box .search-form .search-text,
            .search-box .search-form .search-submit,
            .timeline-container .load-more,
            .timeline-item .timeline-icon .circle-outer,
            .timeline-item .timeline-icon .circle-inner,
            .timeline-item .timeline-icon div p,
            .timeline-item .timeline-icon div span,
            .time-to-filter .top-ring,
            .time-to-filter .bottom-ring,
            .kp-tagcloud a .kp-tag-rounded,
            .load-more-gallery,
            .ch-info .ch-info-back a,
            .ch-info .ch-info-front a,
            .bottom-circle,
            .bottom-bullet,
            .kp-our-work .carousel-nav a,
            .kp-our-experient .entry-item .entry-date,
            .kp-testimonial .carousel-nav a,
            .kp-testimonial .testimonial-author img,
            .about-tabs li a,
            #toggle-view li span,
            .pf-detail-nav a,
            .kp-cat-2 .article-list li article .entry-meta-box .entry-meta-icon,
            .kp-cat-2 .article-list li article .entry-meta-box .entry-meta-circle,
            .article-list-wrapper .article-bullet,
            .kp-cat-3 .entry-meta-column p,
            .about-author .avatar-thumb img,
            #comments .comment-avatar img,
            .kp-single-2 .entry-box .entry-meta-icon,
            .kp-single-2 .entry-box .entry-meta-circle,
            .single-bullet,
            .about-list-container .tooltip-inner {
                behavior: url({$dir}/js/PIE.htc);
            }
            </style>";
}

/* ==============================================================================
 * Mobile Menu
  ============================================================================= */

class kopa_mobile_menu extends Walker_Nav_Menu {

    function start_el(&$output, $item, $depth, $args) {
        global $wp_query;
        $indent = ( $depth ) ? str_repeat("\t", $depth) : '';

        $class_names = $value = '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));

        if ($depth == 0)
            $class_names = $class_names ? ' class="' . esc_attr($class_names) . ' clearfix"' : 'class="clearfix"';
        else
            $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : 'class=""';


        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $value . $class_names . '>';

        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .=!empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .=!empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .=!empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
        if ($depth == 0) {
            $item_output = $args->before;
            $item_output .= '<h3><a' . $attributes . '>';
            $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
            $item_output .= '</a></h3>';
            $item_output .= $args->after;
        } else {
            $item_output = $args->before;
            $item_output .= '<a' . $attributes . '>';
            $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
            $item_output .= '</a>';
            $item_output .= $args->after;
        }
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    function start_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        if ($depth == 0)
            $output .= "\n$indent<span>+</span><div class='clear'></div><div class='menu-panel clearfix'><ul>";
        else
            $output .= "<ul>";
    }

    function end_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        if ($depth == 0)
            $output .= "$indent</ul></div>\n";
    }

}

function kopa_add_icon_home_menu($items, $args) {
    if ($args->theme_location == 'main-nav') {
        if ($args->menu_id == 'toggle-view-menu') {
            $homelink = '<li class="clearfix"><h3><a href="' . get_bloginfo('url') . '">' . __('Home', kopa_get_domain()) . '</a></h3></li>';
            $items = $homelink . $items;
        } else if ($args->menu_id == 'main-menu') {
            $homelink = '<li class="menu-home-icon' . ((is_home() || is_front_page()) ? ' current-menu-item' : '') . '"><a href="' . home_url() . '">' . __('Home', kopa_get_domain()) . '</a></li>';
            $items = $homelink . $items;
        }
    }
    return $items;
}