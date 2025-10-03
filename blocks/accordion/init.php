<?php
/**
 * Accordion block initialization
 *
 * @package      NWU2025
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

namespace NWU2025\Blocks\Accordion;

/**
 * Enqueue accordion JavaScript
 */
function enqueue_scripts() {
	// Only enqueue if the block is used on the page
	if ( has_block( 'cwp/accordion' ) ) {
		wp_enqueue_script(
			'accordion-block',
			get_template_directory_uri() . '/blocks/accordion/script.js',
			[],
			filemtime( get_template_directory() . '/blocks/accordion/script.js' ),
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_scripts' );
