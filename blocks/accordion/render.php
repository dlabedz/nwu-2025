<?php
/**
 * Accordion block
 *
 * @package      NWU2025
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

// Get accordion items
$accordion_items = get_field( 'accordion_items' );

// If no items in editor, show placeholder
if ( empty( $accordion_items ) ) {
	echo '<div class="acf-block-placeholder">';
	echo '<p><strong>Accordion Block</strong></p>';
	echo '<p>Click to add accordion items.</p>';
	echo '</div>';
	return;
}

// Generate unique ID for this accordion
$block_id = 'accordion-' . uniqid();

// Get block anchor if set
$anchor = '';
if ( ! empty( $block['anchor'] ) ) {
	$anchor = ' id="' . esc_attr( $block['anchor'] ) . '"';
}

echo '<div class="block-accordion"' . $anchor . '>';

foreach ( $accordion_items as $index => $item ) {
	$item_id = $block_id . '-item-' . $index;
	$is_open = ! empty( $item['open_default'] );
	$active_class = $is_open ? ' active' : '';

	echo '<div class="accordion-item' . esc_attr( $active_class ) . '">';

	// Question (button)
	echo '<button class="accordion-question" aria-expanded="' . ( $is_open ? 'true' : 'false' ) . '" aria-controls="' . esc_attr( $item_id ) . '">';
	echo '<h3 class="accordion-question__text">' . esc_html( $item['question'] ) . '</h3>';
	echo '<span class="accordion-icon" aria-hidden="true">';
	echo be_icon( [ 'icon' => 'carat-down-large', 'size' => 24 ] );
	echo '</span>';
	echo '</button>';

	// Answer (content)
	echo '<div class="accordion-answer" id="' . esc_attr( $item_id ) . '" style="' . ( $is_open ? '' : 'display: none;' ) . '">';
	echo '<div class="accordion-answer__content">';
	echo wp_kses_post( $item['answer'] );
	echo '</div>';
	echo '</div>';

	echo '</div>'; // .accordion-item
}

echo '</div>'; // .block-accordion
