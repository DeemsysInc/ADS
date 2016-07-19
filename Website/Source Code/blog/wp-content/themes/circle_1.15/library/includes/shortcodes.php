<?php

remove_shortcode('gallery', 'gallery_shortcode');
add_shortcode('gallery', 'kopa_gallery_shortcode');

function kopa_gallery_shortcode($atts, $content = null) {
    extract(shortcode_atts(array("ids" => '', "display_type" => 0), $atts));
    $output = '';
    if (isset($atts['ids'])) {
        $ids = explode(',', $atts['ids']);
        if ($ids) {
            if (is_single()) {
                $atts['display_type'] = 2;
            }
            switch ((int) $atts['display_type']) {
                case 1:

                    $_index = 0;
                    foreach ($ids as $attachment_id) {
                        $_index++;
                        if ($_index == 1) {
                            $output .= '<div class="latest-gallery-img hover-effect">
                                        <div class="mask">
                                        <a class="link-detail" rel="prettyPhoto[kopa-gallery]" href="';
                            $thumbnail = wp_get_attachment_image_src($attachment_id, 'kopa-image-size-2');
                            $output .= $thumbnail[0];
                            $output .='" data-icon="&#xe07e;"></a>
                                        </div>';
                            $output .='<img src="' . $thumbnail[0] . '" alt="" />
                                    </div>';
                        } elseif ($_index > 1 && $_index <= 4) {
                            if ($_index == 2) {
                                $output .='<ul class="older-gallery-img clearfix">';
                            }
                            $output .='<li class="hover-effect">
                                <div class="mask">
                                <a class="link-detail" href="';
                            $thumbnail = wp_get_attachment_image_src($attachment_id, 'kopa-image-size-1');
                            $output .=$thumbnail[0];
                            $output .='" rel="prettyPhoto[kopa-gallery]" data-icon="&#xe07e;"></a>
                                        </div>
                                        <img src="';
                            $output .=$thumbnail[0];
                            $output .='" alt="" />
                                </li>';
                            if ($_index == 4 or $_index == count($ids)) {
                                $output .='</ul>';
                            }
                        } elseif ($_index >= 5) {
                            if ($_index == 5) {
                                $output .='<div class="more-panel clearfix"><ul class="more-gallery-img clearfix">';
                            }
                            $thumbnail = wp_get_attachment_image_src($attachment_id, 'kopa-image-size-1');
                            $output .='<li class="hover-effect">
                            <div class="mask">
                                <a class="link-detail" href="' . $thumbnail[0] . '" rel="prettyPhoto[kopa-gallery]" data-icon="&#xe07e;"></a>
                            </div>
                                <img src="' . $thumbnail[0] . '" alt="" />
                            </li>';
                            if ($_index == count($ids)) {
                                $output .='</ul><!--more-gallery-img--></div>';
                            }
                        }
                    }
                    break;

                case 2:

                    $output .= '<div class="flexslider kp-single-slider loading"><ul class="slides">';
                    foreach ($ids as $attachment_id) {
                        $thumbnail = wp_get_attachment_image_src($attachment_id, 'kopa-image-size-2');
                        $output .= '<li><img src="' . $thumbnail[0] . '" alt="" /></li>';
                    }
                    $output .= '</ul></div>';

                    $output .= '<div class="flexslider kp-single-carousel"><ul class="slides">';
                    foreach ($ids as $attachment_id) {
                        $thumbnail = wp_get_attachment_image_src($attachment_id, 'kopa-image-size-2');
                        $output .= '<li><img src="' . $thumbnail[0] . '" alt="" /></li>';
                    }
                    $output .= '</ul></div>';
                    break;
                case 3:
                    foreach ($ids as $attachment_id) {
                        $thumbnail = wp_get_attachment_image_src($attachment_id, 'kopa-image-size-0');
                        $full_image = wp_get_attachment_image_src($attachment_id, 'kopa-image-size-2');
                        $output .= '<li><a href="' . $full_image[0] . '" title="<?php echo $post_title; ?>" rel="prettyPhoto">';
                        $output .= '<img src="' . $thumbnail[0] . '" alt="" />';
                        $output .= '</a></li>';
                    }
                    break;
                default:
                    $output .= '<div class="flexslider blogpost-slider"><ul class="slides">';
                    foreach ($ids as $attachment_id) {
                        $thumbnail = wp_get_attachment_image_src($attachment_id, 'kopa-image-size-2');
                        $output .= '<li><img src="' . $thumbnail[0] . '" alt="" /></li>';
                    }
                    $output .= '</ul></div>';
                    break;
            }
        }
    }
    return $output;
}

