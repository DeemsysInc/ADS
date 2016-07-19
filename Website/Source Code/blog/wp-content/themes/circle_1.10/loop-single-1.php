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
    <div <?php post_class(); ?>>  
        <?php
        if (!in_array($post_format, array('aside', 'gallery', 'video', 'audio', 'quote')) && has_post_thumbnail()):
            ?>
            <div class="entry-thumb">                       
                <?php the_post_thumbnail('kopa-image-size-2'); ?>
            </div>
            <?php
        endif;
        ?> 
        <header class="entry-header">
            <?php if ('aside' !== $post_format): ?>
                <h2 class="entry-title"><?php echo $post_title; ?></h2>
            <?php endif; ?>
            <div class="entry-meta-box">
                <div class="entry-meta-box-inner">
                    <span class="entry-date"><span class="icon-clock-4 entry-icon"></span><?php echo get_the_date(); ?></span>
                    <span class="entry-author"><span class="icon-user entry-icon"></span><?php _e('by:', kopa_get_domain()); ?>&nbsp;<a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php the_author_meta('nicename'); ?></a></span>
                    <?php if (has_category()): ?>
                        <span class="entry-category"><span class="icon-book entry-icon"></span><?php _e('in:', kopa_get_domain()); ?>&nbsp;<?php the_category(', '); ?></span>
                    <?php endif; ?>
                    <span class="entry-comment"><span class="icon-bubbles-4 entry-icon"></span><?php comments_popup_link(__('No Comment', kopa_get_domain()), __('1 Comment', kopa_get_domain()), __('% Comments', kopa_get_domain()), '', __('Comments Off', kopa_get_domain())); ?></span>                               
                </div>
                <span class="entry-meta-circle"></span>
                <span class="entry-meta-icon" data-icon="<?php echo $post_format_icon; ?>"></span>
            </div>
        </header>

        <?php the_content(); ?>

        <?php
        wp_link_pages(array('before' => '<div class="pagination">', 'after' => '</div>'));
        ?>       

        <footer class="clearfix">
            <p class="prev-post">
                <?php previous_post_link('%link', __('&laquo;&nbsp;Older Article', kopa_get_domain()), true); ?>                
            </p>
            <p class="next-post">
                <?php next_post_link('%link', __('Next Article&nbsp;&raquo;', kopa_get_domain()), true); ?>                   
            </p>
        </footer>
    </div>

    <?php kopa_get_socials_link(); ?>

    <?php kopa_get_about_author(); ?>

    <?php comments_template(); ?>

    <?php kopa_get_related_articles(); ?>
    <?php
endwhile;
?>