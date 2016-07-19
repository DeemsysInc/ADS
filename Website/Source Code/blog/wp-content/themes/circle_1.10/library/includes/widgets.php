<?php
add_action('widgets_init', 'kopa_widgets_init');

function kopa_widgets_init() {
    register_widget('Kopa_Widget_EiSlideshow');
    register_widget('Kopa_Widget_EiSlideshow_2');
    register_widget('Kopa_Widget_ArticleList_Carousel');
    register_widget('Kopa_Widget_ArticleList_Carousel_2');
    register_widget('Kopa_Widget_Gallery');
    register_widget('Kopa_Widget_ArticleList_Small_Thumbnail');
    register_widget('Kopa_Widget_ArticleList_Medium_Thumbnail');
    register_widget('Kopa_Widget_ArticleList_Large_Thumbnail');
    register_widget('Kopa_Widget_Video');
    register_widget('Kopa_Widget_Flickr');
    register_widget('Kopa_Widget_Recent_Comments');
    register_widget('Kopa_Widget_Timeline_Articles');
    //register_widget('Kopa_Widget_Twitter');
    register_widget('Kopa_Widget_Text');
    // register_widget('Kopa_Widget_Sequence_Slider');   
    register_widget('Kopa_Widget_Carousel_Slider_1');
    register_widget('Kopa_Widget_Carousel_Slider_2');
    register_widget('Kopa_Widget_ArticleList_No_Thumbnail');
    register_widget('Kopa_Widget_Advertisement');
    register_widget('Kopa_Widget_Content');
    register_widget('Kopa_Widget_Client');
    register_widget('Kopa_Widget_Testimonial');
    register_widget('Kopa_Widget_Service');
    register_widget('Kopa_Widget_Service_Pie_Chart');
    register_widget('Kopa_Widget_Service_Pie_Chart_2');
    register_widget('Kopa_Widget_Staff');
}

add_action('admin_enqueue_scripts', 'kopa_widget_admin_enqueue_scripts');

function kopa_widget_admin_enqueue_scripts($hook) {
    if ('widgets.php' === $hook) {
        $dir = get_template_directory_uri() . '/library';
        wp_enqueue_style('kopa_widget_admin', "{$dir}/css/widget.css");
        wp_enqueue_script('kopa_widget_admin', "{$dir}/js/widget.js", array('jquery'));
    }
}

function kopa_widget_article_build_query($query_args = array()) {
    $args = array(
        'post_type' => array('post'),
        'posts_per_page' => $query_args['number_of_article']
    );

    $tax_query = array();

    if (isset($query_args['categories']) && $query_args['categories']) {
        $tax_query[] = array(
            'taxonomy' => 'category',
            'field' => 'id',
            'terms' => $query_args['categories']
        );
    }
    if (isset( $query_args['tags'] ) && $query_args['tags']) {
        $tax_query[] = array(
            'taxonomy' => 'post_tag',
            'field' => 'id',
            'terms' => $query_args['tags']
        );
    }
    if ($query_args['relation'] && count($tax_query) == 2)
        $tax_query['relation'] = $query_args['relation'];

    if ($tax_query) {
        $args['tax_query'] = $tax_query;
    }

    switch ($query_args['orderby']) {
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
    if (isset($query_args['post__not_in']) && $query_args['post__not_in']) {
        $args['post__not_in'] = $query_args['post__not_in'];
    }
    return new WP_Query($args);
}

function kopa_widget_posttype_build_query($query_args = array()) {
    $args = array(
        'post_type' => $query_args['post_type'],
        'posts_per_page' => $query_args['posts_per_page']
    );

    $tax_query = array();

    if ($query_args['categories']) {
        $tax_query[] = array(
            'taxonomy' => $query_args['cat_name'],
            'field' => 'id',
            'terms' => $query_args['categories']
        );
    }
    if ($query_args['tags']) {
        $tax_query[] = array(
            'taxonomy' => $query_args['tag_name'],
            'field' => 'id',
            'terms' => $query_args['tags']
        );
    }
    if ($query_args['relation'] && count($tax_query) == 2)
        $tax_query['relation'] = $query_args['relation'];

    if ($tax_query) {
        $args['tax_query'] = $tax_query;
    }

    switch ($query_args['orderby']) {
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
    if (isset($query_args['post__not_in']) && $query_args['post__not_in']) {
        $args['post__not_in'] = $query_args['post__not_in'];
    }
    return new WP_Query($args);
}

class Kopa_Widget_ArticleList extends WP_Widget {

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = esc_attr($instance['relation']);
        $query_args['tags'] = $instance['tags'];
        $query_args['number_of_article'] = (int) $instance['number_of_article'];
        $query_args['orderby'] = $instance['orderby'];

        echo $before_widget;
        if (!empty($title)) {
            echo $before_title . $title . $after_title;
        }

        $this->display($query_args);

        echo $after_widget;
    }

    function display($query_args) {
        
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['number_of_article'] = $new_instance['number_of_article'];
        $instance['orderby'] = $new_instance['orderby'];
        return $instance;
    }

    function form($instance) {
        $default = array(
            'title' => '',
            'categories' => array(),
            'relation' => 'OR',
            'tags' => array(),
            'number_of_article' => 4,
            'orderby' => 'lastest',
        );
        $instance = wp_parse_args((array) $instance, $default);
        $title = strip_tags($instance['title']);

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['number_of_article'] = (int) $instance['number_of_article'];
        $form['orderby'] = $instance['orderby'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_categories();
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, (isset($form['categories']) ? $form['categories'] : array())) ) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                <?php
                $relation = array(
                    'AND' => __('And', kopa_get_domain()),
                    'OR' => __('Or', kopa_get_domain())
                );
                foreach ($relation as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $tags = get_tags();
                foreach ($tags as $tag) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, (isset($form['tags']) ? $form['tags'] : array()))) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number_of_article'); ?>"><?php _e('Number of article:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('number_of_article'); ?>" name="<?php echo $this->get_field_name('number_of_article'); ?>" autocomplete="off">
                <?php
                $number_of_article = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 15, 20, 25, 30);
                foreach ($number_of_article as $value) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $value, ($value == $form['number_of_article']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'lastest' => __('Lastest', kopa_get_domain()),
                    'popular' => __('Popular by View Count', kopa_get_domain()),
                    'most_comment' => __('Popular by Comment Count', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain()),
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <?php
    }

}

