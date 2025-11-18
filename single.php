<?php
/**
 * Single Post
 *
 * @package      NWU2025
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

use NWU2025\Block_Areas;

/**
 * Display featured image for chapters
 * This fires right before the_content() in content.php
 */
function nwu_chapter_featured_image() {
	// Only run on chapter single posts
	if ( ! is_singular( 'chapter' ) || ! has_post_thumbnail() ) {
		return;
	}

	echo '<div class="chapter-featured-image">';
	the_post_thumbnail(
		'large',
		array(
			'alt'     => esc_attr( get_the_title() ),
			'loading' => 'eager',
		)
	);
	echo '</div>';
}
add_action( 'tha_entry_content_before', 'nwu_chapter_featured_image', 5 );

/**
 * After Post
 */
function be_after_post() {
	Block_Areas\show( 'after-post' );
}
add_action( 'tha_content_while_after', 'be_after_post', 8 );

// Build the page.
require get_template_directory() . '/index.php';
