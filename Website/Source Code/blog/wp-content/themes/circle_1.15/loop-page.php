<?php
while (have_posts()) : the_post();
    $post_id = get_the_ID();
    $post_url = get_permalink();
    $post_title = get_the_title();
    
    the_content();

    wp_link_pages(array('before' => '<div class="pagination">', 'after' => '</div>'));

    comments_template();
    
    kopa_get_facebook_comment();
    
endwhile;