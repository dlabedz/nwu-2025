<?php
/**
 * Locations Map block
 *
 * @package      NWU2025
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

$background_color = get_field('background_color') ?: 'white';
$col1_title = get_field('column_1_title');
$col1_content = get_field('column_1_content');
$col2_title = get_field('column_2_title');
$col2_content = get_field('column_2_content');
$map_embed = get_field('map_embed_code');

// Check if both columns have content
$col1_has_content = ! empty( $col1_title ) || ! empty( $col1_content );
$col2_has_content = ! empty( $col2_title ) || ! empty( $col2_content );
$both_columns = $col1_has_content && $col2_has_content;

// Block classes
$classes = ['block-locations-map'];
$classes[] = 'bg-' . $background_color;

// Add anchor ID if set
$anchor = '';
if ( ! empty( $block['anchor'] ) ) {
	$anchor = 'id="' . esc_attr( $block['anchor'] ) . '"';
}

// Container class based on background
$container_class = $background_color === 'light-blue' ? 'full-width-container' : 'content-container';

// Column wrapper class based on number of columns
$columns_class = $both_columns ? 'locations-map__columns--two' : 'locations-map__columns--single';
?>

<div <?php echo $anchor; ?> class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">

	<?php if ( $col1_has_content || $col2_has_content ) : ?>
	<div class="<?php echo esc_attr( $container_class ); ?>">
		<div class="locations-map__columns <?php echo esc_attr( $columns_class ); ?>">
			<?php if ( $col1_has_content ) : ?>
			<div class="locations-map__column">
				<?php if ( $col1_title ) : ?>
					<h3 class="locations-map__title"><?php echo esc_html( $col1_title ); ?></h3>
				<?php endif; ?>

				<?php if ( $col1_content ) : ?>
					<div class="locations-map__content">
						<?php echo wp_kses_post( $col1_content ); ?>
					</div>
				<?php endif; ?>
			</div>
			<?php endif; ?>

			<?php if ( $col2_has_content ) : ?>
			<div class="locations-map__column">
				<?php if ( $col2_title ) : ?>
					<h3 class="locations-map__title"><?php echo esc_html( $col2_title ); ?></h3>
				<?php endif; ?>

				<?php if ( $col2_content ) : ?>
					<div class="locations-map__content">
						<?php echo wp_kses_post( $col2_content ); ?>
					</div>
				<?php endif; ?>
			</div>
			<?php endif; ?>
		</div>
	</div>
	<?php endif; ?>

	<?php if ( $map_embed ) : ?>
	<div class="locations-map__embed">
		<div class="locations-map__embed-wrapper">

			<!-- Mobile scroll trap overlay -->
			<div class="locations-map__scroll-overlay" aria-hidden="true">
				<div class="locations-map__scroll-hint">
					<span><?php esc_html_e( 'Tap to interact with map', 'nwu-2025' ); ?></span>
				</div>
			</div>

			<?php
			// Allow Flourish embed code with specific tags/attributes
			$allowed_html = array(
				'div' => array(
					'class'       => array(),
					'data-src'    => array(),
					'data-width'  => array(),
					'data-height' => array(),
				),
				'script' => array(
					'src'   => array(),
					'async' => array(),
				),
				'noscript' => array(),
				'img' => array(
					'src'    => array(),
					'width'  => array(),
					'height' => array(),
					'alt'    => array(),
				),
			);
			echo wp_kses( $map_embed, $allowed_html );
			?>

		</div>
	</div>
	<?php endif; ?>

</div>
