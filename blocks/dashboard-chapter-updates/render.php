<?php
/**
 * Dashboard Chapter Updates Block
 *
 * @package      NWU2025
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

$section_title = get_field( 'chapter_updates_title' );
$content       = get_field( 'chapter_updates_content' );

// Get current user's chapter
$user_id      = get_current_user_id();
$user_chapter = get_user_meta( $user_id, 'nwu_user_chapter', true );

// If empty, use defaults
if ( empty( $section_title ) ) {
	$section_title = __( 'My Chapter Updates', 'nwu-2025' );
}
?>

<div class="block-dashboard-chapter-updates">
	<h2><?php echo esc_html( $section_title ); ?></h2>

	<?php if ( ! empty( $content ) ) : ?>
		<div class="chapter-updates-content">
			<?php echo wp_kses_post( $content ); ?>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $user_chapter ) ) : ?>
		<?php
		$chapter_post = get_post( $user_chapter );
		if ( $chapter_post ) :
			?>
			<p class="chapter-link">
				<a href="<?php echo esc_url( get_permalink( $user_chapter ) ); ?>">
					<?php
					printf(
						/* translators: %s: Chapter name */
						esc_html__( 'Visit %s Chapter Page →', 'nwu-2025' ),
						esc_html( $chapter_post->post_title )
					);
					?>
				</a>
			</p>
		<?php endif; ?>
	<?php else : ?>
		<p class="no-chapter-assigned">
			<?php esc_html_e( 'No chapter assigned. Contact support to join a chapter.', 'nwu-2025' ); ?>
		</p>
	<?php endif; ?>
</div>
