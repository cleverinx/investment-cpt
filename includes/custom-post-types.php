<?php

// Register Custom Post Type for Investments
function create_investment_post_type() {
    $labels = array(
        'name' => 'Investments',
        'singular_name' => 'Investment',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'investment'),
        'supports' => array('title', 'editor', 'custom-fields'),
    );

    register_post_type('investment', $args);
}

add_action('init', 'create_investment_post_type');

// Register Custom Taxonomy for Investments
function create_investment_taxonomy() {
    $labels = array(
        'name' => 'Investment Categories',
        'singular_name' => 'Investment Category',
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'rewrite' => array('slug' => 'investment-category'),
    );

    register_taxonomy('investment_category', 'investment', $args);
}

add_action('init', 'create_investment_taxonomy');
