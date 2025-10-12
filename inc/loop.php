<?php
/**
 * Loop
 *
 * @package      NWU2025
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

/**
 * Default Loop
 */
function be_default_loop() {

	if ( have_posts() ) :

		tha_content_while_before();

		/* Start the Loop */
		while ( have_posts() && ! is_404() ) :
			the_post();
			tha_entry_before();
			$partial = is_singular() ? 'content' : 'archive';
			get_template_part( 'partials/content/' . $partial );
			tha_entry_after();
		endwhile;

		tha_content_while_after();

	else :

		tha_entry_before();
		$context = apply_filters( 'be_empty_loop_partial_context', 'no-content' );
		get_template_part( 'partials/content/' . $context );
		tha_entry_after();

	endif;

}
add_action( 'tha_content_loop', 'be_default_loop' );

/**
 * Entry Title
 */
function be_entry_header() {
	if ( be_has_h1_block() ) {
		add_filter( 'render_block', 'be_entry_header_in_content', 10, 2 );

	} else {
		do_action( 'be_entry_title_before' );
		echo '<h1 class="entry-title">' . esc_html( get_the_title() ) . '</h1>';
		do_action( 'be_entry_title_after' );
	}
}
add_action( 'tha_entry_top', 'be_entry_header' );

/**
 * Entry header in content
 *
 * @param string $output Outout.
 * @param array  $block Block.
 */
function be_entry_header_in_content( $output, $block ) {
	if ( 'core/heading' === $block['blockName'] && ! empty( $block['attrs'] ) && ! empty( $block['attrs']['level'] ) && 1 === $block['attrs']['level'] ) {
		ob_start();
		do_action( 'be_entry_title_before' );
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Value is hardcoded and safe, not set via input.
		echo $output;
		do_action( 'be_entry_title_after' );
		$output = ob_get_clean();
		remove_filter( 'render_block', 'be_entry_header_in_content', 10 );
	}

	return $output;
}

/**
 * Recursively searches content for h1 blocks.
 *
 * @link https://www.billerickson.net/building-a-header-block-in-wordpress/
 *
 * @param array $blocks Blocks.
 */
function be_has_h1_block( $blocks = array() ) {

	if ( is_singular() && empty( $blocks ) ) {
		global $post;
		$blocks = parse_blocks( $post->post_content );
	}

	foreach ( $blocks as $block ) {

		if ( ! isset( $block['blockName'] ) ) {
			continue;
		}

		// Custom header block.
		if ( 'acf/header' === $block['blockName'] ) {
			return true;

			// Heading block.
		} elseif ( 'core/heading' === $block['blockName'] && isset( $block['attrs']['level'] ) && 1 === $block['attrs']['level'] ) {
			return true;

			// Scan inner blocks for headings.
		} elseif ( isset( $block['innerBlocks'] ) && ! empty( $block['innerBlocks'] ) ) {
			$inner_h1 = be_has_h1_block( $block['innerBlocks'] );
			if ( $inner_h1 ) {
				return true;
			}
		}
	}

	return false;
}

/**
 * Display Event Meta
 */
function be_display_event_meta() {
	if ( 'event' !== get_post_type() ) {
		return;
	}

	$event_date = get_field( 'event_date' );
	$event_time = get_field( 'event_time' );
	$event_location = get_field( 'event_location' );

	if ( ! $event_date && ! $event_time && ! $event_location ) {
		return;
	}

	echo '<div class="event-meta">';

	if ( $event_date ) {
		$formatted_date = date_i18n( 'F j, Y', strtotime( $event_date ) );
		echo '<div class="event-meta__item event-meta__date">';
		echo be_icon( [ 'icon' => 'calendar', 'size' => 20 ] );
		echo '<span>' . esc_html( $formatted_date ) . '</span>';
		echo '</div>';
	}

	if ( $event_time ) {
		echo '<div class="event-meta__item event-meta__time">';
		echo be_icon( [ 'icon' => 'clock', 'size' => 20 ] );
		echo '<span>' . esc_html( $event_time ) . '</span>';
		echo '</div>';
	}

	if ( $event_location ) {
		echo '<div class="event-meta__item event-meta__location">';
		echo be_icon( [ 'icon' => 'location', 'size' => 20 ] );
		echo '<span>' . esc_html( $event_location ) . '</span>';
		echo '</div>';
	}

	echo '</div>';
}
add_action( 'be_entry_title_after', 'be_display_event_meta' );
