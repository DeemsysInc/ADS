<?php

add_action('init', 'kopa_portfolio_init');

function kopa_portfolio_init() {
    $labels = array(
        'name' => __('Portfolios', kopa_get_domain()),
        'singular_name' => __('Portfolio', kopa_get_domain()),
        'add_new' => __('Add New', kopa_get_domain()),
        'add_new_item' => __('Add New Item', kopa_get_domain()),
        'edit_item' => __('Edit Item', kopa_get_domain()),
        'new_item' => __('New Item', kopa_get_domain()),
        'all_items' => __('All Items', kopa_get_domain()),
        'view_item' => __('View Item', kopa_get_domain()),
        'search_items' => __('Search Items', kopa_get_domain()),
        'not_found' => __('No items found', kopa_get_domain()),
        'not_found_in_trash' => __('No items found in Trash', kopa_get_domain()),
        'parent_item_colon' => '',
        'menu_name' => __('Portfolio', kopa_get_domain())
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'portfolio'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'exclude_from_search' => true,
        'menu_position' => 100,
        'supports' => array('title', 'thumbnail', 'excerpt', 'editor', 'post-formats'),
        'can_export' => true,
        'register_meta_box_cb' => ''
    );

    register_post_type('portfolio', $args);

    $taxonomy_category_args = array(
        'public' => true,
        'hierarchical' => true,
        'labels' => array(
            'name' => __('Portfolio Projects', 'taxonomy general name', kopa_get_domain()),
            'singular_name' => __('Project', 'taxonomy singular name', kopa_get_domain()),
            'search_items' => __('Search Project', kopa_get_domain()),
            'popular_items' => __('Popular Projects', kopa_get_domain()),
            'all_items' => __('All Projects', kopa_get_domain()),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => __('Edit Project', kopa_get_domain()),
            'update_item' => __('Update Project', kopa_get_domain()),
            'add_new_item' => __('Add New Project', kopa_get_domain()),
            'new_item_name' => __('New Project Name', kopa_get_domain()),
            'separate_items_with_commas' => __('Separate projects with commas', kopa_get_domain()),
            'add_or_remove_items' => __('Add or remove category', kopa_get_domain()),
            'choose_from_most_used' => __('Choose from the most used projects', kopa_get_domain()),
            'menu_name' => __('Projects', kopa_get_domain())
        ),
        'show_ui' => true,
        'show_admin_column' => true,
        'update_count_callback' => '',
        'query_var' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'rewrite' => array('slug' => 'portfolio_project')
    );

    register_taxonomy('portfolio_project', 'portfolio', $taxonomy_category_args);

    #TAXONOMY TAG
    $taxonomy_tag_args = array(
        'public' => true,
        'hierarchical' => false,
        'labels' => array(
            'name' => __('Portfolio Tags', 'taxonomy general name', kopa_get_domain()),
            'singular_name' => __('Tag', 'taxonomy singular name', kopa_get_domain()),
            'search_items' => __('Search Tag', kopa_get_domain()),
            'popular_items' => __('Popular Tags', kopa_get_domain()),
            'all_items' => __('All Tags', kopa_get_domain()),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => __('Edit Tag', kopa_get_domain()),
            'update_item' => __('Update Tag', kopa_get_domain()),
            'add_new_item' => __('Add New Tag', kopa_get_domain()),
            'new_item_name' => __('New Tag Name', kopa_get_domain()),
            'separate_items_with_commas' => __('Separate tags with commas', kopa_get_domain()),
            'add_or_remove_items' => __('Add or remove tag', kopa_get_domain()),
            'choose_from_most_used' => __('Choose from the most used tags', kopa_get_domain()),
            'menu_name' => __('Tags', kopa_get_domain())
        ),
        'show_ui' => true,
        'show_admin_column' => true,
        'update_count_callback' => '',
        'query_var' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'rewrite' => array('slug' => 'portfolio_tag')
    );

    register_taxonomy('portfolio_tag', 'portfolio', $taxonomy_tag_args);

    flush_rewrite_rules(false);    
}