/* SHORTCODE : ONE_HALF */

add_shortcode('one_half', 'kopa_shortcode_one_half');

function kopa_shortcode_one_half($atts, $content = null) {
    $atts = shortcode_atts(
            array(
        'last' => 'no',
            ), $atts);

    if ($atts['last'] == 'yes') {
        return '<div class="kopa-one-two last">' . do_shortcode($content) . '</div><div class="clear"></div>';
    } else {
        return '<div class="kopa-one-two">' . do_shortcode($content) . '</div>';
    }
}

/* SHORTCODE : ONE_THIRD */

add_shortcode('one_third', 'kopa_shortcode_one_third');

function kopa_shortcode_one_third($atts, $content = null) {
    $atts = shortcode_atts(
            array(
        'last' => 'no',
            ), $atts);

    if ($atts['last'] == 'yes') {
        return '<div class="kopa-one-third last">' . do_shortcode($content) . '</div><div class="clear"></div>';
    } else {
        return '<div class="kopa-one-third">' . do_shortcode($content) . '</div>';
    }
}

add_shortcode('two_third', 'kopa_shortcode_two_third');

function kopa_shortcode_two_third($atts, $content = null) {
    $atts = shortcode_atts(
            array(
        'last' => 'no',
            ), $atts);

    if ($atts['last'] == 'yes') {
        return '<div class="kopa-two-third last">' . do_shortcode($content) . '</div><div class="clear"></div>';
    } else {
        return '<div class="kopa-two-third">' . do_shortcode($content) . '</div>';
    }
}

/* SHORTCODE : ONE_FOURTH */

add_shortcode('one_fourth', 'kopa_shortcode_one_fourth');

function kopa_shortcode_one_fourth($atts, $content = null) {
    $atts = shortcode_atts(
            array(
        'last' => 'no',
            ), $atts);

    if ($atts['last'] == 'yes') {
        return '<div class="kopa-one-fourth last">' . do_shortcode($content) . '</div><div class="clear"></div>';
    } else {
        return '<div class="kopa-one-fourth">' . do_shortcode($content) . '</div>';
    }
}

/* SHORTCODE : THREE_FOURTH */

add_shortcode('three_fourth', 'kopa_shortcode_three_fourth');

function kopa_shortcode_three_fourth($atts, $content = null) {
    $atts = shortcode_atts(
            array(
        'last' => 'no',
            ), $atts);

    if ($atts['last'] == 'yes') {
        return '<div class="kopa-three-fourth last">' . do_shortcode($content) . '</div><div class="clear"></div>';
    } else {
        return '<div class="kopa-three-fourth">' . do_shortcode($content) . '</div>';
    }
}

/* SHORTCODE : TABS */

add_shortcode('tabs', 'kopa_shortcode_tabs');

function kopa_shortcode_tabs($atts, $content = null) {
    extract(shortcode_atts(array(), $atts));

    $out = '<div class="kopa-tabs tabs-wrapper mb-20">';

    $out .= '<div class="list-container list-container-3">';
    $out .= '<ul class="tabs tabs-3 clearfix">';
    foreach ($atts as $key => $tab) {
        $out .= '<li><a href="#' . $key . '">' . $tab . '</a></li>';
    }
    $out .= '</ul>';
    $out .= '</div>';

    $out .= '<div class="tab-container tab-container-3">';
    $out .= do_shortcode($content);
    $out .= '</div>';

    $out .= '</div>';
    return $out;
}

/* SHORTCODE : TAB */

add_shortcode('tab', 'kopa_shortcode_tab');

