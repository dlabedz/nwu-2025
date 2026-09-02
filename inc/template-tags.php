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
 * Entry Meta (Date & Categories)
 * Displays published date and category pills on single posts
 * Appears below breadcrumbs, above the title
 */
function be_entry_meta() {
	if ( ! is_singular( 'post' ) ) {
		return;
	}

	$categories = get_the_category();

	echo '<div class="entry-meta u-width-constrained">';
	echo '<time class="entry-meta__date" datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date( 'F j Y' ) ) . '</time>';

	if ( ! empty( $categories ) ) {
		echo '<div class="entry-meta__categories">';
		foreach ( $categories as $category ) {
			echo '<a href="' . esc_url( get_category_link( $category ) ) . '" class="entry-meta__category">' . esc_html( $category->name ) . '</a>';
		}
		echo '</div>';
	}

	echo '</div>';
}
add_action( 'tha_content_top', 'be_entry_meta', 12 );

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

/**
 * Post Footer (Tags & Post Navigation)
 * Displays post tags and previous/next post links on single posts.
 * Only renders the pieces that exist.
 */
function be_post_footer() {
	if ( ! is_singular( 'post' ) ) {
		return;
	}

	$tags          = get_the_tags();
	$has_tags      = $tags && ! is_wp_error( $tags );
	$previous_post = get_previous_post();
	$next_post     = get_next_post();

	if ( ! $has_tags && ! $previous_post && ! $next_post ) {
		return;
	}

	$arrow = '<span class="post-footer__nav-arrow-icon"><img src="' . esc_url( get_template_directory_uri() . '/assets/images/black-arrow.svg' ) . '" alt="" aria-hidden="true"></span>';

	echo '<div class="post-footer u-width-constrained">';

	if ( $has_tags ) {
		echo '<div class="post-footer__tags">';
		echo '<h4 class="post-footer__tags-label">' . esc_html__( 'Post Tags', 'nwu-2025' ) . '</h4>';
		echo '<div class="post-footer__tag-list">';
		foreach ( $tags as $tag ) {
			echo '<a href="' . esc_url( get_tag_link( $tag ) ) . '" class="post-footer__tag">' . esc_html( $tag->name ) . '</a>';
		}
		echo '</div>';
		echo '</div>';
	}

	if ( $previous_post || $next_post ) {
		echo '<nav class="post-footer__nav" aria-label="' . esc_attr__( 'Post navigation', 'nwu-2025' ) . '">';

		if ( $next_post ) {
			next_post_link(
				'<div class="post-footer__nav-item post-footer__nav-item--newer">%link</div>',
				$arrow . '<span>' . esc_html__( 'Newer Post', 'nwu-2025' ) . '</span>',
				false
			);
		}

		if ( $previous_post ) {
			previous_post_link(
				'<div class="post-footer__nav-item post-footer__nav-item--older">%link</div>',
				'<span>' . esc_html__( 'Older Post', 'nwu-2025' ) . '</span>' . $arrow,
				false
			);
		}

		echo '</nav>';
	}

	echo '</div>';
}
add_action( 'tha_content_while_after', 'be_post_footer', 9 );
