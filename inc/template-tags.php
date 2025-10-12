<?php
/**
 * Template Tags
 *
 * @package      NWU2025
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

/**
 * Entry Category
 *
 */
function be_entry_category() {
	$term = be_first_term();
	if( !empty( $term ) && ! is_wp_error( $term ) )
		echo '<p class="entry-category"><a href="' . get_term_link( $term, 'category' ) . '">' . $term->name . '</a></p>';
}

/**
 * Post Summary Title
 *
 */
function be_post_summary_title() {
	global $wp_query;
	$tag = ( is_singular() || -1 === $wp_query->current_post ) ? 'h3' : 'h2';
	echo '<' . $tag . ' class="post-summary__title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></' . $tag . '>';
}

/**
 * Post Summary Image
 *
 */
function be_post_summary_image( $size = 'thumbnail_medium' ) {
	echo '<a class="post-summary__image" href="' . get_permalink() . '" tabindex="-1" aria-hidden="true">' . wp_get_attachment_image( be_entry_image_id(), $size ) . '</a>';
}


/**
 * Entry Image ID
 *
 */
function be_entry_image_id() {
	return has_post_thumbnail() ? get_post_thumbnail_id() : get_option( 'options_be_default_image' );
}

/**
 * Entry Author
 *
 */
function be_entry_author() {
	$id = (int) get_the_author_meta( 'ID' );
	echo '<p class="entry-author"><a href="' . get_author_posts_url( $id ) . '" aria-hidden="true" tabindex="-1">' . get_avatar( $id, 40 ) . '</a><em>by</em> <a href="' . get_author_posts_url( $id ) . '">' . get_the_author() . '</a></p>';
}

/**
 * Page Title Header
 * Displays H1 page title on all pages except front page
 * Appears below breadcrumbs
 */
function be_page_title_header() {
	// Don't display on front page
	if ( is_front_page() ) {
		return;
	}

	// Don't display if content has an H1 block
	if ( be_has_h1_block() ) {
		return;
	}

	$title = '';

	// Get appropriate title based on page type
	if ( is_singular() ) {
		$title = get_the_title();
	} elseif ( is_search() ) {
		$title = sprintf( __( 'Search Results for: %s', 'nwu-2025' ), get_search_query() );
	} elseif ( is_404() ) {
		$title = __( 'Page Not Found', 'nwu-2025' );
	} elseif ( is_archive() ) {
		$title = get_the_archive_title();
	}

	// Output title if we have one
	if ( ! empty( $title ) ) {
		echo '<h1 class="page-title u-width-constrained">' . wp_kses_post( $title ) . '</h1>';
	}
}
add_action( 'tha_content_top', 'be_page_title_header', 15 );
