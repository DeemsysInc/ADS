<?php
get_header();

$template_setting = kopa_get_template_setting();
$sidebars = $template_setting['sidebars'];
?>

<div class="wrapper">
    <div class="row-fluid">
        <div class="span12">
            <div id="main-col">
                <div id="isotop-container">
                    <header id ="pf-options" class="pf-box-header clearfix">                                    
                        <ul id="pf-filters" class="pf-option-set clearfix" data-option-key="filter">                            
                            <li><a class="selected" href="#filter"  data-option-value="*">View All</a></li>
                            <?php
                            $projects = get_terms('portfolio_project');
                            foreach ($projects as $project):
                                ?>                        
                                <li><a data-option-value="<?php echo ".kopa-portfolio-{$project->slug}"; ?>" href="#filter"><?php echo $project->name; ?></a></li>                                    
                                <?php
                            endforeach;
                            ?>
                        </ul><!-- end #portfolio-items-filter -->
                    </header><!--pf-box-header-->

                    <div id="pf-items" class="clearfix">
                        <?php
                        $args['post_type'] = 'portfolio';
                        $args['posts_per_page'] = -1;
                        query_posts($args);
                        while (have_posts()) : the_post();
                            $post_id = get_the_ID();
                            $post_url = get_permalink();
                            $post_title = get_the_title();
                            $post_format = get_post_format();

                            $portfolio_project_links = array();
                            $portfolio_tag_links = array();

                            $portfolio_projects = wp_get_post_terms($post_id, 'portfolio_project');
                            $portfolio_tag = wp_get_post_terms($post_id, 'portfolio_tag');

                            $classes = array();
                            if ($portfolio_projects) {
                                foreach ($portfolio_projects as $project) {
                                    $classes[] = "kopa-portfolio-{$project->slug}";
                                    $portfolio_project_links[] = sprintf('<a href="%1$s" title="%2$s">%2$s</a>', get_term_link($project, 'portfolio_project'), $project->name);
                                }
                            }
                            $classes = implode(' ', $classes);

                            if ($portfolio_tag) {
                                foreach ($portfolio_tag as $tag) {
                                    $portfolio_tag_links[] = sprintf('<a href="%1$s" title="%2$s">%2$s</a>', get_term_link($tag, 'portfolio_tag'), $tag->name);
                                }
                            }
                            ?>

                            <article class="element <?php echo $classes; ?>" data-category="<?php echo $classes; ?>">
                                <div class="bwWrapper" >
                                    <?php the_post_thumbnail('kopa-image-size-2'); ?>
                                    <a href="<?php echo $post_url; ?>" class="kp-pf-detail">+</a>
                                </div>
                                <div class="pf-info">
                                    <span class="entry-view"><span class="icon-eye-4 entry-icon"></span><?php printf(__('%1$s Views', kopa_get_domain()), kopa_get_view_count($post_id)); ?></span>
                                    <span class="entry-like"><a class="icon-heart-3 entry-icon <?php echo 'kopa_like_button_' . kopa_get_like_permission($post_id); ?>" href="#" onclick="return kopa_like_button_click(jQuery(this),<?php echo $post_id; ?>);"></a><span class="kopa_like_count"><?php printf(__('%1$s Likes', kopa_get_domain()), kopa_get_like_count($post_id)); ?></span></span>
                                    <a class="pf-name" href="<?php echo $post_url; ?>"><?php echo $post_title; ?></a>
                                </div>                                                
                            </article>                                                  
                            <?php
                        endwhile;
                        wp_reset_query();
                        ?>
                    </div>
                </div>
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