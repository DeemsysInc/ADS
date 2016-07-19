<?php
get_header();

$template_setting = kopa_get_template_setting();
$sidebars = $template_setting['sidebars'];
?>

<div class="wrapper">
    <div class="row-fluid">
        <div class="span12">
            <div id="main-col">
                <?php
                while (have_posts()) : the_post();
                    $post_id = get_the_ID();
                    $post_url = get_permalink();
                    $post_title = get_the_title();
                    $post_format = get_post_format();
                    $full_image = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'full');

                    $portfolio_project_links = array();
                    $portfolio_tag_links = array();

                    $portfolio_projects = wp_get_post_terms($post_id, 'portfolio_project');
                    $portfolio_tag = wp_get_post_terms($post_id, 'portfolio_tag');

                    if ($portfolio_projects) {
                        foreach ($portfolio_projects as $project) {
                            $portfolio_project_links[] = sprintf('<a href="%1$s" title="%2$s">%2$s</a>', get_term_link($project, 'portfolio_project'), $project->name);
                        }
                    }
                    if ($portfolio_tag) {
                        foreach ($portfolio_tag as $tag) {
                            $portfolio_tag_links[] = sprintf('<a href="%1$s" title="%2$s">%2$s</a>', get_term_link($tag, 'portfolio_tag'), $tag->name);
                        }
                    }
                    ?>
                    <div class="portfolio-detail">

                        <article class="pf-detail-item">

                            <h3><?php echo $post_title; ?></h3>

                            <div class="entry-meta-box">

                                <ul class="entry-meta">
                                    <li><span class="entry-date"><span class="icon-clock-4 entry-icon"></span><?php echo get_the_date(); ?></span></li>
                                    <li><span class="entry-author"><span class="icon-user entry-icon"></span><a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php the_author_meta('nicename'); ?></a></span></li>
                                    <li><span class="entry-view"><span class="icon-eye-4 entry-icon"></span><?php printf(__('%1$s Views', kopa_get_domain()), kopa_get_view_count($post_id)); ?></span></li>
                                    <li><span class="entry-like"><a class="icon-heart-3 entry-icon <?php echo 'kopa_like_button_' . kopa_get_like_permission($post_id); ?>" href="#" onclick="return kopa_like_button_click(jQuery(this),<?php echo $post_id; ?>);"></a><span class="kopa_like_count"><?php printf(__('%1$s Likes', kopa_get_domain()), kopa_get_like_count($post_id)); ?></span></span></li>
                                   <?php if($portfolio_tag_links):?>
                                    <li><span class="entry-tag"><span class="icon-tags entry-icon"></span><?php echo implode(', ', $portfolio_tag_links); ?></span></li>
                                    <?php endif;?>
                                    <li><span class="entry-category"><span class="icon-book entry-icon"></span><?php echo implode(', ', $portfolio_project_links); ?></span></li>
                                </ul>

                                <ul class="socials-link clearfix">                                    
                                    <li><a href="<?php printf('http://www.facebook.com/sharer.php?u=%1$s&t=%2$s', $post_url, $post_title); ?>"><span aria-hidden="true" class="icon-facebook"></span></a></li>
                                    <li><a href="<?php printf('http://twitter.com/home?status=%1$s %2$s', $post_title, $post_url); ?>"><span aria-hidden="true" class="icon-twitter"></span></a></li>                                    
                                    <li><a href="<?php printf('http://google.com/bookmarks/mark?op=edit&bkmk=%1$s&title=%2$s', $post_url, $post_title); ?>"><span aria-hidden="true" class="icon-google-plus"></span></a></li>
                                    <li><a href="<?php printf('http://pinterest.com/pin/create/button/?url=%1$s&description=%2$s&media=%3$s', $post_url, $post_title, $full_image[0]); ?>"><span aria-hidden="true" class="icon-pinterest"></span></a></li>
                                    <li><a href="<?php printf('http://linkedin.com/shareArticle?mini=true&url=%1$s&title=%2$s', $post_url, $post_title); ?>"><span aria-hidden="true" class="icon-linkedin"></span></a></li>
                                </ul>

                            </div><!--entry-meta-box-->

                            <div class="pf-detail-content">
                                <?php if (!in_array($post_format, array('video', 'gallery', 'audio', 'quote', 'aside')) && has_post_thumbnail()): ?>
                                    <div class="pf-thumb">
                                        <?php the_post_thumbnail('kopa-image-size-2'); ?>
                                    </div>
                                <?php endif; ?>
                                <?php the_content(); ?>
                            </div>

                            <div class="clear"></div>

                            <nav class="pf-detail-nav clearfix">&nbsp;
                                <?php previous_post_link('%link', null); ?>                                
                                <?php next_post_link('%link', null); ?>                             
                            </nav><!--end:pf-detail-nav-->

                        </article><!--pf-detail-item-->

                        <?php kopa_get_related_portfolio(); ?>

                    </div><!--portfolio-detail-->

                    <?php
                endwhile;
                ?>
            </div><!--main-col-->

            <div class="sidebar widget-area-1">
                <?php
                if (is_active_sidebar($sidebars[0])):
                    dynamic_sidebar($sidebars[0]);
                endif;
                ?>
            </div><!--sidebar-->

            <div class="clear"></div>

        </div><!--span12-->

    </div><!--row-fluid-->  

</div><!--wrapper-->

<?php
get_footer();