<?php
/**
 * Base Block Configuration
 * Ensures all custom blocks inherit theme styles
 *
 * @package      NWU2025
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

namespace NWU2025\Block_Config;

/**
 * Set default block supports for all custom blocks
 * This ensures consistency across all blocks
 */
function set_block_defaults( $metadata ) {

	// Only apply to custom blocks (cwp namespace)
	if ( strpos( $metadata['name'], 'cwp/' ) === false ) {
		return $metadata;
	}

	// Default supports if not already set
	$default_supports = array(
		'align' => array( 'wide', 'full' ),
		'anchor' => true,
		'color' => array(
			'text' => true,
			'background' => true,
			'gradients' => false,
			'link' => false,
		),
		'spacing' => array(
			'margin' => true,
			'padding' => true,
			'blockGap' => true,
		),
		'typography' => array(
			'fontSize' => true,
			'lineHeight' => true,
		),
	);

	// Merge with existing supports
	if ( ! isset( $metadata['supports'] ) ) {
		$metadata['supports'] = array();
	}

	$metadata['supports'] = array_merge( $default_supports, $metadata['supports'] );

	return $metadata;
}
add_filter( 'block_type_metadata', __NAMESPACE__ . '\\set_block_defaults' );

/**
 * Add custom block category
 */
function add_block_category( $categories ) {
	return array_merge(
		array(
			array(
				'slug'  => 'nwu-blocks',
				'title' => __( 'NWU Custom Blocks', 'nwu-2025' ),
				'icon'  => 'screenoptions',
			),
		),
		$categories
	);
}
add_filter( 'block_categories_all', __NAMESPACE__ . '\\add_block_category' );

/**
 * Enqueue block editor assets for ALL custom blocks
 * This ensures editor styles match frontend
 */
function enqueue_block_editor_assets() {

	// Global block editor styles
	wp_enqueue_style(
		'nwu-block-editor-global',
		get_template_directory_uri() . '/assets/css/block-editor-global.css',
		array(),
		filemtime( get_template_directory() . '/assets/css/block-editor-global.css' )
	);

	// Global block editor scripts (if needed)
	wp_enqueue_script(
		'nwu-block-editor-global',
		get_template_directory_uri() . '/assets/js/block-editor-global.js',
		array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ),
		filemtime( get_template_directory() . '/assets/js/block-editor-global.js' ),
		true
	);
}
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\enqueue_block_editor_assets' );

/**
 * Add allowed block types (optional - restrict what blocks users can use)
 * Uncomment and customize if you want to limit available blocks
 */
/*
function allowed_block_types( $allowed_blocks, $editor_context ) {

	// For pages, allow all blocks
	if ( $editor_context->post->post_type === 'page' ) {
		return $allowed_blocks;
	}

	// For posts, restrict to specific blocks
	$allowed_blocks = array(
		// Core blocks
		'core/paragraph',
		'core/heading',
		'core/image',
		'core/list',
		'core/quote',
		'core/button',
		'core/buttons',
		'core/separator',
		'core/spacer',
		'core/group',
		'core/columns',

		// Your custom blocks (add as you create them)
		'cwp/card-slider',
		'cwp/social-share',
		'cwp/social-links',
		// Add more custom blocks here
	);

	return $allowed_blocks;
}
add_filter( 'allowed_block_types_all', __NAMESPACE__ . '\\allowed_block_types', 10, 2 );
*/

/**
 * Add custom color palette labels for better UX
 */
function customize_color_palette_labels() {

	// This is already handled by theme.json, but you can add
	// additional JavaScript customization here if needed

}
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\customize_color_palette_labels' );

/**
 * Set default block patterns (optional)
 * Uncomment to disable core patterns
 */
/*
function disable_core_patterns() {
	remove_theme_support( 'core-block-patterns' );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\\disable_core_patterns' );
*/
