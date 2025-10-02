<?php
/**
 * All in One SEO
 *
 * @package      NWU2025
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

/**
 * Breadcrumbs
 */
function be_breadcrumbs() {
	// Don't show on front page
	if ( is_front_page() ) {
		return;
	}

	// Show on single posts, pages, and archives for your key post types
	$show_on_post_types = array( 'post', 'page', 'chapter', 'event' ); // Add your custom post types
	$show_on_archives = array( 'post', 'chapter', 'event' ); // Post types to show breadcrumbs on archives

	$should_show = false;

	if ( is_singular() ) {
		$post_type = get_post_type();
		$should_show = in_array( $post_type, $show_on_post_types );
	} elseif ( is_post_type_archive() || is_category() || is_tag() || is_tax() ) {
		$queried_object = get_queried_object();
		if ( isset( $queried_object->name ) ) {
			// For post type archives
			$should_show = in_array( $queried_object->name, $show_on_archives );
		} elseif ( isset( $queried_object->taxonomy ) ) {
			// For taxonomy archives
			$taxonomy = get_taxonomy( $queried_object->taxonomy );
			if ( $taxonomy && isset( $taxonomy->object_type[0] ) ) {
				$should_show = in_array( $taxonomy->object_type[0], $show_on_archives );
			}
		}
	}

	if ( $should_show && function_exists( 'aioseo_breadcrumbs' ) ) {
		aioseo_breadcrumbs();
	}
}
add_action( 'tha_content_top', 'be_breadcrumbs' );

/**
 * Custom breadcrumb separator with chevron icon
 * All in One SEO passes the separator through multiple times, so we need to check if it's already our icon
 */
function be_aioseo_breadcrumb_separator( $separator ) {
	// Prevent double-wrapping
	if ( strpos( $separator, 'breadcrumb-separator' ) !== false ) {
		return $separator;
	}

	return be_icon( array(
		'icon' => 'chevron-large-right',
		'size' => 14,
		'class' => 'breadcrumb-separator',
		'force' => true // Force inline SVG
	) );
}
add_filter( 'aioseo_breadcrumbs_separator', 'be_aioseo_breadcrumb_separator' );