function kopa_shortcode_tab($atts, $content = null) {
    extract(shortcode_atts(array(), $atts));
    return '<div id="tab' . $atts['id'] . '" class="tab-content tab-content-3" style="display:none;">' . do_shortcode($content) . '</div>';
}

/* SHORTCODE : ACCORDIONS */

add_shortcode('accordions', 'kopa_shortcode_accordions');

function kopa_shortcode_accordions($atts, $content = null) {
    extract(shortcode_atts(array(), $atts));
    return '<div class="acc-wrapper">' . do_shortcode($content) . '</div>';
}

/* SHORTCODE : ACCORDION */

add_shortcode('accordion', 'kopa_shortcode_accordion');

function kopa_shortcode_accordion($atts, $content = null) {
    extract(shortcode_atts(array(
        'title' => 'Accordion Title'
                    ), $atts));
    return '<div class="accordion-title">
                <h3><a href="#">' . $atts['title'] . '</a></h3>
                    <span>+</span>
            </div>
            <div class="accordion-container" style="display:none;">' . do_shortcode($content) . '</div>';
}

/* SHORTCODE : TOGGLE */


add_shortcode('toggles', 'kopa_shortcode_toggles');

function kopa_shortcode_toggles($atts = array(), $content = NULL) {
    extract(shortcode_atts(array(), $atts));

    $out = '<ul id="toggle-view">';
    $out.= do_shortcode($content);
    $out.= '</ul>';

    return apply_filters('kopa_shortcode_toggles', $out);
}

add_shortcode('toggle', 'kopa_shortcode_toggle');

function kopa_shortcode_toggle($atts = array(), $content = NULL) {
    extract(shortcode_atts(array('title' => ''), $atts));

    $out = '<li>';
    $out.='<span>+</span>';
    $out.='<h3>' . $atts['title'] . '</h3>';
    $out.='<div class="clear"></div>';
    $out.='<div class="panel clearfix">';
    $out.= do_shortcode($content);
    $out.='</div>';
    $out.='</li>';

    return apply_filters('kopa_shortcode_toggle', $out);
}

/* SHORTCODE : DROPCAPS */

add_shortcode('dropcaps', 'kopa_shortcode_dropcaps');

function kopa_shortcode_dropcaps($atts, $content = null) {
    $atts = shortcode_atts(array(), $atts);
    return '<span class="kopa-dropcap">' . do_shortcode($content) . '</span>';
}

/* SHORTCODE : BUTTON */

add_shortcode('button', 'kopa_shortcode_button');

function kopa_shortcode_button($atts, $content = null) {
    $atts = shortcode_atts(
            array(
        'color' => 'green',
        'size' => 'small',
        'link' => '',
        'target' => '_self',
            ), $atts);

    if (!in_array($atts['color'], array('orange', 'green', 'blue', 'black'))) {
        $atts['color'] = 'green';
    }

    if (!in_array($atts['size'], array('small', 'medium', 'big'))) {
        $atts['size'] = 'small';
    }
    $out = sprintf('<a href="%1$s" class="%2$s-button %3$s-button" target="%4$s">%5$s</a>', $atts['link'], $atts['color'], $atts['size'], $atts['target'], do_shortcode($content));
    return apply_filters('kopa_shortcode_button', $out);
}

/* SHORTCODE : ALERT */

add_shortcode('alert', 'kopa_shortcode_alert');

function kopa_shortcode_alert($atts, $content = null) {
    $atts = shortcode_atts(
            array(
        'type' => 'infor',
        'title' => ''
            ), $atts);

    $class = '';

    if (!in_array($atts['type'], array('block', 'error', 'success', 'info'))) {
        $atts['type'] = 'block';
    }

    $out = "<div class='alert alert-{$atts['type']}'>";
    $out .= "<h4>{$atts['title']}</h4>";
    $out .= '<p>' . do_shortcode($content) . '</p>';
    $out .= "</div >";

    return $out;
}

/* SHORTCODE : CONTACT */
add_shortcode('contact_form', 'kopa_shortcode_contact_form');

