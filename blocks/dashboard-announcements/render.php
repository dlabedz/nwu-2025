<?php
/**
 * Dashboard Announcements Block
 *
 * @package      NWU2025
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

$section_title = get_field( 'section_title' );
$announcements = get_field( 'announcements' );

// Use default title if none provided
if ( empty( $section_title ) ) {
	$section_title = __( 'Announcements & Opportunities', 'nwu-2025' );
}

// Check if there are any announcements
if ( empty( $announcements ) ) {
	echo '<div class="dashboard-announcements-block">';
	echo '<p class="dashboard-placeholder">' . esc_html__( 'Add announcements in the block settings.', 'nwu-2025' ) . '</p>';
	echo '</div>';
	return;
}
?>

<div class="dashboard-announcements-block">
	<div class="dashboard-announcements-block-inner">
		<h3><?php echo esc_html( $section_title ); ?></h3>

		<div class="announcements-list">
			<?php foreach ( $announcements as $announcement ) : ?>
				<article class="announcement-item">
					<?php if ( ! empty( $announcement['title'] ) ) : ?>
						<p class="announcement-title">
							<?php echo esc_html( $announcement['title'] ); ?>
						</p>
					<?php endif; ?>

					<?php if ( ! empty( $announcement['text'] ) ) : ?>
						<div class="announcement-text">
							<?php echo wp_kses_post( $announcement['text'] ); ?>
					</div>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</div>
