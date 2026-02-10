<?php
/**
 * Single Chapter Template
 *
 * @package      NWU2025
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

use NWU2025\Block_Areas;

/**
 * Display chapter header with featured image and contact info
 */
function nwu_chapter_header() {
	if ( ! is_singular( 'chapter' ) ) {
		return;
	}

	$has_thumbnail = has_post_thumbnail();
	$caption = get_field( 'chapter_image_caption' );
	$contact_info = get_field( 'chapter_contact_info' );

	// Only display if we have image or contact info
	if ( ! $has_thumbnail && ! $contact_info ) {
		return;
	}

	echo '<div class="chapter-header">';

	// Left column: Featured image + caption
	if ( $has_thumbnail || $caption ) {
		echo '<div class="chapter-header__image-column">';

		if ( $has_thumbnail ) {
			echo '<div class="chapter-header__image">';
			the_post_thumbnail(
				'large',
				array(
					'alt'     => esc_attr( get_the_title() ),
					'loading' => 'eager',
				)
			);
			echo '</div>';
		}

		if ( $caption ) {
			echo '<div class="chapter-header__caption">';
			echo wp_kses_post( wpautop( $caption ) );
			echo '</div>';
		}

		echo '</div>'; // .chapter-header__image-column
	}

	// Right column: Contact info
	if ( $contact_info ) {
		echo '<div class="chapter-header__contact-column">';
		echo '<div class="chapter-header__contact">';
		echo wp_kses_post( $contact_info );
		echo '</div>';
		echo '</div>'; // .chapter-header__contact-column
	}

	echo '</div>'; // .chapter-header
}
add_action( 'tha_entry_content_before', 'nwu_chapter_header', 5 );

/**
 * After Post
 */
function be_after_post() {
	Block_Areas\show( 'after-post' );
}
add_action( 'tha_content_while_after', 'be_after_post', 8 );

// Build the page.
require get_template_directory() . '/index.php';
