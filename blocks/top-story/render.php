<?php
/**
 * Top Story block
 *
 * @package      NWU2025
 * @subpackage   blocks/top-story
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

// Get ACF fields
$featured_post = get_field('featured_post');
$custom_excerpt = get_field('custom_excerpt');
$attribution = get_field('attribution');

// If no post selected, show placeholder in editor
if (empty($featured_post)) {
	if (is_admin()) {
		echo '<div class="block-top-story block-top-story--placeholder">';
		echo '<p>Please select a featured post in the block settings.</p>';
		echo '</div>';
	}
	return;
}

// Get post data
$post_title = get_the_title($featured_post->ID);
$post_url = get_permalink($featured_post->ID);

// Get excerpt - use custom if provided, otherwise use post excerpt
$excerpt = !empty($custom_excerpt) ? $custom_excerpt : get_the_excerpt($featured_post->ID);

// Build the block output
?>
<div class="block-top-story u-width-constrained">
	<div class="block-top-story__inner">
		<div class="block-top-story__header">
			<div class="block-top-story__icon">
				<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/NWU-Quote-white.png'); ?>" alt="" aria-hidden="true">
			</div>
			<div class="block-top-story__label">Top Story</div>
		</div>

		<h2 class="block-top-story__title">
			<a href="<?php echo esc_url($post_url); ?>">
				<?php echo esc_html($post_title); ?>
				<?php echo be_icon(array('icon' => 'chevron-large-right', 'size' => 24)); ?>
			</a>
		</h2>

		<?php if (!empty($excerpt)): ?>
			<div class="block-top-story__excerpt">
				<?php echo wp_kses_post(wpautop($excerpt)); ?>
			</div>
		<?php endif; ?>

		<?php if (!empty($attribution)): ?>
			<div class="block-top-story__attribution">
				<?php echo esc_html($attribution); ?>
			</div>
		<?php endif; ?>
	</div>
</div>
