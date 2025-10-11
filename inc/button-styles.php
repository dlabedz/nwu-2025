<?php
/**
 * Button Styles - Figma Design System
 *
 * @package      NWU2025
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

namespace NWU2025\Button_Styles;

/**
 * Register Button Styles
 */
function register_button_styles() {

	// Unregister default WordPress button styles
	unregister_block_style( 'core/button', 'fill' );
	unregister_block_style( 'core/button', 'outline' );

	// Register Figma button styles
	register_block_style(
		'core/button',
		array(
			'name'  => 'pill',
			'label' => __( 'Pill Button', 'nwu-2025' ),
		)
	);

	register_block_style(
		'core/button',
		array(
			'name'  => 'pull',
			'label' => __( 'Pull Button', 'nwu-2025' ),
		)
	);

	register_block_style(
		'core/button',
		array(
			'name'  => 'secondary',
			'label' => __( 'Secondary', 'nwu-2025' ),
		)
	);

	register_block_style(
		'core/button',
		array(
			'name'  => 'tertiary',
			'label' => __( 'Tertiary', 'nwu-2025' ),
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\\register_button_styles' );

/**
 * Add editor stylesheet for button previews
 */
function button_editor_styles() {
	add_editor_style( 'assets/css/button-editor.css' );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\\button_editor_styles' );
