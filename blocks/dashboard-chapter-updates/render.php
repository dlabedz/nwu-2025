<?php
/**
 * Dashboard Chapter Updates Block
 *
 * @package      NWU2025
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

$section_title    = get_field( 'chapter_updates_title' );
$chapter_updates  = get_field( 'chapter_updates' );

// Get current user's chapter
$user_id      = get_current_user_id();
$user_chapter = get_user_meta( $user_id, 'nwu_user_chapter', true );

// If empty, use defaults
if ( empty( $section_title ) ) {
	$section_title = __( 'My Chapter Updates', 'nwu-2025' );
}

// Determine the "More Updates" link
$more_updates_url = home_url( '/chapters' ); // Default to chapters archive
$more_updates_text = __( 'More Updates', 'nwu-2025' );

// If user has a chapter, link to their specific chapter
if ( ! empty( $user_chapter ) ) {
	$chapter_post = get_post( $user_chapter );
	if ( $chapter_post ) {
		$more_updates_url = get_permalink( $user_chapter );
	}
}
?>

<div class="block-dashboard-chapter-updates">

	<div class="chapter-updates-header">
		<h3><span><?php echo esc_html( $section_title ); ?></span></h3>

		<a href="<?php echo esc_url( $more_updates_url ); ?>" class="more-updates-link">
			<?php echo esc_html( $more_updates_text ); ?>
			<?php echo be_icon( array( 'icon' => 'chevron-large-right', 'size' => 16 ) ); ?>
		</a>
	</div>

	<?php if ( ! empty( $chapter_updates ) && is_array( $chapter_updates ) ) : ?>
		<div class="chapter-updates-list">
			<?php foreach ( $chapter_updates as $index => $update ) : ?>
				<div class="chapter-update-item<?php echo 0 !== $index ? ' has-separator' : ''; ?>">
					<?php if ( ! empty( $update['title'] ) ) : ?>
						<h3 class="chapter-update-title"><?php echo esc_html( $update['title'] ); ?></h3>
					<?php endif; ?>

					<?php if ( ! empty( $update['text'] ) ) : ?>
						<div class="chapter-update-text">
							<?php echo wp_kses_post( $update['text'] ); ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

</div>
