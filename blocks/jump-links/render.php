<?php
/**
 * Jump Links block
 */

// Safely check if we're in preview mode
$is_preview_mode = false;
if (isset($is_preview) && $is_preview) {
	$is_preview_mode = true;
}
if (isset($block['data']['is_preview']) && $block['data']['is_preview']) {
	$is_preview_mode = true;
}

// Always show placeholder in editor/preview
if ($is_preview_mode || is_admin()) {
	?>
	<div class="block-jump-links block-jump-links--preview">
		<p><strong>Jump Links</strong></p>
		<p><em>Links to all headings will appear on the front end.</em></p>
	</div>
	<?php
	return;
}

// Front-end only code below
global $post;

// Exit early if no post
if (!isset($post) || empty($post) || empty($post->post_content)) {
	return;
}

try {
	// Parse blocks to find all heading blocks
	$content_blocks = parse_blocks($post->post_content);

	if (!is_array($content_blocks)) {
		return;
	}

	$headings = array();

	// Function to find headings recursively
	if (!function_exists('nwu_jump_links_find_headings')) {
		function nwu_jump_links_find_headings($blocks, &$headings) {
			if (!is_array($blocks)) {
				return;
			}

			foreach ($blocks as $block) {
				if (!is_array($block)) {
					continue;
				}

				// Check if this is a heading block
				if (isset($block['blockName']) && $block['blockName'] === 'core/heading') {
					if (isset($block['attrs']['level']) && !empty($block['attrs']['level'])) {

						$content = isset($block['innerHTML']) ? $block['innerHTML'] : '';
						$text = wp_strip_all_tags($content);

						if (!empty($text)) {
							$slug = sanitize_title($text);
							$anchor = isset($block['attrs']['anchor']) ? $block['attrs']['anchor'] : $slug;

							$headings[] = array(
								'text' => $text,
								'anchor' => $anchor
							);
						}
					}
				}

				// Check inner blocks recursively
				if (isset($block['innerBlocks']) && is_array($block['innerBlocks']) && !empty($block['innerBlocks'])) {
					nwu_jump_links_find_headings($block['innerBlocks'], $headings);
				}
			}
		}
	}

	// Find all headings
	nwu_jump_links_find_headings($content_blocks, $headings);

	// Don't render if no headings found
	if (empty($headings)) {
		return;
	}

	?>
	<nav class="block-jump-links" aria-label="Page sections">
		<ul class="block-jump-links__list">
			<?php foreach ($headings as $heading) : ?>
				<li class="block-jump-links__item">
					<a href="#<?php echo esc_attr($heading['anchor']); ?>" class="block-jump-links__link">
						<?php echo esc_html($heading['text']); ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</nav>
	<?php

} catch (Exception $e) {
	// Silently fail if there's an error
	error_log('Jump Links block error: ' . $e->getMessage());
	return;
}
