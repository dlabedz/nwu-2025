<?php
/**
 * Singular partial
 *
 * @package      NWU2025
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

echo '<article class="' . esc_attr( join( ' ', get_post_class() ) ) . '">';

if ( is_singular( 'post' ) && has_post_thumbnail() ) {
	echo '<div class="entry-featured-image">' . get_the_post_thumbnail( get_the_ID(), 'large' ) . '</div>';
}

echo '<div class="entry-content">';
tha_entry_content_before();
the_content();

wp_link_pages(
	[
		'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'bestarter_textdomain' ),
		'after'  => '</div>',
	]
);

tha_entry_content_after();
echo '</div>';

if ( be_has_action( 'tha_entry_bottom' ) ) {
	echo '<footer class="entry-footer">';
	tha_entry_bottom();
	echo '</footer>';
}

echo '</article>';
