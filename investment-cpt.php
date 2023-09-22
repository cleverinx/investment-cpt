<?php
/*
Plugin Name: Investment Custom Post Type
Description: Custom post type and taxonomy for managing investments.
Version: 1.0
*/
//import custom post types and fields
require_once plugin_dir_path(__FILE__) . 'includes/custom-post-types.php';
require_once plugin_dir_path(__FILE__) . 'includes/custom-fields.php';

function investment_block_assets() {
    wp_enqueue_script(
        'investment-block-script',
        plugin_dir_url(__FILE__) . 'investment-block/build/index.js',
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-api', 'wp-i18n'),
        filemtime(plugin_dir_path(__FILE__) . 'investment-block/build/index.js')
    );

    wp_enqueue_style(
        'investment-block-style',
        plugin_dir_url(__FILE__) . 'investment-block/build/index.css',
        array(),
        filemtime(plugin_dir_path(__FILE__) . 'investment-block/build/index.css')
    );
}

add_action('enqueue_block_editor_assets', 'investment_block_assets');


function investment_block_investment_block_block_init() {
	register_block_type( __DIR__ . '/investment-block/build' ,

	  array(
            'editor_script' => 'investment-block-script',
            'editor_style'  => 'investment-block-style',
		  'attributes' => [
		'selectedCategories' => [
			'default' => '[]',
			'type'    => 'array'
		],
	],
        ));
}
add_action( 'init', 'investment_block_investment_block_block_init' );


