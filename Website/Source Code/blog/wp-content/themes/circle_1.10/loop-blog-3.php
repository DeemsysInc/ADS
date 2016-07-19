<?php
global $post;
while (have_posts()) : the_post();
    $post_id = get_the_ID();
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

    $article_classes[] = 'clearfix';
    ?>


    <li <?php post_class(); ?>>
        <article class="<?php echo implode(' ', $article_classes); ?>">

            <div class="entry-meta-column">
                <p data-icon="<?php echo $post_format_icon; ?>"></p>
                <span><?php _e('Posted by', kopa_get_domain()); ?></span>
                <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php the_author_meta('nicename'); ?></a>
            </div><!--entry-meta-column-->

            <div class="entry-item">                                                                                                                        
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
                <div class="entry-content clearfix">
                    <header class="entry-header">
                        <h2 class="entry-title">
                            <a href="<?php echo $post_url; ?>"><?php echo $post_title; ?></a>
                        </h2>

                        <div class="entry-meta-box">
                            <div class="entry-meta-box-inner">
                                <span class="entry-date"><span class="icon-clock-4 entry-icon"></span><?php echo get_the_date(); ?></span>                                                        
                                <?php if (has_category()): ?>
                                    <span class="entry-category"><span class="icon-book entry-icon"></span><?php _e('in:', kopa_get_domain()); ?>&nbsp;<?php the_category(', '); ?></span>
                                <?php endif; ?>
                                <span class="entry-comment"><span class="icon-bubbles-4 entry-icon"></span><?php comments_popup_link(__('No Comment', kopa_get_domain()), __('1 Comment', kopa_get_domain()), __('% Comments', kopa_get_domain()), '', __('Comments Off', kopa_get_domain())); ?></span>                               
                            </div>
                            <span class="entry-meta-circle"></span>
                            <span class="entry-meta-icon" data-icon="<?php echo $post_format_icon; ?>"></span>
                        </div>
                    </header>
                    <?php the_excerpt(); ?>                    
                    <a class="more-link" href="<?php echo $post_url; ?>"><?php _e('Read more &raquo;' , kopa_get_domain()) ; ?></a>                    
                </div><!--entry-content-->
            </div>
        </article>
    </li>                   
    <?php
endwhile;
?>