class Kopa_Widget_EiSlideshow extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'ei-slider-widget', 'description' => __('Display a EiSlideshow from posts', kopa_get_domain()));
        $control_ops = array('width' => '500', 'height' => 'auto');
        parent::__construct('kopa_widget_eislideshow', __('Kopa EiSlideshow', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['number_of_article'] = $new_instance['number_of_article'];
        $instance['orderby'] = $new_instance['orderby'];

        $instance['animation'] = $new_instance['animation'];
        $instance['autoplay'] = isset($new_instance['autoplay']) ? $new_instance['autoplay'] : 'false';
        $instance['slideshow_interval'] = (int) $new_instance['slideshow_interval'];
        $instance['speed'] = (int) $new_instance['speed'];
        $instance['titlesFactor'] = floatval($new_instance['titlesFactor']);
        $instance['titlespeed'] = (int) $new_instance['titlespeed'];

        return $instance;
    }

    function form($instance) {
        $default = array(
            'title' => '',
            'categories' => array(),
            'relation' => 'OR',
            'tags' => array(),
            'number_of_article' => 4,
            'orderby' => 'lastest',
            'animation' => 'center',
            'autoplay' => 'true',
            'slideshow_interval' => 3000,
            'speed' => 800,
            'titlesFactor' => 0.60,
            'titlespeed' => 800
        );
        $instance = wp_parse_args((array) $instance, $default);
        $title = strip_tags($instance['title']);

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['number_of_article'] = (int) $instance['number_of_article'];
        $form['orderby'] = $instance['orderby'];

        $form['animation'] = $instance['animation'];
        $form['autoplay'] = $instance['autoplay'];
        $form['slideshow_interval'] = (int) $instance['slideshow_interval'];
        $form['speed'] = (int) $instance['speed'];
        $form['titlesFactor'] = $instance['titlesFactor'];
        $form['titlespeed'] = (int) $instance['titlespeed'];
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <div class="kopa-one-half">
            <p>
                <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                    <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                    <?php
                    $categories = get_categories();
                    foreach ($categories as $category) {
                        printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, (isset($form['categories']) ? $form['categories'] : array()))) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>

            </p>
            <p>
                <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                    <?php
                    $relation = array(
                        'AND' => __('And', kopa_get_domain()),
                        'OR' => __('Or', kopa_get_domain())
                    );
                    foreach ($relation as $value => $title) {
                        printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                    <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                    <?php
                    $tags = get_tags();
                    foreach ($tags as $tag) {
                        printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, (isset($form['tags']) ? $form['tags'] : array()))) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('number_of_article'); ?>"><?php _e('Number of article:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('number_of_article'); ?>" name="<?php echo $this->get_field_name('number_of_article'); ?>" autocomplete="off">
                    <?php
                    $number_of_article = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 15, 20, 25, 30);
                    foreach ($number_of_article as $value) {
                        printf('<option value="%1$s" %3$s>%2$s</option>', $value, $value, ($value == $form['number_of_article']) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                    <?php
                    $orderby = array(
                        'lastest' => __('Lastest', kopa_get_domain()),
                        'popular' => __('Popular by View Count', kopa_get_domain()),
                        'most_comment' => __('Popular by Comment Count', kopa_get_domain()),
                        'random' => __('Random', kopa_get_domain()),
                    );
                    foreach ($orderby as $value => $title) {
                        printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>
            </p>
        </div>

        <div class="kopa-one-half last">
            <p>
                <label for="<?php echo $this->get_field_id('animation'); ?>"><?php _e('Animation:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('animation'); ?>" name="<?php echo $this->get_field_name('animation'); ?>" autocomplete="off">
                    <?php
                    $animation = array(
                        'sides' => __('Sides', kopa_get_domain()),
                        'center' => __('Center', kopa_get_domain())
                    );

                    foreach ($animation as $value => $title) {
                        printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['animation']) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>
            </p>

            <p>
                <input class="" id="<?php echo $this->get_field_id('autoplay'); ?>" name="<?php echo $this->get_field_name('autoplay'); ?>" type="checkbox" value="true" <?php echo ('true' === $form['autoplay']) ? 'checked="checked"' : ''; ?> />
                <label for="<?php echo $this->get_field_id('autoplay'); ?>"><?php _e('Auto Play', kopa_get_domain()); ?></label>                                
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('slideshow_interval'); ?>"><?php _e('Interval for the slideshow:', kopa_get_domain()); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id('slideshow_interval'); ?>" name="<?php echo $this->get_field_name('slideshow_interval'); ?>" type="number" value="<?php echo $form['slideshow_interval']; ?>" />
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('speed'); ?>"><?php _e('Speed for the sliding animation:', kopa_get_domain()); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id('speed'); ?>" name="<?php echo $this->get_field_name('speed'); ?>" type="number" value="<?php echo $form['speed']; ?>" />
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('titlesFactor'); ?>"><?php _e('Percentage of speed for the titles animation:', kopa_get_domain()); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id('titlesFactor'); ?>" name="<?php echo $this->get_field_name('titlesFactor'); ?>" type="number" value="<?php echo $form['titlesFactor']; ?>" />
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('titlespeed'); ?>"><?php _e('Titles animation speed:', kopa_get_domain()); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id('titlespeed'); ?>" name="<?php echo $this->get_field_name('titlespeed'); ?>" type="number" value="<?php echo $form['titlespeed']; ?>" />
            </p>

        </div>
        <div class="kopa-clear"></div>
        <?php
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = esc_attr($instance['relation']);
        $query_args['tags'] = $instance['tags'];
        $query_args['number_of_article'] = (int) $instance['number_of_article'];
        $query_args['orderby'] = $instance['orderby'];

        $slider_args['animation'] = $instance['animation'];
        $slider_args['autoplay'] = $instance['autoplay'];
        $slider_args['slideshow_interval'] = (int) $instance['slideshow_interval'];
        $slider_args['speed'] = (int) $instance['speed'];
        $slider_args['titlesFactor'] = floatval($instance['titlesFactor']);
        $slider_args['titlespeed'] = (int) $instance['titlespeed'];

        echo $before_widget;
        if (!empty($title)) {
            echo $before_title . $title . $after_title;
        }

        $posts = kopa_widget_article_build_query($query_args);

        if ($posts->post_count > 0):
            $thumbnails = array();
            
            ?>
            <div class="ei-slider" <?php foreach ($slider_args as $class => $value) {
                printf('data-%1$s="%2$s"', $class, $value);
            } ?>>
                <ul class="ei-slider-large">
                    <?php
                    while ($posts->have_posts()):
                        $posts->the_post();
                        $post_id = get_the_ID();
                        $post_url = get_permalink();
                        $post_title = get_the_title();

                        if (has_post_thumbnail($post_id)):
                            $feature_image = get_post_thumbnail_id($post_id);
                            $thumbnail = wp_get_attachment_image_src($feature_image, 'kopa-image-size-1');
                            $slide = wp_get_attachment_image_src($feature_image, 'kopa-image-size-2');

                            $thumbnails[$post_id]['title'] = $post_title;
                            $thumbnails[$post_id]['url'] = $post_url;
                            $thumbnails[$post_id]['image'] = $thumbnail[0];
                            ?>
                            <li>
                                <img src="<?php echo $slide[0]; ?>" alt="<?php echo $post_title; ?>"/>
                                <div class="ei-title clearfix">
                                    <h2><a href="<?php echo $post_url; ?>"><?php echo $post_title; ?></a></h2>
                                    <h3><?php the_excerpt(); ?></h3>
                                </div>
                            </li>         
                            <?php
                        endif;
                    endwhile;
                    ?>
                </ul>   
                <ul class="ei-slider-thumbs">
                    <li class="ei-slider-element"></li>
                    <?php foreach ($thumbnails as $thumbnail): ?>
                        <li><a href="<?php echo $thumbnail['url']; ?>"><?php echo $thumbnail['title']; ?></a><img src="<?php echo $thumbnail['image']; ?>" alt="thumb01" /></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php
        endif;

        wp_reset_postdata();
        echo $after_widget;
    }

}


class Kopa_Widget_EiSlideshow_2 extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'ei-slider-widget', 'description' => __('Display a EiSlideshow from posts, it can regognize the post format', kopa_get_domain()));
        $control_ops = array('width' => '500', 'height' => 'auto');
        parent::__construct('kopa_widget_eislideshow_2', __('Kopa EiSlideshow 2', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['number_of_article'] = $new_instance['number_of_article'];
        $instance['orderby'] = $new_instance['orderby'];

        $instance['animation'] = $new_instance['animation'];
        $instance['autoplay'] = isset($new_instance['autoplay']) ? $new_instance['autoplay'] : 'false';
        $instance['slideshow_interval'] = (int) $new_instance['slideshow_interval'];
        $instance['speed'] = (int) $new_instance['speed'];
        $instance['titlesFactor'] = floatval($new_instance['titlesFactor']);
        $instance['titlespeed'] = (int) $new_instance['titlespeed'];

        return $instance;
    }

    function form($instance) {
        $default = array(
            'title' => '',
            'categories' => array(),
            'relation' => 'OR',
            'tags' => array(),
            'number_of_article' => 4,
            'orderby' => 'lastest',
            'animation' => 'center',
            'autoplay' => 'true',
            'slideshow_interval' => 3000,
            'speed' => 800,
            'titlesFactor' => 0.60,
            'titlespeed' => 800
        );
        $instance = wp_parse_args((array) $instance, $default);
        $title = strip_tags($instance['title']);

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['number_of_article'] = (int) $instance['number_of_article'];
        $form['orderby'] = $instance['orderby'];

        $form['animation'] = $instance['animation'];
        $form['autoplay'] = $instance['autoplay'];
        $form['slideshow_interval'] = (int) $instance['slideshow_interval'];
        $form['speed'] = (int) $instance['speed'];
        $form['titlesFactor'] = $instance['titlesFactor'];
        $form['titlespeed'] = (int) $instance['titlespeed'];
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <div class="kopa-one-half">
            <p>
                <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                    <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                    <?php
                    $categories = get_categories();
                    foreach ($categories as $category) {
                        printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, (isset($form['categories']) ? $form['categories'] : array()))) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>

            </p>
            <p>
                <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                    <?php
                    $relation = array(
                        'AND' => __('And', kopa_get_domain()),
                        'OR' => __('Or', kopa_get_domain())
                    );
                    foreach ($relation as $value => $title) {
                        printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                    <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                    <?php
                    $tags = get_tags();
                    foreach ($tags as $tag) {
                        printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, (isset($form['tags']) ? $form['tags'] : array()))) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('number_of_article'); ?>"><?php _e('Number of article:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('number_of_article'); ?>" name="<?php echo $this->get_field_name('number_of_article'); ?>" autocomplete="off">
                    <?php
                    $number_of_article = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 15, 20, 25, 30);
                    foreach ($number_of_article as $value) {
                        printf('<option value="%1$s" %3$s>%2$s</option>', $value, $value, ($value == $form['number_of_article']) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                    <?php
                    $orderby = array(
                        'lastest' => __('Lastest', kopa_get_domain()),
                        'popular' => __('Popular by View Count', kopa_get_domain()),
                        'most_comment' => __('Popular by Comment Count', kopa_get_domain()),
                        'random' => __('Random', kopa_get_domain()),
                    );
                    foreach ($orderby as $value => $title) {
                        printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>
            </p>
        </div>

        <div class="kopa-one-half last">
            <p>
                <label for="<?php echo $this->get_field_id('animation'); ?>"><?php _e('Animation:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('animation'); ?>" name="<?php echo $this->get_field_name('animation'); ?>" autocomplete="off">
                    <?php
                    $animation = array(
                        'sides' => __('Sides', kopa_get_domain()),
                        'center' => __('Center', kopa_get_domain())
                    );

                    foreach ($animation as $value => $title) {
                        printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['animation']) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>
            </p>

            <p>
                <input class="" id="<?php echo $this->get_field_id('autoplay'); ?>" name="<?php echo $this->get_field_name('autoplay'); ?>" type="checkbox" value="true" <?php echo ('true' === $form['autoplay']) ? 'checked="checked"' : ''; ?> />
                <label for="<?php echo $this->get_field_id('autoplay'); ?>"><?php _e('Auto Play', kopa_get_domain()); ?></label>                                
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('slideshow_interval'); ?>"><?php _e('Interval for the slideshow:', kopa_get_domain()); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id('slideshow_interval'); ?>" name="<?php echo $this->get_field_name('slideshow_interval'); ?>" type="number" value="<?php echo $form['slideshow_interval']; ?>" />
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('speed'); ?>"><?php _e('Speed for the sliding animation:', kopa_get_domain()); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id('speed'); ?>" name="<?php echo $this->get_field_name('speed'); ?>" type="number" value="<?php echo $form['speed']; ?>" />
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('titlesFactor'); ?>"><?php _e('Percentage of speed for the titles animation:', kopa_get_domain()); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id('titlesFactor'); ?>" name="<?php echo $this->get_field_name('titlesFactor'); ?>" type="number" value="<?php echo $form['titlesFactor']; ?>" />
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('titlespeed'); ?>"><?php _e('Titles animation speed:', kopa_get_domain()); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id('titlespeed'); ?>" name="<?php echo $this->get_field_name('titlespeed'); ?>" type="number" value="<?php echo $form['titlespeed']; ?>" />
            </p>

        </div>
        <div class="kopa-clear"></div>
        <?php
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = esc_attr($instance['relation']);
        $query_args['tags'] = $instance['tags'];
        $query_args['number_of_article'] = (int) $instance['number_of_article'];
        $query_args['orderby'] = $instance['orderby'];

        $slider_args['animation'] = $instance['animation'];
        $slider_args['autoplay'] = $instance['autoplay'];
        $slider_args['slideshow_interval'] = (int) $instance['slideshow_interval'];
        $slider_args['speed'] = (int) $instance['speed'];
        $slider_args['titlesFactor'] = floatval($instance['titlesFactor']);
        $slider_args['titlespeed'] = (int) $instance['titlespeed'];

        echo $before_widget;
        if (!empty($title)) {
            echo $before_title . $title . $after_title;
        }

        $posts = kopa_widget_article_build_query($query_args);

        if ($posts->post_count > 0):
            $thumbnails = array();
            
            ?>
            <div class="ei-slider" <?php foreach ($slider_args as $class => $value) {
                printf('data-%1$s="%2$s"', $class, $value);
            } ?>>
                <ul class="ei-slider-large">
                    <?php
                    while ($posts->have_posts()):
                        $posts->the_post();
                        $post_id = get_the_ID();
                        $post_url = get_permalink();
                        $post_title = get_the_title();

                        if (has_post_thumbnail($post_id)):
                            $feature_image = get_post_thumbnail_id($post_id);
                            $thumbnail = wp_get_attachment_image_src($feature_image, 'kopa-image-size-1');
                            $slide = wp_get_attachment_image_src($feature_image, 'kopa-image-size-2');

                            $thumbnails[$post_id]['title'] = $post_title;
                            $thumbnails[$post_id]['url'] = $post_url;
                            $thumbnails[$post_id]['image'] = $thumbnail[0];
                            ?>
                            <li>
                                <?php // for image preview post format 
                                switch ( get_post_format() ) :
                                    // gallery post format
                                    case 'gallery':
                                        $gallery = kopa_content_get_gallery( get_the_content() );
                                        $shortcode = $gallery[0]['shortcode'];

                                        // get gallery string ids
                                        preg_match_all('/ids=\"(?:\d+,*)+\"/', $shortcode, $gallery_string_ids);
                                        $gallery_string_ids = $gallery_string_ids[0][0];

                                        // get array of image id
                                        preg_match_all('/\d+/', $gallery_string_ids, $gallery_ids);
                                        $gallery_ids = $gallery_ids[0];

                                        $first_image_id = array_shift($gallery_ids);
                                        $first_image_src = wp_get_attachment_image_src($first_image_id, 'kopa-image-size-2');
                                        
                                        $slug = $this->get_field_id('gallery') . '-' . get_the_ID();
                                        ?>
                                        <a href="<?php echo $first_image_src[0] ?>" rel="prettyPhoto[<?php echo $slug;?>]"><img src="<?php echo $first_image_src[0] ?>"></a>
                                        <?php
                                        
                                        break;

                                    case 'video':
                                        $video = kopa_content_get_video( get_the_content() );
                                        if ( ! empty($video) ) {
                                            $video = array_shift($video);
                                        } ?>

                                        <a href="<?php echo $video['url']; ?>" rel="prettyPhoto"><img src="<?php echo $slide[0]; ?>" alt="<?php echo $post_title; ?>"/></a>

                                        <?php
                                        break;

                                    default: ?>
                                        <img src="<?php echo $slide[0]; ?>" alt="<?php echo $post_title; ?>"/>
                                        <?php
                                        break; 
                                endswitch; ?>
                                <div class="ei-title clearfix">
                                    <h2><a href="<?php echo $post_url; ?>"><?php echo $post_title; ?></a></h2>
                                    <h3><?php the_excerpt(); ?></h3>
                                </div>
                                <?php 
                                    if (get_post_format() == 'gallery') : ?>
                                        <div class="gallery-eislider-preview">
                                            <?php foreach ($gallery_ids as $gallery_id) :
                                                $image_src = wp_get_attachment_image_src($gallery_id, 'kopa-image-size-2'); 
                                                $image_thumb_src = wp_get_attachment_image_src($gallery_id, 'kopa-image-size-5');
                                            ?>
                                                <div class="hover-effect">
                                                    
                                                    <a href="<?php echo $image_src[0]; ?>" rel="prettyPhoto[<?php echo $slug; ?>]">
                                                        <img src="<?php echo $image_thumb_src[0]?>" alt="">
                                                    </a>
                                                    
                                                </div>
                                                
                                        <?php
                                            endforeach; ?>
                                        </div> <!-- .gallery-eislider-preview -->
                                <?php
                                    endif;
                                ?>
                            </li>         
                            <?php
                        endif;
                    endwhile;
                    ?>
                </ul>   
                <ul class="ei-slider-thumbs">
                    <li class="ei-slider-element"></li>
                    <?php foreach ($thumbnails as $thumbnail): ?>
                        <li><a href="<?php echo $thumbnail['url']; ?>"><?php echo $thumbnail['title']; ?></a><img src="<?php echo $thumbnail['image']; ?>" alt="thumb01" /></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php
        endif;

        wp_reset_postdata();
        echo $after_widget;
    }

}

class Kopa_Widget_ArticleList_Carousel extends Kopa_Widget_ArticleList {

    public function __construct() {
        $widget_ops = array('classname' => 'featured-widget', 'description' => __('Display list of articles filter by categories (and/or) tags (carousel effect)', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_articlelist_carousel', __('Kopa Widget ArticleList Carousel', kopa_get_domain()), $widget_ops, $control_ops);
    }

    public function display($query_args) {
        $posts = kopa_widget_article_build_query($query_args);
        if ($posts->post_count > 0):
            ?>
            <div class="list-carousel responsive">
                <ul class="kopa_widget_articlelist_carousel" data-prev-id="<?php echo $this->get_field_id('prev'); ?>" data-next-id="<?php echo $this->get_field_id('next'); ?>" data-pagination-id="<?php echo $this->get_field_id('pagination'); ?>">     
                    <?php
                    while ($posts->have_posts()):
                        $posts->the_post();
                        $post_id = get_the_ID();
                        $post_url = get_permalink();
                        $post_title = get_the_title();
                        ?>
                        <li style="width: 250px;">
                            <article class="entry-item clearfix">
                                <div class="entry-thumb hover-effect">
                                    <div class="mask">
                                        <a class="link-detail" href="<?php echo $post_url; ?>" data-icon="&#xe0c2;"></a>
                                    </div>
                                    <?php
                                    if (has_post_thumbnail()):
                                        the_post_thumbnail('kopa-image-size-1');
                                    else:
                                        printf('<img src="%1$s">', get_template_directory_uri() . '/images/kopa-image-size-1.png');
                                    endif;
                                    ?>
                                </div>
                                <div class="entry-content">
                                    <p class="entry-meta">
                                        <span class="entry-date"><span class="icon-clock-4 entry-icon"></span><?php echo get_the_date(); ?></span>
                                        <span class="entry-comment"><span class="icon-bubbles-4 entry-icon"></span><?php comments_popup_link(__('No Comment', kopa_get_domain()), __('1 Comment', kopa_get_domain()), __('% Comments', kopa_get_domain()), '', __('Comments Off', kopa_get_domain())); ?></span>
                                    </p>
                                    <h3 class="entry-title"><a href="<?php echo $post_url; ?>"><?php echo $post_title; ?></a></h3>
                                    <a href="<?php echo $post_url; ?>" class="more-link"><?php _e('Read more &raquo;', kopa_get_domain()); ?></a>                                            
                                </div><!--entry-content-->
                            </article><!--entry-item-->
                        </li>
                        <?php
                    endwhile;
                    ?>
                </ul>
                <div class="clearfix"></div>
                <div class="carousel-nav clearfix">
                    <a id="<?php echo $this->get_field_id('prev'); ?>" class="carousel-prev" href="#">&lt;</a>
                    <a id="<?php echo $this->get_field_id('next'); ?>" class="carousel-next" href="#">&gt;</a>
                </div><!--end:carousel-nav-->
                <div id="<?php echo $this->get_field_id('pagination'); ?>" class="pagination"></div>
            </div>
            <?php
        endif;
        wp_reset_postdata();
    }

}

class Kopa_Widget_ArticleList_Carousel_2 extends Kopa_Widget_ArticleList {

    public function __construct() {
        $widget_ops = array('classname' => 'featured-widget', 'description' => __('Display list of articles filter by categories (and/or) tags (carousel effect), it can recognize the post format', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_articlelist_carousel_2', __('Kopa Widget ArticleList Carousel 2', kopa_get_domain()), $widget_ops, $control_ops);
    }

    public function display($query_args) {
        global $post;
        $posts = kopa_widget_article_build_query($query_args);
        if ($posts->post_count > 0):
            ?>
            <div class="list-carousel responsive">
                <ul  class="kopa_widget_articlelist_carousel" data-prev-id="<?php echo $this->get_field_id('prev'); ?>" data-next-id="<?php echo $this->get_field_id('next'); ?>" data-pagination-id="<?php echo$this->get_field_id('pagination'); ?>">     
                    <?php
                    while ($posts->have_posts()):
                        $posts->the_post();
                        $post_id = get_the_ID();
                        $post_url = get_permalink();
                        $post_title = get_the_title();
                        ?>
                        <li style="width: 250px;">
                            <article class="entry-item clearfix">
                                <div class="entry-thumb hover-effect">
                                    <div class="mask">
                                        <?php 
                                            switch ( get_post_format() ) :
                                                // video post format
                                                case 'video': 
                                                    $video = kopa_content_get_video( get_the_content() );
                                                    if ( ! empty($video) ) :
                                                        $video = array_shift($video);
                                                ?>
                                                    <a class="link-detail" href="<?php echo $video['url']; ?>" rel="prettyPhoto" data-icon="&#xe022;"></a>
                                                <?php
                                                    endif;
                                                    break;

                                                // gallery post format
                                                case 'gallery':
                                                    $gallery = kopa_content_get_gallery( get_the_content() );
                                                    $shortcode = $gallery[0]['shortcode'];

                                                    // get gallery string ids
                                                    preg_match_all('/ids=\"(?:\d+,*)+\"/', $shortcode, $gallery_string_ids);
                                                    $gallery_string_ids = $gallery_string_ids[0][0];

                                                    // get array of image id
                                                    preg_match_all('/\d+/', $gallery_string_ids, $gallery_ids);
                                                    $gallery_ids = $gallery_ids[0];

                                                    $first_image_id = array_shift($gallery_ids);
                                                    $first_image_src = wp_get_attachment_image_src($first_image_id, 'kopa-image-size-2');
                                                    
                                                    $slug = $this->get_field_id('gallery') . '-' . get_the_ID();

                                                ?>
                                                    <a class="link-detail" href="<?php echo $first_image_src[0]; ?>" rel="prettyPhoto[<?php echo $slug; ?>]" data-icon="&#xe01d;"></a>
                                                <?php
                                                    foreach ($gallery_ids as $gallery_id) :
                                                        $image_src = wp_get_attachment_image_src($gallery_id, 'kopa-image-size-2'); ?>
                                                        <a style="display: none" href="<?php echo $image_src[0]; ?>" rel="prettyPhoto[<?php echo $slug; ?>]"></a>
                                                        <?php 
                                                    endforeach;

                                                    break;

                                                // audio post format
                                                case 'audio':
                                                ?>
                                                    <a class="link-detail" href="<?php echo $post_url; ?>" data-icon="&#xe020;"></a>
                                                <?php
                                                    break;

                                                // quote post format
                                                case 'quote':
                                                ?>
                                                    <a class="link-detail" href="<?php echo $post_url; ?>" data-icon="&#xe075;"></a>
                                                <?php
                                                    break;

                                                // default post format                                                
                                                default: ?>
                                                    <a class="link-detail" href="<?php echo $post_url; ?>" data-icon="&#xe0c2;"></a>
                                                <?php 
                                                    break;
                                            endswitch;
                                        ?>
                                    </div> <!-- .mask -->
                                    <?php
                                    if (has_post_thumbnail()):
                                        the_post_thumbnail('kopa-image-size-1');
                                    elseif (get_post_format() == 'video' && isset($video['type']) && isset($video['url'])) :
                                        printf('<img src="%1$s" alt="">', kopa_get_video_thumbnails_url($video['type'], $video['url']));
                                    else: 
                                        printf('<img src="%1$s">', get_template_directory_uri() . '/images/kopa-image-size-1.png');
                                    endif;
                                    ?>
                                </div>
                                <div class="entry-content">
                                    <p class="entry-meta">
                                        <span class="entry-date"><span class="icon-clock-4 entry-icon"></span><?php echo get_the_date(); ?></span>
                                        <span class="entry-comment"><span class="icon-bubbles-4 entry-icon"></span><?php comments_popup_link(__('No Comment', kopa_get_domain()), __('1 Comment', kopa_get_domain()), __('% Comments', kopa_get_domain()), '', __('Comments Off', kopa_get_domain())); ?></span>
                                    </p>
                                    <h3 class="entry-title"><a href="<?php echo $post_url; ?>"><?php echo $post_title; ?></a></h3>
                                    <a href="<?php echo $post_url; ?>" class="more-link"><?php _e('Read more &raquo;', kopa_get_domain()); ?></a>                                            
                                </div><!--entry-content-->
                            </article><!--entry-item-->
                        </li>
                        <?php
                    endwhile;
                    ?>
                </ul>
                <div class="clearfix"></div>
                <div class="carousel-nav clearfix">
                    <a id="<?php echo $this->get_field_id('prev'); ?>" class="carousel-prev" href="#">&lt;</a>
                    <a id="<?php echo $this->get_field_id('next'); ?>" class="carousel-next" href="#">&gt;</a>
                </div><!--end:carousel-nav-->
                <div id="<?php echo $this->get_field_id('pagination'); ?>" class="pagination"></div>
            </div>
            <?php
        endif;
        wp_reset_postdata();
    }

}

class Kopa_Widget_ArticleList_Large_Thumbnail extends Kopa_Widget_ArticleList {

    public function __construct() {
        $widget_ops = array('classname' => 'kopa_widget_articlelist_large_thumbnail clearfix', 'description' => __('Display list of articles filter by categories (and/or) tags', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_articlelist_large_thumbnail', __('Kopa Article List Large Thumbnail', kopa_get_domain()), $widget_ops, $control_ops);
    }

    public function display($query_args) {
        $posts = kopa_widget_article_build_query($query_args);
        if ($posts->post_count > 0):
            $loop_index = 0;
            ?>
            <?php
            while ($posts->have_posts()):
                $posts->the_post();
                $post_url = get_permalink();
                $post_title = get_the_title();

                if (0 === $loop_index) {
                    ?>
                    <article class="entry-item standard-post clearfix">
                        <div class="entry-thumb hover-effect">
                            <?php if (has_post_thumbnail()): ?>
                                <div class="mask">
                                    <a class="link-detail" href="<?php echo $post_url; ?>" data-icon="&#xe0c2;"></a>
                                </div>
                                <?php the_post_thumbnail('kopa-image-size-1'); ?> 
                            <?php endif; ?>
                        </div>
                        <div class="entry-content">
                            <p class="entry-meta">
                                <span class="entry-date"><span class="icon-clock-4 entry-icon"></span><?php echo get_the_date(); ?></span>
                                <span class="entry-comment"><span class="icon-bubbles-4 entry-icon"></span><?php comments_popup_link(__('No Comment', kopa_get_domain()), __('1 Comment', kopa_get_domain()), __('% Comments', kopa_get_domain()), '', __('Comments Off', kopa_get_domain())); ?></span>
                            </p>
                            <h3 class="entry-title"><a href="<?php echo $post_url; ?>"><?php echo $post_title; ?></a></h3>
                            <p><?php the_excerpt(); ?></p>
                            <a href="<?php echo $post_url; ?>" class="more-link"><?php _e('Read more &raquo;', kopa_get_domain()); ?></a>
                        </div>
                    </article>
                    <?php
                    if ($posts->post_count > 1)
                        echo '<ul class="older-post">';
                } else {
                    ?>
                    <li>
                        <article class="entry-item standard-post clearfix">
                            <?php if (has_post_thumbnail()): ?>
                                <div class="entry-thumb">
                                    <a href="<?php echo $post_url; ?>"><?php the_post_thumbnail('kopa-image-size-0'); ?></a>
                                </div>
                            <?php endif; ?>
                            <div class="entry-content">
                                <p class="entry-meta">
                                    <span class="entry-date"><span class="icon-clock-4 entry-icon"></span><?php echo get_the_date(); ?></span>
                                    <span class="entry-comment"><span class="icon-bubbles-4 entry-icon"></span><?php comments_popup_link(__('No Comment', kopa_get_domain()), __('1 Comment', kopa_get_domain()), __('% Comments', kopa_get_domain()), '', __('Comments Off', kopa_get_domain())); ?></span>
                                </p>
                                <h3 class="entry-title"><a href="<?php echo $post_url; ?>"><?php echo $post_title; ?></a></h3>
                                <p><?php the_excerpt(); ?></p>
                                <a href="<?php echo $post_url; ?>" class="more-link"><?php _e('Read more &raquo;', kopa_get_domain()); ?></a>
                            </div>
                        </article>
                    </li>
                    <?php
                }

                $loop_index++;
            endwhile;
            if ($posts->post_count > 1)
                echo '</ul>';
            ?>
            <?php
        endif;
        wp_reset_postdata();
    }

}

class Kopa_Widget_ArticleList_Medium_Thumbnail extends Kopa_Widget_ArticleList {

    public function __construct() {
        $widget_ops = array('classname' => 'kopa_widget_articlelist_medium_thumbnail clearfix', 'description' => __('Display list of articles filter by categories (and/or) tags', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_articlelist_medium_thumbnail', __('Kopa Article List Medium Thumbnail', kopa_get_domain()), $widget_ops, $control_ops);
    }

    public function display($query_args) {

        $posts = kopa_widget_article_build_query($query_args);
        if ($posts->post_count > 0):
            global $post;
            ?>
            <ul>
                <?php
                while ($posts->have_posts()):
                    $posts->the_post();
                    $post_url = get_permalink();
                    $post_title = get_the_title();
                    $post_format = get_post_format();
                    $article_classes = array();

                    $post_format_icon = '&#xe034;';
                    switch ($post_format) {
                        case 'audio':
                            $post_format_icon = '&#xe020;';
                            break;
                        case 'video':
                            $post_format_icon = '&#xe023;';
                            break;
                        case 'gallery':
                            $post_format_icon = '&#xe01c;';
                            break;
                        case 'quote':
                            $post_format_icon = '&#xe075;';
                            $article_classes[] = 'article-no-thumb';
                            break;
                        case 'aside':
                            $post_format_icon = '&#xe034;';
                            $article_classes[] = 'article-no-thumb';
                            break;
                        default:
                            $post_format_icon = '&#xe034;';
                            if (!has_post_thumbnail()) {
                                $article_classes[] = 'article-no-thumb';
                            }
                            break;
                    }
                    $article_classes[] = 'entry-item';
                    $article_classes[] = 'standard-post';
                    $article_classes[] = 'clearfix';
                    ?>
                    <li>
                        <article class="<?php echo implode(' ', $article_classes); ?>">
                            <?php
                            switch ($post_format):
                                case 'gallery':
                                    $gallery = kopa_content_get_gallery($post->post_content);
                                    if ($gallery) {
                                        echo '<div class="entry-thumb">';
                                        echo do_shortcode($gallery[0]['shortcode']);
                                        echo '</div>';
                                    }
                                    break;
                                case 'video':
                                    $video = kopa_content_get_video($post->post_content);
                                    if ($video) {
                                        echo '<div class="entry-thumb hover-effect">';
                                        if ('disable' === get_option('kopa_theme_options_play_video_in_lightbox', 'disable')) {
                                            echo do_shortcode($video[0]['shortcode']);
                                        } else {
                                            ?>                                
                                            <div class="mask">
                                                <a class="link-detail" data-icon="<?php echo $post_format_icon; ?>" rel="prettyPhoto[blog-videos]" href="<?php echo $video[0]['url']; ?>"></a>
                                            </div>
                                            <?php
                                            if (has_post_thumbnail()):
                                                the_post_thumbnail('kopa-image-size-1');
                                            else:
                                                printf('<img src="%1$s">', kopa_get_video_thumbnails_url($video[0]['type'], $video[0]['url']));
                                            endif;
                                        }
                                        echo '</div>';
                                    }
                                    break;
                                case 'audio':
                                    $audio = kopa_content_get_audio($post->post_content);
                                    if ($audio) {
                                        echo '<div class="entry-thumb hover-effect">';
                                        echo do_shortcode($audio[0]['shortcode']);
                                        echo '</div>';
                                    }
                                    break;
                                default:
                                    if (has_post_thumbnail()):
                                        ?>
                                        <div class="entry-thumb hover-effect">
                                            <div class="mask">
                                                <a class="link-detail" data-icon="&#xe0c2;" href="<?php echo $post_url; ?>"></a>
                                            </div>
                                            <?php the_post_thumbnail('kopa-image-size-1'); ?>
                                        </div>
                                        <?php
                                    endif;
                                    break;
                            endswitch;
                            ?>                         
                            <div class="entry-content">
                                <p class="entry-meta">
                                    <span class="entry-date"><span class="icon-clock-4 entry-icon"></span><?php echo get_the_date(); ?></span>
                                    <span class="entry-comment"><span class="icon-bubbles-4 entry-icon"></span><?php comments_popup_link(__('No Comment', kopa_get_domain()), __('1 Comment', kopa_get_domain()), __('% Comments', kopa_get_domain()), '', __('Comments Off', kopa_get_domain())); ?></span>
                                </p>
                                <h3 class="entry-title"><a href="<?php echo $post_url; ?>"><?php echo $post_title; ?></a></h3>
                                <p><?php the_excerpt(); ?></p>
                                <a href="<?php echo $post_url; ?>" class="more-link"><?php _e('Read more &raquo;', kopa_get_domain()); ?></a>
                            </div>
                        </article>
                    </li>
                    <?php
                endwhile;
                ?>
            </ul>
            <?php
        endif;
        wp_reset_postdata();
    }

}

class Kopa_Widget_ArticleList_Small_Thumbnail extends Kopa_Widget_ArticleList {

    public function __construct() {
        $widget_ops = array('classname' => 'kopa_widget_articlelist_small_thumbnail clearfix', 'description' => __('Display list of articles filter by categories (and/or) tags', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_articlelist_small_thumbnail', __('Kopa Article List Small Thumbnail', kopa_get_domain()), $widget_ops, $control_ops);
    }

    public function display($query_args) {
        $posts = kopa_widget_article_build_query($query_args);
        if ($posts->post_count > 0):
            ?>
            <ul class="kp-popular-post">                        
                <?php
                while ($posts->have_posts()):
                    $posts->the_post();
                    $post_url = get_permalink();
                    $post_title = get_the_title();
                    ?>
                    <li>
                        <article class="clearfix">
                            <?php if (has_post_thumbnail()): ?>
                                <a href="<?php echo $post_url; ?>" class="entry-thumb"><?php the_post_thumbnail('kopa-image-size-0'); ?></a>
                            <?php endif; ?>
                            <div class="entry-content">
                                <h4 class="entry-title"><a href="<?php echo $post_url; ?>"><?php echo $post_title; ?></a></h4>
                                <span class="entry-date"><span class="icon-clock-4 entry-icon"></span><?php echo get_the_date(); ?></span>
                                <span class="entry-comment"><span class="icon-bubbles-4 entry-icon"></span><?php comments_popup_link(__('0 Comments', kopa_get_domain()), __('1 Comment', kopa_get_domain()), __('% Comments', kopa_get_domain()), '', __('Comments Off', kopa_get_domain())); ?></span>
                            </div>
                        </article>
                    </li>       
                    <?php
                endwhile;
                ?>
            </ul>
            <?php
        endif;
        wp_reset_postdata();
    }

}

class Kopa_Widget_Gallery extends Kopa_Widget_ArticleList {

    public function __construct() {
        $widget_ops = array('classname' => 'kopa_widget_articlelist_only_small_thumbnail kp-gallery-widget clearfix', 'description' => __('Display list of Gallery postype articles filtered by categories (and/or) tags', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_articlelist_only_small_thumbnail', __('Kopa Gallery Widget', kopa_get_domain()), $widget_ops, $control_ops);
    }

    public function display($query_args) {

        $posts = kopa_widget_article_build_query($query_args);
        if ($posts->post_count > 0):
            global $post;
            ?>
            <ul class="clearfix">              
                <?php
                while ($posts->have_posts()):
                    $posts->the_post();
                    $gallery = kopa_content_get_gallery($post->post_content);
                    if ($gallery) {
                        $shortcode = substr_replace($gallery[0]['shortcode'], ' display_type = 3]', strlen($gallery[0]['shortcode']) - 1, strlen($gallery[0]['shortcode']));
                        echo do_shortcode($shortcode);
                    }
                endwhile;
                ?>
            </ul>
            <?php
        endif;
        wp_reset_postdata();
    }

}

class Kopa_Widget_Video extends WP_Widget {

    public function __construct() {
        $widget_ops = array('classname' => 'kopa_widget_video kp-video-widget clearfix', 'description' => __('Display list of videos filter by categories (and/or) tags', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_video', __('Kopa Video', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number_of_article'] = (int) $new_instance['number_of_article'];
        $instance['orderby'] = $new_instance['orderby'];
        return $instance;
    }

    function form($instance) {
        $default = array(
            'title' => '',
            'number_of_article' => 7,
            'orderby' => 'lastest'
        );
        $instance = wp_parse_args((array) $instance, $default);

        $title = strip_tags($instance['title']);
        $form['number_of_article'] = (int) $instance['number_of_article'];
        $form['orderby'] = $instance['orderby'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number_of_article'); ?>"><?php _e('Number of article:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('number_of_article'); ?>" name="<?php echo $this->get_field_name('number_of_article'); ?>" autocomplete="off">
                <?php
                $number_of_article = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 15, 20, 25, 30);
                foreach ($number_of_article as $value) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $value, ($value == $form['number_of_article']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'lastest' => __('Lastest', kopa_get_domain()),
                    'popular' => __('Popular by View Count', kopa_get_domain()),
                    'most_comment' => __('Popular by Comment Count', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain()),
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>        
        <?php
    }

    function widget($args, $instance) {
        global $post;
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $number_of_article = (int) $instance['number_of_article'];
        $orderby = $instance['orderby'];

        $query_args = array(
            'post_type' => array('post'),
            'posts_per_page' => $number_of_article,
            'tax_query' => array(
                array(
                    'taxonomy' => 'post_format',
                    'field' => 'slug',
                    'terms' => array('post-format-video')
                )
            )
        );

        switch ($orderby) {
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

        echo $before_widget;
        if (!empty($title)) {
            echo $before_title . $title . $after_title;
        }

        $posts = new WP_Query($query_args);

        if ($posts->post_count > 0):
            echo '<ul>';
            while ($posts->have_posts()):
                $posts->the_post();
                $videos = kopa_content_get_video($post->post_content);
                if ($videos):
                    foreach ($videos as $video):
                        echo '<li><div class="entry-thumb hover-effect">';
                        if ('disable' === get_option('kopa_theme_options_play_video_in_lightbox', 'disable')) {
                            echo do_shortcode($video['shortcode']);
                        } else {
                            ?>                                
                            <div class="mask">
                                <a class="link-detail" data-icon="&#xe023;" rel="prettyPhoto[blog-videos]" href="<?php echo $video['url']; ?>"></a>
                            </div>
                            <?php
                            if (has_post_thumbnail()):
                                the_post_thumbnail('kopa-image-size-1');
                            else:
                                printf('<img src="%1$s">', kopa_get_video_thumbnails_url($video['type'], $video['url']));
                            endif;
                        }
                        echo '</div></li>';
                    endforeach;
                endif;
            endwhile;
            echo '</ul>';

        endif;

        wp_reset_postdata();
        echo $after_widget;
    }

}

class Kopa_Widget_Recent_Comments extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'kopa_widget_recent_comments', 'description' => __('Display recent comments', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_recent_comments', __('Kopa Recent Comments', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $number = !empty($instance['number']) ? (int) $instance['number'] : 5;
        $limit = !empty($instance['limit']) ? (int) $instance['limit'] : 100;
        $show_avatar = $instance['show_avatar'];

        echo $before_widget;
        if (!empty($title)) {
            echo $before_title . $title . $after_title;
        }

        $comments = get_comments(array('number' => $number));

        if ($comments) {
            ?>
            <ul class="kp-latest-comment">
                <?php
                foreach ($comments as $comment):
                    $comment_content = $comment->comment_content;
                    if (strlen($comment_content) > $limit)
                        $comment_content = substr(strip_tags($comment->comment_content), 0, $limit);
                    ?>
                    <li>
                        <article class="clearfix">
                            <?php if ('true' == $show_avatar): ?>
                                <a class="entry-thumb" href="<?php echo get_permalink($comment->comment_post_ID); ?>">
                                    <?php echo get_avatar($comment->comment_author_email, 60); ?>                                
                                </a>
                            <?php endif; ?>

                            <div class="entry-content clearfix">
                                <a class="comment-name" href="<?php echo get_permalink($comment->comment_post_ID); ?>"><?php printf(__('%1$s says:', kopa_get_domain()), $comment->comment_author); ?></a>
                                <p><?php echo $comment_content; ?></p>
                            </div>
                        </article>
                    </li>
                    <?php
                endforeach;
                ?>
            </ul>
            <?php
        }

        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = (int) strip_tags($new_instance['number']);
        $instance['limit'] = (int) strip_tags($new_instance['limit']);
        $instance['show_avatar'] = isset($new_instance['show_avatar']) ? $new_instance['show_avatar'] : 'false';
        return $instance;
    }

    function form($instance) {
        $instance = wp_parse_args((array) $instance, array('title' => '', 'number' => 5, 'limit' => 100, 'show_avatar' => 'true'));
        $title = strip_tags($instance['title']);
        $number = (int) strip_tags($instance['number']);
        $limit = (int) strip_tags($instance['limit']);
        $show_avatar = $instance['show_avatar'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>  
        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of comments to show:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo esc_attr($number); ?>" />
        </p> 
        <p>
            <label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Character limit of comment content', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo esc_attr($limit); ?>" />
        </p>   
        <p>
            <input class="" id="<?php echo $this->get_field_id('show_avatar'); ?>" name="<?php echo $this->get_field_name('show_avatar'); ?>" type="checkbox" value="true" <?php echo ('true' === $show_avatar) ? 'checked="checked"' : ''; ?> />
            <label for="<?php echo $this->get_field_id('show_avatar'); ?>"><?php _e('Show Avatar', kopa_get_domain()); ?></label>                                
        </p>
        <?php
    }

}

class Kopa_Widget_Text extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'kopa_widget_text', 'description' => __('Arbitrary text, HTML or shortcodes', kopa_get_domain()));
        $control_ops = array('width' => 600, 'height' => 400);
        parent::__construct('kopa_widget_text', __('Kopa Text', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $text = apply_filters('widget_text', empty($instance['text']) ? '' : $instance['text'], $instance);

        echo $before_widget;
        if (!empty($title)) {
            echo $before_title . $title . $after_title;
        }
        ?>
        <?php echo!empty($instance['filter']) ? wpautop($text) : $text; ?>
        <?php
        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        if (current_user_can('unfiltered_html'))
            $instance['text'] = $new_instance['text'];
        else
            $instance['text'] = stripslashes(wp_filter_post_kses(addslashes($new_instance['text'])));
        $instance['filter'] = isset($new_instance['filter']);
        return $instance;
    }

    function form($instance) {
        $instance = wp_parse_args((array) $instance, array('title' => '', 'text' => ''));
        $title = strip_tags($instance['title']);
        $text = esc_textarea($instance['text']);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>        
        <ul class="kopa_shortcode_icons">
            <?php
            $shortcodes = array(
                'one_half' => 'One Half Column',
                'one_third' => 'One Thirtd Column',
                'two_third' => 'Two Third Column',
                'one_fourth' => 'One Fourth Column',
                'three_fourth' => 'Three Fourth Column',
                'dropcaps' => 'Add Dropcaps Text',
                'button' => 'Add A Button',
                'alert' => 'Add A Alert Box',
                'tabs' => 'Add A Tabs Content',
                'accordions' => 'Add A Accordions Content',
                'toggle' => 'Add A Toggle Content',
                'contact_form' => 'Add A Contact Form',
                'posts_lastest' => 'Add A List Lastest Post',
                'posts_popular' => 'Add A List Popular Post',
                'posts_most_comment' => 'Add A List Most Comment Post',
                'posts_random' => 'Add A List Random Post',
                'youtube' => 'Add A Yoube Video Box',
                'vimeo' => 'Add A Vimeo Video Box'
            );
            foreach ($shortcodes as $rel => $title):
                ?>
                <li>
                    <a onclick="return kopa_shortcode_icon_click('<?php echo $rel; ?>', jQuery('#<?php echo $this->get_field_id('text'); ?>'));" href="#" class="<?php echo "kopa-icon-{$rel}"; ?>" rel="<?php echo $rel; ?>" title="<?php echo $title; ?>"></a>
                </li>
            <?php endforeach; ?>
        </ul>        
        <textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
        <p>
            <input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs', kopa_get_domain()); ?></label>
        </p>
        <?php
    }

}

class Kopa_Widget_Twitter extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'kopa_widget_twitter', 'description' => __('Display lastets tweets', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_twitter', __('Kopa Tweets', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $account = strip_tags($instance['account']);
        $limit = (int) $instance['limit'];

        echo $before_widget;
        if (!empty($title)) {
            echo $before_title . $title . $after_title;
        }
        ?>
        <div class="twitter-widget">
            <div class='twitter_outer'>
                <input type='hidden' class='tweet_id' value='<?php echo $account; ?>'>
                <input type='hidden' class='tweet_count' value='<?php echo $limit; ?>'>
                <div class='twitter_inner clearfix'></div>
            </div><!--twitter_outer-->
        </div><!--twitter-widget-->
        <?php
        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['account'] = strip_tags($new_instance['account']);
        $instance['limit'] = (int) $new_instance['limit'];

        return $instance;
    }

    function form($instance) {
        $instance = wp_parse_args((array) $instance, array('title' => '', 'account' => '', 'limit' => 4));
        $title = strip_tags($instance['title']);
        $account = strip_tags($instance['account']);
        $limit = (int) $instance['limit'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>     
        <p>
            <label for="<?php echo $this->get_field_id('account'); ?>"><?php _e('Account:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('account'); ?>" name="<?php echo $this->get_field_name('account'); ?>" type="text" value="<?php echo esc_attr($account); ?>" />
        </p>  
        <p>
            <label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Limit:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" autocomplete="off">
                <?php
                $limits = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 15, 20, 25, 30);
                foreach ($limits as $value) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $value, ($value == $limit) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <?php
    }

}

class Kopa_Widget_Flickr extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'kopa_widget_flickr', 'description' => __('Display lastets flickr photos', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_flickr', __('Kopa Flickr', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $account = strip_tags($instance['account']);
        $limit = (int) $instance['limit'];

        echo $before_widget;
        if (!empty($title)) {
            echo $before_title . $title . $after_title;
        }
        ?>
        <div class="flickr-wrap clearfix">       
            <input type='hidden' class='flickr_id' value='<?php echo $account; ?>'>
            <input type='hidden' class='flickr_limit' value='<?php echo $limit; ?>'>
            <ul class="kopa-flickr-widget clearfix"></ul>
        </div>
        <?php
        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['account'] = strip_tags($new_instance['account']);
        $instance['limit'] = (int) $new_instance['limit'];
        return $instance;
    }

    function form($instance) {
        $instance = wp_parse_args((array) $instance, array('title' => '', 'account' => '', 'limit' => 4));
        $title = strip_tags($instance['title']);
        $account = strip_tags($instance['account']);
        $limit = (int) $instance['limit'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>     
        <p>
            <label for="<?php echo $this->get_field_id('account'); ?>"><?php _e('Account:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('account'); ?>" name="<?php echo $this->get_field_name('account'); ?>" type="text" value="<?php echo esc_attr($account); ?>" />
        </p>  
        <p>
            <label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Limit:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" autocomplete="off">
                <?php
                $limits = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 15, 20, 25, 30);
                foreach ($limits as $value) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $value, ($value == $limit) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <?php
    }

}

class Kopa_Widget_Timeline_Articles extends WP_Widget {

    public function __construct() {
        $widget_ops = array('classname' => 'kopa_widget_timeline_article clearfix', 'description' => __('Display timeline article or portfolio filter by categories (and/or) tags', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_timeline_articles', __('Kopa Timeline Articles', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title_1'] = strip_tags($new_instance['title_1']);
        $instance['number_of_article_1'] = (int) $new_instance['number_of_article_1'];
        $instance['orderby_1'] = $new_instance['orderby_1'];
        $instance['categories_1'] = (empty($new_instance['categories_1'])) ? array() : array_filter($new_instance['categories_1']);
        $instance['relation_1'] = $new_instance['relation_1'];
        $instance['tags_1'] = (empty($new_instance['tags_1'])) ? array() : array_filter($new_instance['tags_1']);
        $instance['display_type_1'] = $new_instance['display_type_1'];
        return $instance;
    }

    function form($instance) {
        $default = array(
            'title_1' => '',
            'display_type_1' => 'blog',
            'number_of_article_1' => 7,
            'orderby_1' => 'lastest',
            'relation_1' => 'OR',
        );
        $instance = wp_parse_args((array) $instance, $default);

        $title_1 = strip_tags($instance['title_1']);
        $form['number_of_article_1'] = (int) $instance['number_of_article_1'];
        $form['orderby_1'] = $instance['orderby_1'];
        $form['categories_1'] = $instance['categories_1'];
        $form['relation_1'] = $instance['relation_1'];
        $form['tags_1'] = $instance['tags_1'];
        $form['display_type_1'] = $instance['display_type_1'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title_1'); ?>"><?php _e('First tab title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title_1'); ?>" name="<?php echo $this->get_field_name('title_1'); ?>" type="text" value="<?php echo esc_attr($title_1); ?>" />
        </p>        
        <p>
            <label for="<?php echo $this->get_field_id('display_type_1'); ?>"><?php _e('Display type:', kopa_get_domain()); ?></label>
            <select class="widefat kopa-wdt-select-timeline" id="<?php echo $this->get_field_id('display_type_1'); ?>" name="<?php echo $this->get_field_name('display_type_1'); ?>" autocomplete="off" onchange="kopa_change_timeline(jQuery(this));">
                <?php
                $display_type_1 = array(
                    'blog' => __('Timeline Blog', kopa_get_domain()),
                    'portfolio' => __('Portfolio', kopa_get_domain()),
                );
                foreach ($display_type_1 as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['display_type_1']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p class="kopa-wdt-category">
            <label for="<?php echo $this->get_field_id('categories_1'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories_1'); ?>" name="<?php echo $this->get_field_name('categories_1'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories_1 = get_categories();
                foreach ($categories_1 as $category) {
                    echo '<option value="'.$category->term_id.'" ';
                    if(isset($form['categories_1'])){ 
                        if(in_array($category->term_id, $form['categories_1']))echo 'selected="selected"';                        
                    }
                    echo '>'.$category->name.' ('. $category->count.')</option>';
                }
                ?>
            </select>

        </p>
        <p class="kopa-wdt-and-or">
            <label for="<?php echo $this->get_field_id('relation_1'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('relation_1'); ?>" name="<?php echo $this->get_field_name('relation_1'); ?>" autocomplete="off">
                <?php
                $relation = array(
                    'AND' => __('And', kopa_get_domain()),
                    'OR' => __('Or', kopa_get_domain())
                );
                foreach ($relation as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation_1']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p class="kopa-wdt-tags">
            <label for="<?php echo $this->get_field_id('tags_1'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('tags_1'); ?>" name="<?php echo $this->get_field_name('tags_1'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $tags_1 = get_tags();
                foreach ($tags_1 as $tag) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, (isset($form['tags_1']) ? $form['tags_1'] : array()))) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p class="kopa-wdt-number-of-article">
            <label for="<?php echo $this->get_field_id('number_of_article_1'); ?>"><?php _e('Number of article:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('number_of_article_1'); ?>" name="<?php echo $this->get_field_name('number_of_article_1'); ?>" autocomplete="off">
                <?php
                $number_of_article = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 15, 20, 25, 30);
                foreach ($number_of_article as $value) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $value, ($value == $form['number_of_article_1']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p class="kopa-wdt-order-by">
            <label for="<?php echo $this->get_field_id('orderby_1'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby_1'); ?>" name="<?php echo $this->get_field_name('orderby_1'); ?>" autocomplete="off">
                <?php
                $orderby_1 = array(
                    'lastest' => __('Lastest', kopa_get_domain()),
                    'popular' => __('Popular by View Count', kopa_get_domain()),
                    'most_comment' => __('Popular by Comment Count', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain()),
                );
                foreach ($orderby_1 as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby_1']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>

        <?php
    }

    function widget($args, $instance) {
        global $post;
        extract($args);
        $article_tabs = array(
            'title' => $instance['title_1'],
            'display_type' => $instance['display_type_1'],
            'categories' => $instance['categories_1'],
            'relation' => esc_attr($instance['relation_1']),
            'tags' => $instance['tags_1'],
            'number_of_article' => (int) $instance['number_of_article_1'],
            'orderby' => $instance['orderby_1']
        );
        $title_class = '';
        $tab_content_class = '';
        if ($article_tabs['display_type'] == 'portfolio') {
            $tab_content_class = ' portfolio-content';
        }
        echo '<div class="entry-list">';
        echo '<h2 class="kp-title"><span data-icon="';
        if ($article_tabs['display_type'] == 'blog') {
            echo '&#xe014;';
        } else {
            echo '&#xe02f;';
        }
        echo '" aria-hidden="true"></span>' . $article_tabs['title'] . '</h2>';

        echo "<div class='tab-container-1 {$tab_content_class}'>";
        $query_args['categories'] = $article_tabs['categories'];
        $query_args['relation'] = $article_tabs['relation'];
        $query_args['tags'] = $article_tabs['tags'];
        $query_args['number_of_article'] = $article_tabs['number_of_article'];
        $query_args['orderby'] = $article_tabs['orderby'];
        $posts = kopa_widget_article_build_query($query_args);
        if ($posts->post_count > 0):

            switch ($article_tabs['display_type']) {
                case 'blog':
                    if (count($article_tabs['categories']) > 0):
                        foreach ($article_tabs['categories'] as $cat_key => $cat_value) {
                            ?>

                            <input type="hidden" class="kopa_categories_arg" value="<?php echo $cat_value; ?>">
                            <?php
                        }
                    else:
                        ?>
                        <input type="hidden" class="kopa_categories_arg" value="none">
                    <?php
                    endif;

                    if (count($article_tabs['tags']) > 0):
                        foreach ($article_tabs['tags'] as $tag_key => $tag_value) {
                            ?>

                            <input type="hidden" class="kopa_tags_arg" value="<?php echo $tag_value; ?>">
                            <?php
                        }
                    else:
                        ?>
                        <input type="hidden" class="kopa_tags_arg" value="none">
                    <?php endif; ?>

                    <input type="hidden" id="kopa_relation_arg" value="<?php echo $article_tabs['relation']; ?>">
                    <input type="hidden" id="kopa_number_of_article_arg" value="<?php echo $article_tabs['number_of_article']; ?>">
                    <input type="hidden" id="kopa_orderbye_arg" value="<?php echo $article_tabs['orderby']; ?>">
                    <div class="timeline-container clearfix">
                        <div id="time-line"></div>
                        <?php
                        $previous_date = '';
                        $month_year = array();
                        $post_id_array = array();
                        while ($posts->have_posts()):
                            $posts->the_post();
                            $post_id = get_the_ID();
                            array_push($post_id_array, $post_id);
                            $post_url = get_permalink();
                            $post_title = get_the_title();
                            $current_date = get_the_date('M,Y');
                            $_element = array(
                                'month' => get_the_date('m'),
                                'year' => get_the_date('Y'),
                                'month-year' => get_the_date('M') . '-' . get_the_date('Y'),
                                'month-text' => get_the_date('F')
                            );
                            array_push($month_year, $_element);
                            if ($current_date != $previous_date):
                                $previous_date = $current_date;
                                $total_post_no = kopa_total_post_count_by_month(get_the_date('m'), get_the_date('Y'));
                                ?>
                                <div class="time-to-filter clearfix" id="<?php echo get_the_date('M') . '-' . get_the_date('Y'); ?>">
                                    <p class="timeline-filter"><span><?php echo $current_date; ?></span></p>
                                    <span class="post-quantity"><?php
                                        echo $total_post_no;
                                        if ($total_post_no <= 1): _e(' Article', kopa_get_domain());
                                        else: _e(' Articles', kopa_get_domain());
                                        endif;
                                        ?>
                                    </span>
                                    <span class="top-ring"></span>
                                    <span class="bottom-ring"></span>
                                </div><!--time-to-filter-->
                                <?php
                            endif;
                            switch (get_post_format()) {
                                case 'quote':
                                    ?>
                                    <article class="timeline-item quote-post clearfix">                                                    
                                        <div class="timeline-icon">
                                            <div><p class="post-type" data-icon="&#xe075;"></p></div>
                                            <span class="dotted-horizon"></span>
                                            <span class="vertical-line"></span>
                                            <span class="circle-outer"></span>
                                            <span class="circle-inner"></span>
                                        </div>
                                        <div class="entry-body clearfix">                                                    
                                            <div class="quote-format"><?php the_excerpt(); ?></div>
                                            <center><span class="quote-name"><?php the_author(); ?></span></center>
                                            <header>
                                                <span class="entry-date"><span class="icon-clock-4 entry-icon" aria-hidden="true"></span><span><?php echo get_the_date(); ?></span></span></span>
                                                <span class="entry-comment"><span class="icon-bubbles-4 entry-icon" aria-hidden="true"></span><?php comments_popup_link(__('No Comment', kopa_get_domain()), __('1 Comment', kopa_get_domain()), __('% Comments', kopa_get_domain()), '', __('Comments Off', kopa_get_domain())); ?></span>
                                            </header>
                                        </div>
                                    </article><!--timeline-item-->
                                    <?php
                                    break;
                                case 'video':
                                    $video = kopa_content_get_video($post->post_content);
                                    ?>
                                    <article class="timeline-item video-post clearfix">                                                    
                                        <div class="timeline-icon">
                                            <div><p class="post-type" data-icon="&#xe023;"></p></div>
                                            <span class="dotted-horizon"></span>
                                            <span class="vertical-line"></span>
                                            <span class="circle-outer"></span>
                                            <span class="circle-inner"></span>
                                        </div>
                                        <div class="entry-body clearfix">
                                            <div class="kp-thumb hover-effect">
                                                <div class="mask">
                                                    <a href="<?php echo $video[0]['url']; ?>" rel="prettyPhoto" class="link-detail" data-icon="&#xe022;"><span></span></a>
                                                </div>
                                                <?php
                                                if (has_post_thumbnail()):
                                                    the_post_thumbnail('kopa-image-size-1');
                                                else:
                                                    printf('<img src="%1$s" alt="">', kopa_get_video_thumbnails_url($video[0]['type'], $video[0]['url']));
                                                endif;
                                                ?>
                                            </div>
                                            <header>
                                                <h2 class="entry-title"><a href="<?php echo $post_url; ?>"><?php the_title(); ?></a></h2>
                                                <span class="entry-date"><span class="icon-clock-4 entry-icon" aria-hidden="true"></span><span><?php echo get_the_date(); ?></span></span>
                                                <span class="entry-comment"><span class="icon-bubbles-4 entry-icon" aria-hidden="true"></span><?php comments_popup_link(__('No Comment', kopa_get_domain()), __('1 Comment', kopa_get_domain()), __('% Comments', kopa_get_domain()), '', __('Comments Off', kopa_get_domain())); ?></span>
                                            </header>
                                            <p><?php the_excerpt(); ?></p>
                                            <a href="<?php echo $post_url; ?>" class="more-link"><?php _e('Continue Reading &raquo;', kopa_get_domain()); ?></a>
                                        </div>

                                    </article><!--timeline-item-->
                                    <?php
                                    break;
                                case 'gallery':
                                    ?>
                                    <article class="timeline-item gallery-post clearfix">                                                    
                                        <div class="timeline-icon">
                                            <div><p class="post-type" data-icon="&#xe01d;"></p></div>
                                            <span class="dotted-horizon"></span>
                                            <span class="vertical-line"></span>
                                            <span class="circle-outer"></span>
                                            <span class="circle-inner"></span>
                                        </div>
                                        <div class="entry-body clearfix"> 
                                            <?php
                                            $gallery = kopa_content_get_gallery($post->post_content);
                                            if ($gallery) {
                                                $shortcode = substr_replace($gallery[0]['shortcode'], ' display_type = 1]', strlen($gallery[0]['shortcode']) - 1, strlen($gallery[0]['shortcode']));
                                                echo do_shortcode($shortcode);
                                            }
                                            ?>
                                            <header>
                                                <h2 class="entry-title"><a href="<?php echo $post_url; ?>"><?php the_title(); ?></a></h2>
                                                <span class="entry-date"><span class="icon-clock-4 entry-icon" aria-hidden="true"></span><span><?php echo get_the_date(); ?></span></span>
                                                <span class="entry-comment"><span class="icon-bubbles-4 entry-icon" aria-hidden="true"></span><?php comments_popup_link(__('No Comment', kopa_get_domain()), __('1 Comment', kopa_get_domain()), __('% Comments', kopa_get_domain()), '', __('Comments Off', kopa_get_domain())); ?></span>
                                            </header>
                                            <span class="load-more-gallery" onclick="more_gallery(jQuery(this));"><span></span></span>
                                        </div>                                                    

                                    </article><!--timeline-item-->
                                    <?php
                                    break;
                                case 'audio':
                                    ?>
                                    <article class="timeline-item audio-post clearfix">                                                    
                                        <div class="timeline-icon">
                                            <div><p class="post-type" data-icon="&#xe020;"></p></div>
                                            <span class="dotted-horizon"></span>
                                            <span class="vertical-line"></span>
                                            <span class="circle-outer"></span>
                                            <span class="circle-inner"></span>
                                        </div>
                                        <div class="entry-body clearfix">
                                            <header>
                                                <h2 class="entry-title"><a href="<?php echo $post_url; ?>"><?php the_title(); ?></a></h2>
                                                <span class="entry-date"><span class="icon-clock-4 entry-icon" aria-hidden="true"></span><span><?php echo get_the_date(); ?></span></span>
                                                <span class="entry-comment"><span class="icon-bubbles-4 entry-icon" aria-hidden="true"></span><?php comments_popup_link(__('No Comment', kopa_get_domain()), __('1 Comment', kopa_get_domain()), __('% Comments', kopa_get_domain()), '', __('Comments Off', kopa_get_domain())); ?></span>
                                            </header>
                                            <?php
                                            $audio = kopa_content_get_audio($post->post_content);
                                            if ($audio) {
                                                echo do_shortcode($audio[0]['shortcode']);
                                            }
                                            ?>
                                            <p><?php the_excerpt(); ?></p>
                                            <a href="<?php echo $post_url; ?>" class="more-link"><?php _e('Continue Reading &raquo;', kopa_get_domain()); ?></a>
                                        </div>

                                    </article><!--timeline-item-->
                                    <?php
                                    break;
                                default:
                                    ?>
                                    <article class="timeline-item <?php
                                    if (has_post_thumbnail())
                                        echo ' standard-post ';
                                    else
                                        echo 'link-post'
                                        ?> clearfix">
                                        <div class="timeline-icon">
                                            <div><p class="post-type" data-icon="&#xe034;"></p></div>
                                            <span class="dotted-horizon"></span>
                                            <span class="vertical-line"></span>
                                            <span class="circle-outer"></span>
                                            <span class="circle-inner"></span>
                                        </div>
                                        <div class="entry-body clearfix">
                                            <?php if (has_post_thumbnail()): ?>
                                                <div class="kp-thumb hover-effect">
                                                    <div class="mask">
                                                        <a class="link-detail" href="<?php echo $post_url; ?>" data-icon="&#xe0c2;"></a>
                                                    </div>
                                                    <?php the_post_thumbnail('kopa-image-size-1'); ?>
                                                </div>
                                            <?php endif; ?>
                                            <header>
                                                <h2 class="entry-title"><a href="<?php echo $post_url; ?>"><?php the_title(); ?></a></h2>
                                                <span class="entry-date"><span class="icon-clock-4 entry-icon" aria-hidden="true"></span><span><?php echo get_the_date(); ?></span></span>
                                                <span class="entry-comment"><span class="icon-bubbles-4 entry-icon" aria-hidden="true"></span><?php comments_popup_link(__('No Comment', kopa_get_domain()), __('1 Comment', kopa_get_domain()), __('% Comments', kopa_get_domain()), '', __('Comments Off', kopa_get_domain())); ?></span>
                                            </header>
                                            <p><?php the_excerpt(); ?></p>
                                            <a href="<?php echo $post_url; ?>" class="more-link"><?php _e('Continue Reading &raquo;', kopa_get_domain()); ?></a>
                                        </div>
                                    </article><!--timeline-item-->
                                    <?php
                                    break;
                            }
                            ?>
                        <?php endwhile; ?>  
                        <span class="load-more kp-tooltip" data-toggle="tooltip" data-original-title="<?php _e('Load More', kopa_get_domain()); ?>" onclick="loadmore_articles(jQuery(this))"><i class="icon-plus"></i></span>
                        <span class="kp-loading hidden"></span>                            
                        <?php
                        wp_nonce_field("load_more_articles", "nonce_id_load_more");
                        $post_id_string = implode(",", $post_id_array);
                        ?>
                        <input type="hidden" id="post_id_array" value="<?php echo $post_id_string; ?>">
                        <div class="kp-filter clearfix">
                            <div onclick="kp_filter_click(jQuery(this))">
                                <span>View by:</span><em>All</em>
                                <a></a>                                    
                                <ul id="ss-links" class="ss-links">
                                    <?php
                                    $current_month = '';
                                    $current_year = '';
                                    foreach ($month_year as $k => $v) {
                                        if ($v['year'] !== $current_year) {
                                            $current_year = $v['year'];
                                            echo '<li class="year"><span>' . $current_year . '</span></li>';
                                        }
                                        if ($v['month'] !== $current_month) {
                                            $current_month = $v['month'];
                                            echo '<li><a href="#' . $v['month-year'] . '" onclick="kp_filter_li_click(jQuery(this))">' . $v['month-text'] . '</a></li>';
                                        }
                                    }
                                    ?>
                                </ul>
                                <input type="hidden" id="stored_month_year" value='<?php echo json_encode($month_year); ?>'>
                                <input type="hidden" id="no_post_found" value="0">
                            </div>
                        </div><!--kp-filter-->
                    </div><!--timeline-container-->
                    <?php
                    break;

                case 'portfolio':
                    ?>
                    <div id="isotop-container">
                        <header id ="options" class="isotop-header clearfix">
                            <em><?php echo __('Sort by:', kopa_get_domain()); ?></em><span><?php echo __('All', kopa_get_domain()); ?></span>
                            <a></a>
                            <ul id="filters" class="option-set clearfix" data-option-key="filter">   
                                <li><a href="#filter"  data-option-value="*"><?php echo __('View All', kopa_get_domain()); ?></a></li>                                 
                                <?php
                                $projects = get_terms('portfolio_project');
                                foreach ($projects as $project):
                                    ?>                        
                                    <li><a data-option-value="<?php echo ".kopa-portfolio-{$project->slug}"; ?>" href="#filter"><?php echo $project->name; ?></a></li>                                    
                                    <?php
                                endforeach;
                                ?>
                            </ul><!-- end #portfolio-items-filter -->      
                        </header><!-- end .page-header -->
                        <div id="portfolio-items">
                            <?php
                            $pf_args['post_type'] = 'portfolio';
                            $pf_args['posts_per_page'] = -1;
                            $query = new WP_Query($pf_args);
                            while ($query->have_posts()) : $query->the_post();
                                $post_id = get_the_ID();
                                $post_url = get_permalink();
                                $post_title = get_the_title();

                                $portfolio_project_links = array();
                                $portfolio_tag_links = array();

                                $portfolio_projects = wp_get_post_terms($post_id, 'portfolio_project');

                                $classes = array();
                                if ($portfolio_projects) {
                                    foreach ($portfolio_projects as $project) {
                                        $classes[] = "kopa-portfolio-{$project->slug}";
                                    }
                                }
                                $classes = implode(' ', $classes);

                                $full_size_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
                                $thumbnails_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'kopa-image-size-6');
                                ?>

                                <article class="element <?php echo $classes; ?>" data-category="<?php echo $classes; ?>">
                                    <div class="mask">
                                        <a rel="prettyPhoto[kopa-gallery]" href="<?php echo $full_size_image[0]; ?>" class="kp-pf-gallery" data-icon="&#xe07e;"></a>
                                        <a href="<?php the_permalink(); ?>" class="kp-pf-detail" data-icon="&#xe0c2;"></a>
                                        <div class="portfolio-caption">
                                            <h3><?php echo $post_title; ?></h3>
                                            <?php the_excerpt(); ?>
                                        </div>
                                    </div>
                                    <?php
                                    if ($thumbnails_image):
                                        $size = getimagesize($thumbnails_image[0]);
                                        ?>
                                        <img src="<?php echo $thumbnails_image[0]; ?>" <?php echo $size[3]; ?> alt="" />
                                    <?php endif; ?>
                                </article><!--element-->
                                <?php
                            endwhile;
                            wp_reset_query();
                            ?>
                        </div><!--portfolio-items-->
                    </div><!--isotop-container-->
                    <?php
                    break;
                default:
                    break;
            }
        endif;
        echo '</div><!--tab-container-1-->';
        echo '</div><!--entry-list-->';
        wp_reset_postdata();
    }

}

class Kopa_Widget_Sequence_Slider extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'sequence-slider-widget', 'description' => __('Display a Sequence Slider from posts', kopa_get_domain()));
        $control_ops = array('width' => '500', 'height' => 'auto');
        parent::__construct('kopa_widget_sequence_slider', __('Kopa Sequence Slider', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['number_of_article'] = $new_instance['number_of_article'];
        $instance['orderby'] = $new_instance['orderby'];

        // $instance['animation'] = $new_instance['animation'];
        $instance['autoplay'] = isset($new_instance['autoplay']) ? $new_instance['autoplay'] : 'false';
        $instance['slideshow_interval'] = (int) $new_instance['slideshow_interval'];
        // $instance['speed'] = (int) $new_instance['speed'];
        // $instance['titlesFactor'] = floatval($new_instance['titlesFactor']);
        // $instance['titlespeed'] = (int) $new_instance['titlespeed'];

        return $instance;
    }

    function form($instance) {
        $default = array(
            'title' => '',
            'categories' => array(),
            'relation' => 'OR',
            'tags' => array(),
            'number_of_article' => 4,
            'orderby' => 'lastest',
            // 'animation' => 'center',
            'autoplay' => 'true',
            'slideshow_interval' => 3000,
                // 'speed' => 800,
                // 'titlesFactor' => 0.60,
                // 'titlespeed' => 800
        );
        $instance = wp_parse_args((array) $instance, $default);
        $title = strip_tags($instance['title']);

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['number_of_article'] = (int) $instance['number_of_article'];
        $form['orderby'] = $instance['orderby'];

        // $form['animation'] = $instance['animation'];
        $form['autoplay'] = $instance['autoplay'];
        $form['slideshow_interval'] = (int) $instance['slideshow_interval'];
        // $form['speed'] = (int) $instance['speed'];
        // $form['titlesFactor'] = $instance['titlesFactor'];
        // $form['titlespeed'] = (int) $instance['titlespeed'];
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <div class="kopa-one-half">
            <p>
                <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                    <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                    <?php
                    $categories = get_categories();
                    foreach ($categories as $category) {
                        printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, (isset($form['categories']) ? $form['categories'] : array()))) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>

            </p>
            <p>
                <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                    <?php
                    $relation = array(
                        'AND' => __('And', kopa_get_domain()),
                        'OR' => __('Or', kopa_get_domain())
                    );
                    foreach ($relation as $value => $title) {
                        printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                    <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                    <?php
                    $tags = get_tags();
                    foreach ($tags as $tag) {
                        printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, (isset($form['tags']) ? $form['tags'] : array()))) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('number_of_article'); ?>"><?php _e('Number of article:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('number_of_article'); ?>" name="<?php echo $this->get_field_name('number_of_article'); ?>" autocomplete="off">
                    <?php
                    $number_of_article = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 15, 20, 25, 30);
                    foreach ($number_of_article as $value) {
                        printf('<option value="%1$s" %3$s>%2$s</option>', $value, $value, ($value == $form['number_of_article']) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                    <?php
                    $orderby = array(
                        'lastest' => __('Lastest', kopa_get_domain()),
                        'popular' => __('Popular by View Count', kopa_get_domain()),
                        'most_comment' => __('Popular by Comment Count', kopa_get_domain()),
                        'random' => __('Random', kopa_get_domain()),
                    );
                    foreach ($orderby as $value => $title) {
                        printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>
            </p>
        </div>

        <div class="kopa-one-half last">

            <p>
                <input class="" id="<?php echo $this->get_field_id('autoplay'); ?>" name="<?php echo $this->get_field_name('autoplay'); ?>" type="checkbox" value="true" <?php echo ('true' === $form['autoplay']) ? 'checked="checked"' : ''; ?> />
                <label for="<?php echo $this->get_field_id('autoplay'); ?>"><?php _e('Auto Play', kopa_get_domain()); ?></label>                                
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('slideshow_interval'); ?>"><?php _e('Interval for the slideshow:', kopa_get_domain()); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id('slideshow_interval'); ?>" name="<?php echo $this->get_field_name('slideshow_interval'); ?>" type="number" value="<?php echo $form['slideshow_interval']; ?>" />
            </p>

        </div>
        <div class="kopa-clear"></div>
        <?php
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = esc_attr($instance['relation']);
        $query_args['tags'] = $instance['tags'];
        $query_args['number_of_article'] = (int) $instance['number_of_article'];
        $query_args['orderby'] = $instance['orderby'];

        // $slider_args['animation'] = $instance['animation'];
        $slider_args['autoplay'] = $instance['autoplay'];
        $slider_args['slideshow_interval'] = (int) $instance['slideshow_interval'];
        // $slider_args['speed'] = (int) $instance['speed'];
        // $slider_args['titlesFactor'] = floatval($instance['titlesFactor']);
        // $slider_args['titlespeed'] = (int) $instance['titlespeed'];

        echo $before_widget;
        if (!empty($title)) {
            echo $before_title . $title . $after_title;
        }

        $posts = kopa_widget_article_build_query($query_args);

        if ($posts->post_count > 0):
            $thumbnails = array();
            ?>
            <div class="sequence-wrapper">

                <div class="sequence-container">

                    <a class="prev" href="#"></a>
                    <a class="next" href="#"></a>

                    <div class="sequence-slider">

                        <div class="sequence" <?php
                        foreach ($slider_args as $class => $value) {
                            printf('data-%1$s="%2$s" ', $class, $value);
                        }
                        ?>>
                            <ul>
                                <?php
                                $title_index = 1; // index for title, subtitle & image model
                                while ($posts->have_posts()):
                                    $posts->the_post();
                                    $post_id = get_the_ID();
                                    $post_url = get_permalink();
                                    $post_title = get_the_title();

                                    if (has_post_thumbnail($post_id)):
                                        $feature_image = get_post_thumbnail_id($post_id);
                                        $thumbnail = wp_get_attachment_image_src($feature_image, 'kopa-image-size-3');

                                        $thumbnails[$post_id]['title'] = $post_title;
                                        $thumbnails[$post_id]['url'] = $post_url;
                                        $thumbnails[$post_id]['image'] = $thumbnail[0];
                                        ?>

                                        <li>
                                            <div class="title<?php echo $title_index == 1 ? '' : '-2'; ?> animate-in">
                                                <h2><?php the_title(); ?></h2>
                                            </div> <!-- .title -->
                                            <div class="subtitle<?php echo $title_index == 1 ? '' : '-2'; ?> animate-in">
                                                <h3>With a Clean &amp; Modern design</h3>
                                                <p><?php echo strip_tags(get_the_excerpt()); ?></p>
                                                <a class="more-link" href="<?php the_permalink(); ?>">
                                                    <?php _e('Read more &raquo;', kopa_get_domain()); ?>
                                                </a>
                                            </div> <!-- .subtitle -->
                                            <img class="model<?php echo $title_index == 1 ? '' : '-2-1'; ?> animate-in" src="<?php echo $thumbnail[0]; ?>" alt="<?php the_title(); ?>" />
                                        </li>

                                        <?php
                                        if ($title_index == 1)
                                            $title_index = 0;
                                        else
                                            $title_index = 1;

                                    endif;
                                endwhile;
                                ?>

                            </ul>

                        </div><!--sequence-->

                        <ul class="sequence-nav">
                            <?php
                            while ($posts->have_posts()):
                                $posts->the_post();
                                $post_id = get_the_ID();

                                if (has_post_thumbnail($post_id)):
                                    $feature_image = get_post_thumbnail_id($post_id);
                                    $thumbnail = wp_get_attachment_image_src($feature_image, 'kopa-image-size-3');
                                    ?>
                                    <li><img src="<?php echo $thumbnail[0]; ?>" alt="<?php the_title(); ?>" /></li>
                                    <?php
                                endif;
                            endwhile;
                            ?>
                        </ul>

                    </div><!--sequence-slider-->

                </div><!--sequence-container-->

            </div><!--sequence-wrapper-->

            <?php
        endif;

        wp_reset_postdata();
        echo $after_widget;
    }

}

class Kopa_Widget_Advertisement extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'kopa_widget_adv', 'description' => __('Display Banner in sidebar', kopa_get_domain()));
        $control_ops = array('width' => '500', 'height' => '500');
        parent::__construct('kopa_widget_adv', __('Kopa Banner', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $image = strip_tags($instance['image']);
        $link = strip_tags($instance['link']);

        echo $before_widget;
        if (!empty($title)) {
            echo $before_title . $title . $after_title;
        }
        ?>
        <div class="adv-300-300">
            <a href="<?php echo $link; ?>"><img alt="" src="<?php echo $image; ?>"></a>
        </div>
        <?php
        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['image'] = strip_tags($new_instance['image']);
        $instance['link'] = strip_tags($new_instance['link']);

        return $instance;
    }

    function form($instance) {
        $instance = wp_parse_args((array) $instance, array('title' => '', 'image' => '', 'link' => ''));
        $title = strip_tags($instance['title']);
        $image = strip_tags($instance['image']);
        $link = strip_tags($instance['link']);
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>  

        <div class="clearfix">
            <input class="kopa_adv_upload_image left" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" type="text" value="<?php echo esc_attr($image); ?>" />
            <button class="left btn btn-success widget_upload_image_button" alt="kopa_adv_upload_image"><i class="icon-circle-arrow-up"></i><?php _e('Upload', kopa_get_domain()); ?></button>
        </div>
        <p>
            <br>
            <label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('URL:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo esc_attr($link); ?>" />
        </p>  

        <?php
    }

}

class Kopa_Widget_Content extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'kopa_widget_content', 'description' => __('Display Arbitrary text, HTML in full width layout', kopa_get_domain()));
        $control_ops = array('width' => 600, 'height' => 400);
        parent::__construct('kopa_widget_content', __('Kopa full width content', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $text = apply_filters('widget_text', empty($instance['text']) ? '' : $instance['text'], $instance);

        echo $before_widget;
        ?>
        <div class="widget kp-full-content">
            <span class="bottom-circle"></span>
            <span class="bottom-bullet"></span>
            <div class="wrapper">
                <div class="row-fluid">
                    <div class="span12">
                        <?php
                        if (!empty($title)) {
                            echo $before_title . $title . $after_title;
                        }
                        echo!empty($instance['filter']) ? wpautop($text) : $text;
                        ?>
                    </div><!--span12-->
                </div><!--row-fluid-->
            </div><!--wrapper-->
        </div><!--kp-tag-line-->

        <?php
        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        if (current_user_can('unfiltered_html'))
            $instance['text'] = $new_instance['text'];
        else
            $instance['text'] = stripslashes(wp_filter_post_kses(addslashes($new_instance['text'])));
        $instance['filter'] = isset($new_instance['filter']);
        return $instance;
    }

    function form($instance) {
        $instance = wp_parse_args((array) $instance, array('title' => '', 'text' => ''));
        $title = strip_tags($instance['title']);
        $text = esc_textarea($instance['text']);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p> 
        <ul class="kopa_shortcode_icons">
            <?php
            $shortcodes = array(
                'one_half' => 'One Half Column',
                'one_third' => 'One Thirtd Column',
                'two_third' => 'Two Third Column',
                'one_fourth' => 'One Fourth Column',
                'three_fourth' => 'Three Fourth Column',
                'dropcaps' => 'Add Dropcaps Text',
                'button' => 'Add A Button',
                'alert' => 'Add A Alert Box',
                'tabs' => 'Add A Tabs Content',
                'accordions' => 'Add A Accordions Content',
                'toggle' => 'Add A Toggle Content',
                'contact_form' => 'Add A Contact Form',
                'posts_lastest' => 'Add A List Lastest Post',
                'posts_popular' => 'Add A List Popular Post',
                'posts_most_comment' => 'Add A List Most Comment Post',
                'posts_random' => 'Add A List Random Post',
                'youtube' => 'Add A Yoube Video Box',
                'vimeo' => 'Add A Vimeo Video Box'
            );
            foreach ($shortcodes as $rel => $title):
                ?>
                <li>
                    <a onclick="return kopa_shortcode_icon_click('<?php echo $rel; ?>', jQuery('#<?php echo $this->get_field_id('text'); ?>'));" href="#" class="<?php echo "kopa-icon-{$rel}"; ?>" rel="<?php echo $rel; ?>" title="<?php echo $title; ?>"></a>
                </li>
            <?php endforeach; ?>
        </ul>
        <textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
        <p>
            <input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs', kopa_get_domain()); ?></label>
        </p>
        <?php
    }

}

class Kopa_Widget_Service extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'kp-service', 'description' => __('Display a service widget', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_service', __('Kopa Services', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['posts_per_page'] = (int) $new_instance['posts_per_page'];
        $instance['orderby'] = $new_instance['orderby'];

        return $instance;
    }

    function form($instance) {
        $default = array(
            'title' => '',
            'categories' => array(),
            'relation' => 'OR',
            'tags' => array(),
            'posts_per_page' => 4,
            'orderby' => 'lastest',
        );
        $instance = wp_parse_args((array) $instance, $default);
        $title = strip_tags($instance['title']);

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['posts_per_page'] = (int) $instance['posts_per_page'];
        $form['orderby'] = $instance['orderby'];
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_terms('service_category');
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, (isset($form['categories']) ? $form['categories'] : array()))) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                <?php
                $relation = array(
                    'AND' => __('And', kopa_get_domain()),
                    'OR' => __('Or', kopa_get_domain())
                );
                foreach ($relation as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $tags = get_terms('service_tag');
                foreach ($tags as $tag) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, (isset($form['tags']) ? $form['tags'] : array()))) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('posts_per_page'); ?>"><?php _e('Number of items:', kopa_get_domain()); ?></label>                
            <input class="widefat" id="<?php echo $this->get_field_id('posts_per_page'); ?>" name="<?php echo $this->get_field_name('posts_per_page'); ?>" value="<?php echo $form['posts_per_page']; ?>" type="number" min="1">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'lastest' => __('Lastest', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain()),
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>

        <div class="kopa-clear"></div>
        <?php
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

        $query_args['post_type'] = 'services';
        $query_args['cat_name'] = 'service_category';
        $query_args['tag_name'] = 'service_tag';
        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = esc_attr($instance['relation']);
        $query_args['tags'] = $instance['tags'];
        $query_args['posts_per_page'] = (int) $instance['posts_per_page'];
        $query_args['orderby'] = $instance['orderby'];

        echo $before_widget;

        $services = kopa_widget_posttype_build_query($query_args);

        if ($services->post_count > 0):
            ?>
            <span class="bottom-circle"></span>
            <span class="bottom-bullet"></span>
            <div class="wrapper">
                <div class="row-fluid">
                    <?php
                    $service_index = 1;
                    $kopa_icon = unserialize(KOPA_ICON);
                    while ($services->have_posts()):
                        $services->the_post();


                        // initialize & reset for each loop
                        $service_custom_icon_src = ''; 
                        $data_icon = '';
                        $icon_class = '';

                        $icon_class = get_post_meta(get_the_ID(), 'icon_class', true);
                        $service_custom_icon_src = get_post_meta(get_the_ID(), 'service_custom_icon', true);

                        if ( !empty($icon_class) ) {
                            $data_icon = $kopa_icon[$icon_class];
                        }
                        ?>
                        <div class="span3">
                            <article>
                                <div class="ch-item">
                                    <div class="ch-info">
                                        <?php if ( !empty($data_icon) ) : ?>

                                            <div class="ch-info-front">
                                                <a href="<?php the_permalink(); ?>" data-icon="<?php echo $data_icon; ?>"></a>                                    
                                            </div>
                                            <div class="ch-info-back">
                                                <a href="<?php the_permalink(); ?>" data-icon="<?php echo $data_icon; ?>"></a>                                         
                                            </div>

                                        <?php elseif ($service_custom_icon_src) : ?>

                                            <div class="ch-info-front service-front">

                                                <a class="service-thumbnail-front" href="<?php the_permalink(); ?>">
                                                    <img src="<?php echo $service_custom_icon_src; ?>" alt="<?php the_title(); ?>"></a>                                    
                                            </div>
                                            <div class="ch-info-back service-back">
                                                
                                                <a class="service-thumbnail-back" href="<?php the_permalink(); ?>">
                                                    <img src="<?php echo $service_custom_icon_src; ?>" alt="<?php the_title(); ?>"></a>                                         
                                            </div>    

                                        <?php endif; // ! empty($data_icon) ?>
                                    </div>
                                </div>
                                <h2 class="ch-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                                <p><?php echo strip_tags(get_the_excerpt()); ?></p>
                            </article>
                        </div><!--span3-->       
                        <?php
                        
                        if ($service_index % 4 == 0) {
                            echo '</div></div><div class="wrapper"><div class="row-fluid">';
                        }
                        $service_index++; // increase service index by 1

                    // endif;
                    endwhile;
                    ?>         
                </div><!--row-fluid-->
            </div><!--wrapper-->
            <?php
        endif;

        wp_reset_postdata();
        echo $after_widget;
    }

}

class Kopa_Widget_Service_Pie_Chart extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'service-pie-chart-widget', 'description' => __('Display a percentage pie chart widget of service expertise', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_service_pie_chart', __('Kopa Service Pie Chart', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['posts_per_page'] = (int) $new_instance['posts_per_page'];
        $instance['orderby'] = $new_instance['orderby'];

        return $instance;
    }

    function form($instance) {
        $default = array(
            'title' => '',
            'categories' => array(),
            'relation' => 'OR',
            'tags' => array(),
            'posts_per_page' => 4,
            'orderby' => 'lastest',
        );
        $instance = wp_parse_args((array) $instance, $default);
        $title = strip_tags($instance['title']);

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['posts_per_page'] = (int) $instance['posts_per_page'];
        $form['orderby'] = $instance['orderby'];
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_terms('service_category');
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, (isset($form['categories']) ? $form['categories'] : array()))) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                <?php
                $relation = array(
                    'AND' => __('And', kopa_get_domain()),
                    'OR' => __('Or', kopa_get_domain())
                );
                foreach ($relation as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $tags = get_terms('service_tag');
                foreach ($tags as $tag) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, (isset($form['tags']) ? $form['tags'] : array()))) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('posts_per_page'); ?>"><?php _e('Number of items:', kopa_get_domain()); ?></label>                
            <input class="widefat" id="<?php echo $this->get_field_id('posts_per_page'); ?>" name="<?php echo $this->get_field_name('posts_per_page'); ?>" value="<?php echo $form['posts_per_page']; ?>" type="number" min="1">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'lastest' => __('Lastest', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain()),
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>

        <div class="kopa-clear"></div>
        <?php
    }

    function widget($args, $instance) {
        extract($args);

        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

        $query_args['post_type'] = 'services';
        $query_args['cat_name'] = 'service_category';
        $query_args['tag_name'] = 'service_tag';
        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = esc_attr($instance['relation']);
        $query_args['tags'] = $instance['tags'];
        $query_args['posts_per_page'] = (int) $instance['posts_per_page'];
        $query_args['orderby'] = $instance['orderby'];

        echo $before_widget;

        if (!empty($title)) {
            echo $before_title . $title . $after_title;
        }

        $services = kopa_widget_posttype_build_query($query_args);

        if ($services->post_count <= 0)
            return;
        ?>

        <ul class="kp-skill clearfix">

            <?php
            while ($services->have_posts()) : $services->the_post();
                $service_percentage = (int) get_post_meta(get_the_ID(), 'service_percentage', true);
                ?>

                <li>
                    <div class="chart">
                        <div class="percentage-light" 
                             data-percent="<?php echo $service_percentage; ?>">
                            <span><?php echo $service_percentage; ?></span><sup>%</sup>
                        </div> <!-- percentage-light -->
                        <center><div class="label"><?php the_title(); ?></div></center>
                    </div>
                </li>

            <?php endwhile; ?>
        </ul>
        <script>
                        jQuery(document).ready(function() {
                            (function() {
                                jQuery('.percentage-light').easyPieChart({
                                    barColor: function(percent) {
                                        percent /= 100;
                                        return "rgb(" + Math.round(255 * (1 - percent)) + ", " + Math.round(255 * percent) + ", 0)";
                                    },
                                    trackColor: '#f5f5f5',
                                    barColor: <?php echo '"' . get_option('kopa_theme_options_color_code', '#91C448') . '"'; ?>,
                                            scaleColor: false,
                                    lineCap: 'butt',
                                    lineWidth: 5,
                                    animate: 1000
                                });
                            })();
                        });
        </script>
        <?php
        echo $after_widget;
    }

}

class Kopa_Widget_Service_Pie_Chart_2 extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'about-skill', 'description' => __('Display a fullwidth percentage pie chart widget of service expertise', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_pie_chart_2', __('Kopa Service Pie Chart 2', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['description'] = strip_tags($new_instance['description']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['posts_per_page'] = (int) $new_instance['posts_per_page'];
        $instance['orderby'] = $new_instance['orderby'];

        return $instance;
    }

    function form($instance) {
        $default = array(
            'title' => '',
            'description' => '',
            'categories' => array(),
            'relation' => 'OR',
            'tags' => array(),
            'posts_per_page' => 4,
            'orderby' => 'lastest',
        );
        $instance = wp_parse_args((array) $instance, $default);
        $title = strip_tags($instance['title']);
        $form['description'] = strip_tags($instance['description']);
        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['posts_per_page'] = (int) $instance['posts_per_page'];
        $form['orderby'] = $instance['orderby'];
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Description:', kopa_get_domain()); ?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" rows="5"><?php echo esc_attr($form['description']); ?></textarea>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_terms('service_category');
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, (isset($form['categories']) ? $form['categories'] : array()))) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                <?php
                $relation = array(
                    'AND' => __('And', kopa_get_domain()),
                    'OR' => __('Or', kopa_get_domain())
                );
                foreach ($relation as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $tags = get_terms('service_tag');
                foreach ($tags as $tag) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, (isset($form['tags']) ? $form['tags'] : array()))) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('posts_per_page'); ?>"><?php _e('Number of items:', kopa_get_domain()); ?></label>                
            <input class="widefat" id="<?php echo $this->get_field_id('posts_per_page'); ?>" name="<?php echo $this->get_field_name('posts_per_page'); ?>" value="<?php echo $form['posts_per_page']; ?>" type="number" min="1">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'lastest' => __('Lastest', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain()),
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>

        <div class="kopa-clear"></div>
        <?php
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

        $description = strip_tags($instance['description']);

        $query_args['post_type'] = 'services';
        $query_args['cat_name'] = 'service_category';
        $query_args['tag_name'] = 'service_tag';
        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = esc_attr($instance['relation']);
        $query_args['tags'] = $instance['tags'];
        $query_args['posts_per_page'] = (int) $instance['posts_per_page'];
        $query_args['orderby'] = $instance['orderby'];

        echo $before_widget;

        $services = kopa_widget_posttype_build_query($query_args);

        if ($services->post_count <= 0)
            return;
        ?>

        <span class="bottom-circle"></span>
        <span class="bottom-bullet"></span>
        <div class="wrapper">
            <div class="row-fluid">
                <div class="span12">

                    <?php if (!empty($title)) : ?>
                        <h2><?php echo $title; ?></h2>
                    <?php endif; ?>

                    <p class="about-skill-intro"><?php echo $description; ?></p>
                    <ul class="kp-skill clearfix">
                        <?php
                        while ($services->have_posts()) :
                            $services->the_post();
                            $service_percentage = get_post_meta(get_the_ID(), 'service_percentage', true);
                            ?>
                            <li>
                                <div class="chart">
                                    <div class="percentage-light" 

                                         data-percent="<?php echo $service_percentage; ?>">
                                        <span><?php echo $service_percentage; ?></span><sup>%</sup>
                                    </div>
                                    <center><div class="label"><?php the_title(); ?></div></center>
                                </div>
                                <p><?php echo strip_tags(get_the_excerpt()); ?></p>
                            </li>

                        <?php endwhile; ?>
                    </ul>
                </div><!--span12-->
            </div><!--row-fluid-->
        </div><!--wrapper-->
        <script>
            jQuery(document).ready(function() {
                (function() {
                    jQuery('.percentage-light').easyPieChart({
                        barColor: function(percent) {
                            percent /= 100;
                            return "rgb(" + Math.round(255 * (1 - percent)) + ", " + Math.round(255 * percent) + ", 0)";
                        },
                        trackColor: '#f5f5f5',
                        barColor: <?php echo '"' . get_option('kopa_theme_options_color_code', '#91C448') . '"'; ?>,
                                scaleColor: false,
                        lineCap: 'butt',
                        lineWidth: 5,
                        animate: 1000
                    });
                })();
            });
        </script>

        <?php
        wp_reset_postdata();
        echo $after_widget;
    }

}

class Kopa_Widget_Carousel_Slider_1 extends Kopa_Widget_ArticleList {

    function __construct() {
        $widget_ops = array('classname' => 'kp-our-work', 'description' => __('Display a carousel slider in full width layout', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_carousel_posts', __('Kopa Carousel Slider 1', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = esc_attr($instance['relation']);
        $query_args['tags'] = $instance['tags'];
        $query_args['number_of_article'] = (int) $instance['number_of_article'];
        $query_args['orderby'] = $instance['orderby'];

        $posts = kopa_widget_article_build_query($query_args);

        if ($posts->post_count <= 0)
            return;

        echo $before_widget;
        ?>


        <span class="bottom-circle"></span>
        <span class="bottom-bullet"></span>

        <div class="wrapper">

            <div class="row-fluid">

                <div class="span12">

                    <?php
                    if (!empty($title))
                        echo $before_title . $title . $after_title;
                    ?>

                    <div class="list-carousel responsive" >

                        <ul class="our-work-widget">

                            <?php
                            while ($posts->have_posts()) : $posts->the_post();

                                $post_id = get_the_ID();

                                if (has_post_thumbnail($post_id)):
                                    $feature_image = get_post_thumbnail_id($post_id);
                                    $thumbnail = wp_get_attachment_image_src($feature_image, 'kopa-image-size-1');
                                endif;
                                ?>                                    
                                <li>
                                    <article class="entry-item clearfix">
                                        <div class="entry-thumb hover-effect">

                                            <div class="mask">
                                                <a class="link-detail" data-icon="&#xe0c2;" href="<?php the_permalink(); ?>"></a>
                                            </div>

                                            <?php if (has_post_thumbnail()) : ?>
                                                <img src="<?php echo $thumbnail[0]; ?>" alt="">
                                            <?php endif; ?>

                                        </div>
                                        <div class="entry-content">
                                            <h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                            <p class="entry-meta">
                                                <span class="entry-category"><span class="icon-clock-4 entry-icon" aria-hidden="true"></span><span><?php the_time('F j, Y'); ?></span></span>
                                                <span class="entry-comment"><span class="icon-bubbles-4 entry-icon" aria-hidden="true"></span><?php comments_popup_link(__('No Comment', kopa_get_domain()), __('1 Comment', kopa_get_domain()), __('% Comments', kopa_get_domain()), '', __('Comments Off', kopa_get_domain())); ?></span>
                                            </p>
                                            <p><?php echo strip_tags(get_the_excerpt()); ?></p>
                                            <a class="more-link" href="<?php the_permalink(); ?>">
                                                <?php _e('Read more &raquo;', kopa_get_domain()); ?>
                                            </a>
                                        </div><!--entry-content-->
                                    </article><!--entry-item-->
                                </li>

                            <?php endwhile; ?>
                        </ul>

                        <div class="clearfix"></div>

                        <div class="carousel-nav clearfix">
                            <a id="<?php echo $this->get_field_id('prev'); ?>" 
                               class="carousel-prev" href="#">&lt;</a>
                            <a id="<?php echo $this->get_field_id('next'); ?>" 
                               class="carousel-next" href="#">&gt;</a>
                        </div><!--end:carousel-nav-->

                    </div><!--end:list-carousel-->

                </div><!--span12-->

            </div><!--row-fluid-->

        </div><!--wrapper-->


        <?php
        echo $after_widget;
    }


    function form($instance) {
        $default = array(
            'title' => '',
            'categories' => array(),
            'relation' => 'OR',
            'tags' => array(),
            'number_of_article' => 8,
            'orderby' => 'lastest',
        );
        $instance = wp_parse_args((array) $instance, $default);
        $title = strip_tags($instance['title']);

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['number_of_article'] = (int) $instance['number_of_article'];
        $form['orderby'] = $instance['orderby'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_categories();
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, (isset($form['categories']) ? $form['categories'] : array())) ) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                <?php
                $relation = array(
                    'AND' => __('And', kopa_get_domain()),
                    'OR' => __('Or', kopa_get_domain())
                );
                foreach ($relation as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $tags = get_tags();
                foreach ($tags as $tag) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, (isset($form['tags']) ? $form['tags'] : array()))) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number_of_article'); ?>"><?php _e('Number of article:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('number_of_article'); ?>" name="<?php echo $this->get_field_name('number_of_article'); ?>" autocomplete="off">
                <?php
                $number_of_article = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 15, 20, 25, 30);
                foreach ($number_of_article as $value) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $value, ($value == $form['number_of_article']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'lastest' => __('Lastest', kopa_get_domain()),
                    'popular' => __('Popular by View Count', kopa_get_domain()),
                    'most_comment' => __('Popular by Comment Count', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain()),
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <?php
    }

}


class Kopa_Widget_Carousel_Slider_2 extends Kopa_Widget_ArticleList {

    function __construct() {
        $widget_ops = array('classname' => 'kp-our-work', 'description' => __('Display a carousel slider in frontpage right sidebar layout', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_carousel_posts_2', __('Kopa Carousel Slider 2', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = esc_attr($instance['relation']);
        $query_args['tags'] = $instance['tags'];
        $query_args['number_of_article'] = (int) $instance['number_of_article'];
        $query_args['orderby'] = $instance['orderby'];

        $posts = kopa_widget_article_build_query($query_args);

        if ($posts->post_count <= 0)
            return;

        echo $before_widget;
        ?>

            <?php
            if (!empty($title))
                echo $before_title . $title . $after_title;
            ?>

            <div class="list-carousel responsive" >

                <ul class="our-work-widget">

                    <?php
                    while ($posts->have_posts()) : $posts->the_post();

                        $post_id = get_the_ID();
                        $post_url = get_permalink();

                        if (has_post_thumbnail($post_id)):
                            $feature_image = get_post_thumbnail_id($post_id);
                            $thumbnail = wp_get_attachment_image_src($feature_image, 'kopa-image-size-1');
                        endif;
                        ?>                                    
                        <li>
                            <article class="entry-item clearfix">
                                <div class="entry-thumb hover-effect">

                                    <div class="mask">
                                        <?php 
                                            switch ( get_post_format() ) :
                                                // video post format
                                                case 'video': 
                                                    $video = kopa_content_get_video( get_the_content() );

                                                    $video = array_shift($video);
                                                ?>
                                                    <a class="link-detail" href="<?php echo $video['url']; ?>" rel="prettyPhoto" data-icon="&#xe022;"></a>
                                                <?php
                                                    break;

                                                // gallery post format
                                                case 'gallery':
                                                    $gallery = kopa_content_get_gallery( get_the_content() );
                                                    $shortcode = $gallery[0]['shortcode'];

                                                    // get gallery string ids
                                                    preg_match_all('/ids=\"(?:\d+,*)+\"/', $shortcode, $gallery_string_ids);
                                                    $gallery_string_ids = $gallery_string_ids[0][0];

                                                    // get array of image id
                                                    preg_match_all('/\d+/', $gallery_string_ids, $gallery_ids);
                                                    $gallery_ids = $gallery_ids[0];

                                                    $first_image_id = array_shift($gallery_ids);
                                                    $first_image_src = wp_get_attachment_image_src($first_image_id, 'kopa-image-size-2');
                                                    
                                                    $slug = $this->get_field_id('gallery') . '-' . get_the_ID();

                                                ?>
                                                    <a class="link-detail" href="<?php echo $first_image_src[0]; ?>" rel="prettyPhoto[<?php echo $slug; ?>]" data-icon="&#xe01d;"></a>
                                                <?php
                                                    foreach ($gallery_ids as $gallery_id) :
                                                        $image_src = wp_get_attachment_image_src($gallery_id, 'kopa-image-size-2'); ?>
                                                        <a style="display: none" href="<?php echo $image_src[0]; ?>" rel="prettyPhoto[<?php echo $slug; ?>]"></a>
                                                        <?php 
                                                    endforeach;

                                                    break;

                                                // audio post format
                                                case 'audio':
                                                ?>
                                                    <a class="link-detail" href="<?php echo $post_url; ?>" data-icon="&#xe020;"></a>
                                                <?php
                                                    break;

                                                // quote post format
                                                case 'quote':
                                                ?>
                                                    <a class="link-detail" href="<?php echo $post_url; ?>" data-icon="&#xe075;"></a>
                                                <?php
                                                    break;

                                                // default post format                                                
                                                default: ?>
                                                    <a class="link-detail" href="<?php echo $post_url; ?>" data-icon="&#xe0c2;"></a>
                                                <?php 
                                                    break;
                                            endswitch;
                                        ?>
                                    </div> <!-- .mask -->

                                    <?php if (has_post_thumbnail()) : ?>
                                        <img src="<?php echo $thumbnail[0]; ?>" alt="">
                                    <?php elseif (get_post_format() == 'video' && isset($video['type']) && isset($video['url'])) :
                                        printf('<img src="%1$s" alt="">', kopa_get_video_thumbnails_url($video['type'], $video['url']));
                                    endif; ?>

                                </div>
                                <div class="entry-content">
                                    <h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <p class="entry-meta">
                                        <span class="entry-category"><span class="icon-clock-4 entry-icon" aria-hidden="true"></span><span><?php the_time('F j, Y'); ?></span></span>
                                        <span class="entry-comment"><span class="icon-bubbles-4 entry-icon" aria-hidden="true"></span><?php comments_popup_link(__('No Comment', kopa_get_domain()), __('1 Comment', kopa_get_domain()), __('% Comments', kopa_get_domain()), '', __('Comments Off', kopa_get_domain())); ?></span>
                                    </p>
                                    <p><?php echo strip_tags(get_the_excerpt()); ?></p>
                                    <a class="more-link" href="<?php the_permalink(); ?>">
                                        <?php _e('Read more &raquo;', kopa_get_domain()); ?>
                                    </a>
                                </div><!--entry-content-->
                            </article><!--entry-item-->
                        </li>

                    <?php endwhile; ?>
                </ul>

                <div class="clearfix"></div>

                <div class="carousel-nav clearfix">
                    <a id="<?php echo $this->get_field_id('prev'); ?>" 
                       class="carousel-prev" href="#">&lt;</a>
                    <a id="<?php echo $this->get_field_id('next'); ?>" 
                       class="carousel-next" href="#">&gt;</a>
                </div><!--end:carousel-nav-->

            </div><!--end:list-carousel-->

        <?php
        echo $after_widget;
    }

    function form($instance) {
        $default = array(
            'title' => '',
            'categories' => array(),
            'relation' => 'OR',
            'tags' => array(),
            'number_of_article' => 8,
            'orderby' => 'lastest',
        );
        $instance = wp_parse_args((array) $instance, $default);
        $title = strip_tags($instance['title']);

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['number_of_article'] = (int) $instance['number_of_article'];
        $form['orderby'] = $instance['orderby'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_categories();
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, (isset($form['categories']) ? $form['categories'] : array())) ) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                <?php
                $relation = array(
                    'AND' => __('And', kopa_get_domain()),
                    'OR' => __('Or', kopa_get_domain())
                );
                foreach ($relation as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $tags = get_tags();
                foreach ($tags as $tag) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, (isset($form['tags']) ? $form['tags'] : array()))) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number_of_article'); ?>"><?php _e('Number of article:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('number_of_article'); ?>" name="<?php echo $this->get_field_name('number_of_article'); ?>" autocomplete="off">
                <?php
                $number_of_article = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 15, 20, 25, 30);
                foreach ($number_of_article as $value) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $value, ($value == $form['number_of_article']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'lastest' => __('Lastest', kopa_get_domain()),
                    'popular' => __('Popular by View Count', kopa_get_domain()),
                    'most_comment' => __('Popular by Comment Count', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain()),
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <?php
    }

}

class Kopa_Widget_ArticleList_No_Thumbnail extends Kopa_Widget_ArticleList {

    function __construct() {
        $widget_ops = array('classname' => 'kopa_widget_blog', 'description' => __('Display list of articles filter by categories (and/or) tags ', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_blog', __('Kopa Article List No Thumbnail', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = esc_attr($instance['relation']);
        $query_args['tags'] = $instance['tags'];
        $query_args['number_of_article'] = (int) $instance['number_of_article'];
        $query_args['orderby'] = $instance['orderby'];

        $posts = kopa_widget_article_build_query($query_args);

        if ($posts->post_count <= 0)
            return;

        echo $before_widget;
        if (!empty($title))
            echo $before_title . $title . $after_title;
        ?>            

        <?php while ($posts->have_posts()) : $posts->the_post(); ?>

            <article class="entry-item clearfix">
                <div class="entry-date">
                    <p><?php the_time('j'); ?></p>
                    <strong><?php the_time('M'); ?></strong>
                </div>
                <div class="entry-content">
                    <h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <p class="entry-meta">
                        <span class="entry-category"><span class="icon-book entry-icon" aria-hidden="true"></span>In: <?php the_category(', '); ?></span>
                        <span class="entry-comment"><span class="icon-bubbles-4 entry-icon" aria-hidden="true"></span><a href="<?php comments_link(); ?>"><?php comments_number(__('No comments', kopa_get_domain()), '1', '%'); ?></a></span>
                    </p>
                    <p><?php echo strip_tags(get_the_excerpt()); ?></p>
                    <a href="<?php the_permalink(); ?>" class="more-link"><?php _e('Read more &raquo;', kopa_get_domain()); ?></a>
                </div>
            </article>    

        <?php endwhile; ?>

        <?php
        echo $after_widget;
    }

}

class Kopa_Widget_Client extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'kp-client-logo', 'description' => __('Display a widget of clients logo', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_client', __('Kopa Clients', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['posts_per_page'] = (int) $new_instance['posts_per_page'];
        $instance['orderby'] = $new_instance['orderby'];

        return $instance;
    }

    function form($instance) {
        $default = array(
            'title' => '',
            'categories' => array(),
            'relation' => 'OR',
            'tags' => array(),
            'posts_per_page' => 5,
            'orderby' => 'lastest',
        );
        $instance = wp_parse_args((array) $instance, $default);
        $title = strip_tags($instance['title']);

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['posts_per_page'] = (int) $instance['posts_per_page'];
        $form['orderby'] = $instance['orderby'];
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_terms('client_category');
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, (isset($form['categories']) ? $form['categories'] : array()))) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                <?php
                $relation = array(
                    'AND' => __('And', kopa_get_domain()),
                    'OR' => __('Or', kopa_get_domain())
                );
                foreach ($relation as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $tags = get_terms('client_tag');
                foreach ($tags as $tag) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, (isset($form['tags']) ? $form['tags'] : array()))) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('posts_per_page'); ?>"><?php _e('Number of items:', kopa_get_domain()); ?></label>                
            <input class="widefat" id="<?php echo $this->get_field_id('posts_per_page'); ?>" name="<?php echo $this->get_field_name('posts_per_page'); ?>" value="<?php echo $form['posts_per_page']; ?>" type="number" min="1">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'lastest' => __('Lastest', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain()),
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>

        <div class="kopa-clear"></div>
        <?php
    }

    function widget($args, $instance) {
        extract($args);

        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

        $query_args['post_type'] = 'clients';
        $query_args['cat_name'] = 'client_category';
        $query_args['tag_name'] = 'client_tag';
        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = esc_attr($instance['relation']);
        $query_args['tags'] = $instance['tags'];
        $query_args['posts_per_page'] = (int) $instance['posts_per_page'];
        $query_args['orderby'] = $instance['orderby'];

        echo $before_widget;

        $clients = kopa_widget_posttype_build_query($query_args);

        if ($clients->post_count <= 0)
            return;
        ?>

        <span class="bottom-circle"></span>
        <span class="bottom-bullet"></span>

        <div class="wrapper">
            <div class="row-fluid">
                <div class="span12">

                    <?php
                    if (!empty($title))
                        echo $before_title . $title . $after_title;
                    ?>

                    <ul class="clearfix">
                        <?php while ($clients->have_posts()) : $clients->the_post(); ?>
                            <li>
                                <a href="<?php echo get_post_meta(get_the_ID(), 'client_url', true); ?>">
                                    <?php the_post_thumbnail('kopa-image-size-4'); ?>
                                </a>
                            </li>
                        <?php endwhile; ?>
                    </ul>

                </div><!--span12-->
            </div><!--row-fluid-->
        </div><!--wrapper-->            

        <?php
        echo $after_widget;
    }

}

class Kopa_Widget_Testimonial extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'kp-testimonial', 'description' => __('Display a widget of testimonials', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_testimonial', __('Kopa Testimonials', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['posts_per_page'] = (int) $new_instance['posts_per_page'];
        $instance['orderby'] = $new_instance['orderby'];

        return $instance;
    }

    function form($instance) {
        $default = array(
            'title' => '',
            'categories' => array(),
            'relation' => 'OR',
            'tags' => array(),
            'posts_per_page' => 4,
            'orderby' => 'lastest',
        );
        $instance = wp_parse_args((array) $instance, $default);
        $title = strip_tags($instance['title']);

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['posts_per_page'] = (int) $instance['posts_per_page'];
        $form['orderby'] = $instance['orderby'];
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_terms('testimonial_category');
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, (isset($form['tags']) ? $form['tags'] : array()))) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                <?php
                $relation = array(
                    'AND' => __('And', kopa_get_domain()),
                    'OR' => __('Or', kopa_get_domain())
                );
                foreach ($relation as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $tags = get_terms('testimonial_tag');
                foreach ($tags as $tag) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, (isset($form['tags']) ? $form['tags'] : array()))) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('posts_per_page'); ?>"><?php _e('Number of items:', kopa_get_domain()); ?></label>                
            <input class="widefat" id="<?php echo $this->get_field_id('posts_per_page'); ?>" name="<?php echo $this->get_field_name('posts_per_page'); ?>" value="<?php echo $form['posts_per_page']; ?>" type="number" min="1">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'lastest' => __('Lastest', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain()),
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>

        <div class="kopa-clear"></div>
        <?php
    }

    function widget($args, $instance) {
        extract($args);

        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

        $query_args['post_type'] = 'testimonials';
        $query_args['cat_name'] = 'testimonial_category';
        $query_args['tag_name'] = 'testimonial_tag';
        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = esc_attr($instance['relation']);
        $query_args['tags'] = $instance['tags'];
        $query_args['posts_per_page'] = (int) $instance['posts_per_page'];
        $query_args['orderby'] = $instance['orderby'];

        echo $before_widget;

        $testimonials = kopa_widget_posttype_build_query($query_args);

        if ($testimonials->post_count <= 0)
            return;
        ?>

        <div class="wrapper">
            <div class="row-fluid">
                <div class="span12">
                    <?php
                    if (!empty($title))
                        echo $before_title . $title . $after_title;
                    ?>

                    <div class="list-carousel responsive" >

                        <ul class="testimonial-slider">
                            <?php
                            while ($testimonials->have_posts()) :
                                $testimonials->the_post();

                                $author_url = get_post_meta(get_the_ID(), 'author_url', true);
                                $author_site_name = preg_replace('/http:\/\//', '', $author_url);
                                ?>
                                <li style="width: 355px;">
                                    <article class="testimonial-item clearfix">
                                        <div class="testimonial-content clearfix">
                                            <span class="quote-icon"></span>
                                            <blockquote><?php echo strip_tags(get_the_excerpt()); ?></blockquote>
                                            <span class="arrow"></span>
                                        </div>
                                        <div class="testimonial-author clearfix">
                                            <?php the_post_thumbnail('kopa-image-size-5'); ?>
                                            <a class="author-name" href="<?php echo $author_url; ?>">
                                                <?php the_title(); ?>
                                            </a>
                                            <a href="<?php echo $author_url; ?>" class="author-url">
                                                <?php echo "-" . ucfirst($author_site_name); ?>
                                            </a>
                                        </div>
                                    </article><!--testimonial-item-->
                                </li>
                            <?php endwhile; ?>
                        </ul>
                        <div class="clearfix"></div>

                        <div class="carousel-nav clearfix">
                            <a id="<?php echo $this->get_field_id('prev'); ?>" class="carousel-prev" href="#">&lt;</a>
                            <a id="<?php echo $this->get_field_id('next'); ?>" class="carousel-next" href="#">&gt;</a>
                        </div><!--end:carousel-nav-->

                    </div><!--end:list-carousel-->

                </div><!--span12-->
            </div><!--row-fluid-->
        </div><!--wrapper-->

        <?php
        echo $after_widget;
    }

}

class Kopa_Widget_Staff extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'about-tab-content', 'description' => __('Display a widget of staffs', kopa_get_domain()));
        $control_ops = array('width' => '500', 'height' => 'auto');
        parent::__construct('kopa_widget_staff', __('Kopa Staffs', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['description'] = strip_tags($new_instance['description']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['posts_per_page'] = (int) $new_instance['posts_per_page'];
        $instance['orderby'] = $new_instance['orderby'];

        return $instance;
    }

    function form($instance) {
        $default = array(
            'title' => 'Meet the Team',
            'description' => '',
            'categories' => array(),
            'relation' => 'OR',
            'tags' => array(),
            'posts_per_page' => 4,
            'orderby' => 'lastest',
        );
        $instance = wp_parse_args((array) $instance, $default);
        $title = strip_tags($instance['title']);

        $form['description'] = strip_tags($instance['description']);
        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['posts_per_page'] = (int) $instance['posts_per_page'];
        $form['orderby'] = $instance['orderby'];
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Description:', kopa_get_domain()); ?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" rows="5"><?php echo esc_attr($form['description']); ?></textarea>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_terms('staff_category');
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, (isset($form['categories']) ? $form['categories'] : array()))) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                <?php
                $relation = array(
                    'AND' => __('And', kopa_get_domain()),
                    'OR' => __('Or', kopa_get_domain())
                );
                foreach ($relation as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $tags = get_terms('staff_tag');
                foreach ($tags as $tag) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, (isset($form['tags']) ? $form['tags'] : array()))) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('posts_per_page'); ?>"><?php _e('Number of items:', kopa_get_domain()); ?></label>                
            <input class="widefat" id="<?php echo $this->get_field_id('posts_per_page'); ?>" name="<?php echo $this->get_field_name('posts_per_page'); ?>" value="<?php echo $form['posts_per_page']; ?>" type="number" min="1">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'lastest' => __('Lastest', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain()),
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>

        <div class="kopa-clear"></div>
        <?php
    }

    function widget($args, $instance) {
        extract($args);

        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $description = $instance['description'];

        $query_args['post_type'] = 'staffs';
        $query_args['cat_name'] = 'staff_category';
        $query_args['tag_name'] = 'staff_tag';
        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = esc_attr($instance['relation']);
        $query_args['tags'] = $instance['tags'];
        $query_args['posts_per_page'] = (int) $instance['posts_per_page'];
        $query_args['orderby'] = $instance['orderby'];

        echo $before_widget;

        $staffs = kopa_widget_posttype_build_query($query_args);

        if ($staffs->post_count <= 0)
            return;
        ?>
        <div class="about-intro">
            <span class="bottom-circle"></span>
            <span class="bottom-bullet"></span>
            <div class="wrapper">
                <div class="row-fluid">
                    <div class="span12">  
                        <?php if (!empty($title)) : ?>
                            <h2><?php echo $title; ?></h2>        
                        <?php endif; ?>   

                        <?php if (!empty($description)) : ?>

                            <p><?php echo $description; ?></p>  

                        <?php endif; ?>  

                        <div class="about-team">
                            <div class="row-fluid">                 

                                <?php
                                $staff_index = 1;
                                while ($staffs->have_posts()) : $staffs->the_post();
                                    $staff_position = get_post_meta(get_the_ID(), 'position', true);
                                    $staff_facebook = get_post_meta(get_the_ID(), 'facebook', true);
                                    $staff_twitter = get_post_meta(get_the_ID(), 'twitter', true);
                                    $staff_gplus = get_post_meta(get_the_ID(), 'gplus', true);
                                    ?>
                                    <div class="span3">
                                        <article>
                                            <?php
                                            if (has_post_thumbnail())
                                                the_post_thumbnail('kopa-image-size-1');
                                            ?>
                                            <div class="team-content">
                                                <header>
                                                    <h2><?php the_title(); ?></h2>
                                                    <span><?php echo $staff_position; ?></span>
                                                </header>
                                                <p><?php echo strip_tags(get_the_content()); ?></p>
                                                <ul class="socials-link clearfix">
                                                    <?php if (!empty($staff_facebook)) : ?>
                                                        <li class="facebook-icon"><a href="<?php echo $staff_facebook; ?>"><span class="icon-facebook" aria-hidden="true"></span></a></li>
                                                    <?php endif; ?>

                                                    <?php if (!empty($staff_twitter)) : ?>
                                                        <li class="twitter-icon"><a href="<?php echo $staff_twitter; ?>"><span class="icon-twitter" aria-hidden="true"></span></a></li>
                                                    <?php endif; ?>

                                                    <?php if (!empty($staff_gplus)) : ?>
                                                        <li class="gplus-icon"><a href="<?php echo $staff_gplus; ?>"><span class="icon-google-plus" aria-hidden="true"></span></a></li>
                                                            <?php endif; ?>
                                                </ul><!--socials-link-->
                                            </div>
                                        </article>
                                    </div><!--span3-->

                                    <?php
                                    if ($staff_index % 4 == 0)
                                        echo '</div><div class="mt-30 row-fluid">';

                                    $staff_index++;

                                endwhile;
                                ?>

                            </div><!--row-fluid-->
                        </div><!--about-team-->
                    </div><!--span12-->
                </div><!--row-fluid-->
            </div><!--wrapper--> 
        </div> <!-- about-intro -->   
        <?php
        echo $after_widget;
    }

}