function kopa_shortcode_contact_form($atts, $content = null) {
    $atts = shortcode_atts(array('caption' => '', 'address' => '', 'phone' => '', 'email' => ''), $atts);


    $out = '<div class="row-fluid">';
    $out .= '<div class="span6">';
    $out .= '<div id="contact-box">';

    if ($atts['caption']) {
        $out .= "<h2 class='contact-title'>{$atts['caption']}</h2>";
    }

    $out .= '<form id="contact-form" class ="clearfix" action="' . admin_url('admin-ajax.php') . '" method="post">';

    $out .= '<p class="input-block clearfix">';
    $out .= '<label class="required" for="contact_name">' . __('Name (required):', kopa_get_domain()) . '</label>';
    $out .= '<input class="valid" type="text" name="name" id="contact_name" value="">';
    $out .= '</p>';

    $out .= '<p class="input-block clearfix">';
    $out .= '<label class="required" for="contact_email">' . __('Email (required):', kopa_get_domain()) . '</label>';
    $out .= '<input type="email" class="valid" name="email" id="contact_email" value="">';
    $out .= '</p>';

    $out .= '<p class="input-block clearfix">';
    $out .= '<label class="required" for="contact_url">' . __('Website:', kopa_get_domain()) . '</label>';
    $out .= '<input type="url" class="valid" name="url" id="contact_url" value="">';
    $out .= '</p>';

    $out .= '<p class="textarea-block">';
    $out .= '<label class="required" for="contact_message">' . __('Message (required):', kopa_get_domain()) . '</label>';
    $out .= '<textarea id="contact_message" name="message"></textarea>';
    $out .= '</p>';

    $out .= '<p class="comment-button clearfix">';
    $out .= '<input type="submit" id="submit-contact" value="' . __('Send', kopa_get_domain()) . '">';
    $out .= '</p>';

    $out .= '<input type="hidden" name="action" value="kopa_send_contact">';
    $out .= wp_nonce_field('kopa_send_contact_nicole_kidman', 'kopa_send_contact_nonce', true, false);

    $out .= '</form>';

    $out .= '<div id="response"></div>';

    $out.= '</div><!--contact-box-->';
    $out.= '</div><!--span6-->';

    if ($atts['address'] || $atts['phone'] || $atts['email']) {
        $out.= '<div class="span6">';
        $out.= '<div id="contact-info">';
        $out.= '<h2 class="contact-title">' . __('Contact Information', kopa_get_domain()) . '</h2>';
        $out.= '<address>';
        if ($atts['address'])
            $out.= '<p><i class="icon-location"></i><span>' . $atts['address'] . '</span></p>';
        if ($atts['phone']) {
            $phones = explode(',', $atts['phone']);
            $phones_html = array();
            foreach ($phones as $phone) {
                $phones_html[] = '<a href="callto:' . $phone . '">' . $phone . '</a>';
            }
            $out.= '<p><i class="icon-mobile"></i>' . implode(', ', $phones_html) . '</p>';
        }
        if ($atts['email'])
            $out.= '<p><i class="icon-envelop"></i><a href="mailto:' . $atts['email'] . '">' . $atts['email'] . '</a></p>';

        $out.= '</address>';

        $out.= '</div><!--contact-info-->';
        $out.= '</div><!--span6-->';
    }

    $out.= '</div><!--row-fluid-->';

    return $out;
}

/* SHORTCODE : POST */
add_shortcode('posts', 'kopa_shortcode_posts');

