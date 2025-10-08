<?php
/**
 * Image Quote block
 *
 * @package      NWU2025
 * @subpackage   blocks/image-quote
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

// Get ACF fields
$quote = get_field('quote');
$attribution = get_field('attribution');
$image_id = get_field('image');
$image_caption = get_field('image_caption');

// Exit if required fields are missing
if ( empty( $quote ) || empty( $image_id ) ) {
	return;
}

// Build block classes
$classes = ['block-image-quote'];

// Add alignment class
if ( ! empty( $block['align'] ) ) {
	$classes[] = 'align' . $block['align'];
}

// Add custom background color class
if ( ! empty( $block['backgroundColor'] ) ) {
	$classes[] = 'has-' . $block['backgroundColor'] . '-background-color';
	$classes[] = 'has-background';
}

// Add custom text color class
if ( ! empty( $block['textColor'] ) ) {
	$classes[] = 'has-' . $block['textColor'] . '-color';
	$classes[] = 'has-text-color';
}

// Add anchor ID if provided
$anchor = ! empty( $block['anchor'] ) ? 'id="' . esc_attr( $block['anchor'] ) . '"' : '';

?>

<div <?php echo $anchor; ?> class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
	<div class="block-image-quote__inner">

		<div class="block-image-quote__content">
			<div class="block-image-quote__icon">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/NWU-Quote 1.png' ); ?>" alt="Quote icon" />
			</div>

			<blockquote class="block-image-quote__quote">
				<?php echo wp_kses_post( wpautop( $quote ) ); ?>
			</blockquote>

			<?php if ( ! empty( $attribution ) ) : ?>
				<cite class="block-image-quote__attribution">
					<?php echo esc_html( $attribution ); ?>
				</cite>
			<?php endif; ?>
		</div>

		<figure class="block-image-quote__figure">
			<?php echo wp_get_attachment_image( $image_id, 'large', false, ['class' => 'block-image-quote__image'] ); ?>

			<?php if ( ! empty( $image_caption ) ) : ?>
				<figcaption class="block-image-quote__caption">
					<?php echo esc_html( $image_caption ); ?>
				</figcaption>
			<?php endif; ?>
		</figure>

	</div>
</div>
