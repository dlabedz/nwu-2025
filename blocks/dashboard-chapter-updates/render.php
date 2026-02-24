<?php
/**
 * Dashboard Chapter Updates Block
 *
 * @package      NWU2025
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

// Get current user's chapter
$user_id      = get_current_user_id();
$user_chapter = get_user_meta( $user_id, 'nwu_user_chapter', true );

$section_title    = get_field( 'chapter_updates_title' );
$chapter_updates  = get_field( 'chapter_updates' );

// If empty, use defaults
if ( empty( $section_title ) ) {
	$section_title = __( 'My Chapter Updates', 'nwu-2025' );
}

// Filter updates based on user's chapter
$visible_updates = array();

if ( ! empty( $chapter_updates ) && is_array( $chapter_updates ) ) {
	foreach ( $chapter_updates as $update ) {
		$update_target = isset( $update['target_chapter'] ) ? $update['target_chapter'] : null;

		// Show update if:
		// 1. No target chapter (show to all)
		// 2. Target chapter matches user's chapter
		if ( empty( $update_target ) || $update_target == $user_chapter ) {
			$visible_updates[] = $update;
		}
	}
}

// Don't render block if no visible updates
if ( empty( $visible_updates ) ) {
	return;
}

// Determine the "More Updates" link
$more_updates_url = null;
$more_updates_text = __( 'More Updates', 'nwu-2025' );

// If user has a chapter, try to find chapter-tagged news
if ( ! empty( $user_chapter ) ) {
	$chapter_post = get_post( $user_chapter );

	if ( $chapter_post ) {
		// Get the chapter slug to look for matching tag/category
		$chapter_slug = $chapter_post->post_name;
		$chapter_name = $chapter_post->post_title;

		// Try to find a tag that matches the chapter
		$chapter_term = null;

		// Check custom taxonomy 'chapter_category'
		if ( taxonomy_exists( 'chapter_category' ) ) {
			$chapter_term = get_term_by( 'slug', $chapter_slug, 'chapter_category' );
		}

		// Check regular post tags
		if ( ! $chapter_term ) {
			$chapter_term = get_term_by( 'slug', $chapter_slug, 'post_tag' );
		}

		// Check regular categories
		if ( ! $chapter_term ) {
			$chapter_term = get_term_by( 'slug', $chapter_slug, 'category' );
		}

		// Try matching by name instead of slug
		if ( ! $chapter_term ) {
			$chapter_term = get_term_by( 'name', $chapter_name, 'post_tag' );
		}

		if ( ! $chapter_term ) {
			$chapter_term = get_term_by( 'name', $chapter_name, 'category' );
		}

		// If we found a matching term, link to that filtered archive
		if ( $chapter_term ) {
			$more_updates_url = get_term_link( $chapter_term );
		}
	}
}

// Fallback to news-events-archive page if no chapter tag found
if ( ! $more_updates_url ) {
	$more_updates_url = home_url( '/news-events-archive' );
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

	<div class="chapter-updates-list">
		<?php foreach ( $visible_updates as $index => $update ) : ?>
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

	<p class="chapter-link">
		<a href="<?php echo esc_url( home_url( '/chapters' ) ); ?>">
			<?php esc_html_e( 'View All Chapters →', 'nwu-2025' ); ?>
		</a>
	</p>

</div>