function kopa_shortcode_posts($atts, $content = null) {
    $atts = shortcode_atts(array(
        'cats' => '',
        'tags' => '',
        'relation' => 'OR',
        'count' => 10,
        'orderby' => 'lastest', //lastest, popular, most_like, most_comment, random
        'max_length' => 0
            ), $atts);

    $args = array(
        'post_type' => array('post'),
        'posts_per_page' => (int) $atts['count'],
    );

    $tax_query = array();
    if ($atts['cats']) {
        $tax_query[] = array(
            'taxonomy' => 'category',
            'field' => 'id',
            'terms' => explode(', ', $atts['cats'])
        );
    }
    if ($atts['tags']) {
        $tax_query[] = array(
            'taxonomy' => 'post_tag',
            'field' => 'id',
            'terms' => explode(', ', $atts['tags'])
        );
    }
    if ($atts['relation'] && count($tax_query) == 2) {
        $tax_query[] = ('OR' == $atts['relation']) ? 'OR' : 'AND';
    }

    if ($tax_query) {
        $args['tax_query'] = $tax_query;
    }

    switch ($atts['orderby']) {
        case 'popular':
            $args['meta_key'] = 'kopa_' . kopa_get_domain() . '_total_view';
            $args['orderby'] = 'meta_value_num';
            break;
        case 'most_comment':
            $args['orderby'] = 'comment_count';
            break;
        case 'random':
            $args['orderby'] = 'rand';
            break;
        default:
            $args['orderby'] = 'date';
            break;
    }

    $posts = new WP_Query($args);

    $out = '';
    $max_length = ($atts['max_length']) ? (int) $atts['max_length'] : 0;

    if ($posts->post_count > 0) {
        $out .= '<ul class="kopa-shortcode-posts kp-latest-post cleafix">';
        while ($posts->have_posts()) {
            $posts->the_post();
            $post_id = get_the_ID();
            $url = get_permalink();
            $title = get_the_title();
            $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'kopa-image-size-0');
            
            $out .= '<li>';
            $out .= '<article class="clearfix">';
            if (has_post_thumbnail($post_id))
                $out .= '<a class="entry-thumb" href="' . $url . '"><img src="' . $thumbnail[0] . '" alt="" class="hover-effect"></a>';

            $out .= '<div class="entry-content">';
            $out .= '<h4 class="entry-title"><a href="' . $url . '">' . $title . '</a></h4>';

            $out .= '<span class="entry-date"><span class="icon-clock-4 entry-icon"></span>' . get_the_date() . '</span>';
            $out .= '<span class="entry-comment"><span class="icon-bubbles-4 entry-icon"></span>';
            
            $num_comments = get_comments_number(); // get_comments_number returns only a numeric value
            if (comments_open()) {
                if ($num_comments == 0) {
                    $comments = __('0 Comments', kopa_get_domain());
                } elseif ($num_comments > 1) {
                    $comments = $num_comments . __(' Comments', kopa_get_domain());
                } else {
                    $comments = __('1 Comment', kopa_get_domain());
                }
               $out .=  $comments;
            } else {
                $out .=  __('Comments off', kopa_get_domain());
            }
            $out .= '</span>';
            $out .= '</div>';
            $out .= '</article>';
            $out .= '</li>';
        }
        $out .= '</ul>';
    }

    wp_reset_postdata();

    return $out;
}

add_shortcode('youtube', 'kopa_shortcode_youtube');

function kopa_shortcode_youtube($atts, $content = null) {
    $atts = shortcode_atts(array(), $atts);
    $out = '';
    if ($content) {
        $matches = array();
        preg_match('#(\.be/|/embed/|/v/|/watch\?v=)([A-Za-z0-9_-]{5,11})#', $content, $matches);
        if (isset($matches[2]) && $matches[2] != '') {
            $out .= '<div class="video-wrapper"><iframe src="http://www.youtube.com/embed/' . $matches[2] . '" width="560" height="315" frameborder="0" allowfullscreen></iframe></div>';
        }
    }

    return $out;
}

add_shortcode('vimeo', 'kopa_shortcode_vimeo');

function kopa_shortcode_vimeo($atts, $content = null) {
    $atts = shortcode_atts(array(), $atts);
    $out = '';
    if ($content) {
        $matches = array();
        preg_match('/(\d+)/', $content, $matches);
        if (isset($matches[0]) && $matches[0] != '') {
            $out .= '<div class="video-wrapper"><iframe src="http://player.vimeo.com/video/' . $matches[0] . '" width="560" height="315" frameborder="0" allowfullscreen></iframe></div>';
        }
    }
    return $out;
}

add_shortcode('google_map', 'kopa_shortcode_google_map');

function kopa_shortcode_google_map($atts, $content = null) {
    $atts = shortcode_atts(array(
        'caption' => ''
            ), $atts);

    $out = '<div class="kp-map">';
    if ($atts['caption'])
        $out .= '<h4 class="widget-title">' . $atts['caption'] . '</h4>';
    $out .= $content . '</div>';

    return $out;
}

add_shortcode('audio', 'kopa_shortcode_audio');

function kopa_shortcode_audio($atts, $content = null) {
    $atts = shortcode_atts(array(), $atts);
    $out = '';

    if ($content) {
        $out = '<audio style="width:100%;" controls><source src="' . $content . '" type="audio/mpeg"></audio>';
    }
    return $out;
}

add_action('init', 'kopa_shortcode_add_button');

function kopa_shortcode_add_button() {
    if (current_user_can('edit_posts') && current_user_can('edit_pages')) {
        add_filter('mce_external_plugins', 'kopa_add_plugin');
        add_filter('mce_buttons_3', 'kopa_register_button');
    }
}

function kopa_add_plugin($plugin_array) {
    $plugin_array['one_half'] = get_template_directory_uri() . '/library/js/shortcodes/one_half.js';
    $plugin_array['one_third'] = get_template_directory_uri() . '/library/js/shortcodes/one_third.js';
    $plugin_array['two_third'] = get_template_directory_uri() . '/library/js/shortcodes/two_third.js';
    $plugin_array['one_fourth'] = get_template_directory_uri() . '/library/js/shortcodes/one_fourth.js';
    $plugin_array['three_fourth'] = get_template_directory_uri() . '/library/js/shortcodes/three_fourth.js';
    $plugin_array['tabs'] = get_template_directory_uri() . '/library/js/shortcodes/tabs.js';
    $plugin_array['accordions'] = get_template_directory_uri() . '/library/js/shortcodes/accordions.js';
    $plugin_array['toggle'] = get_template_directory_uri() . '/library/js/shortcodes/toggle.js';
    $plugin_array['dropcaps'] = get_template_directory_uri() . '/library/js/shortcodes/dropcaps.js';
    $plugin_array['button'] = get_template_directory_uri() . '/library/js/shortcodes/button.js';
    $plugin_array['alert'] = get_template_directory_uri() . '/library/js/shortcodes/alert.js';
    $plugin_array['posts_lastest'] = get_template_directory_uri() . '/library/js/shortcodes/posts_lastest.js';
    $plugin_array['posts_popular'] = get_template_directory_uri() . '/library/js/shortcodes/posts_popular.js';
    $plugin_array['posts_most_comment'] = get_template_directory_uri() . '/library/js/shortcodes/posts_most_comment.js';
    $plugin_array['posts_random'] = get_template_directory_uri() . '/library/js/shortcodes/posts_random.js';
    $plugin_array['contact_form'] = get_template_directory_uri() . '/library/js/shortcodes/contact_form.js';
    $plugin_array['google_map'] = get_template_directory_uri() . '/library/js/shortcodes/google_map.js';
    $plugin_array['youtube'] = get_template_directory_uri() . '/library/js/shortcodes/youtube.js';
    $plugin_array['vimeo'] = get_template_directory_uri() . '/library/js/shortcodes/vimeo.js';
    $plugin_array['audio'] = get_template_directory_uri() . '/library/js/shortcodes/audio.js';

    return $plugin_array;
}

function kopa_register_button($buttons) {
    array_push($buttons, 'one_half');
    array_push($buttons, 'one_third');
    array_push($buttons, 'two_third');
    array_push($buttons, 'one_fourth');
    array_push($buttons, 'three_fourth');
    array_push($buttons, 'dropcaps');
    array_push($buttons, 'button');
    array_push($buttons, 'alert');
    array_push($buttons, 'tabs');
    array_push($buttons, 'accordions');
    array_push($buttons, 'toggle');
    array_push($buttons, 'posts_lastest');
    array_push($buttons, 'posts_popular');
    array_push($buttons, 'posts_most_comment');
    array_push($buttons, 'posts_random');
    array_push($buttons, 'contact_form');
    array_push($buttons, 'google_map');
    array_push($buttons, 'youtube');
    array_push($buttons, 'vimeo');
    array_push($buttons, 'audio');

    return $buttons;
}