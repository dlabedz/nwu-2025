<?php
/**
 * Jump Links block initialization
 */

namespace NWU2025\Blocks\Jump_Links;

/**
 * Automatically add anchors to all heading blocks when rendering
 */
function add_heading_anchors($block_content, $block) {
	// Safety check
	if (!isset($block['blockName'])) {
		return $block_content;
	}

	// Only process heading blocks
	if ($block['blockName'] !== 'core/heading') {
		return $block_content;
	}

	// Skip if anchor already exists
	if (!empty($block['attrs']['anchor'])) {
		return $block_content;
	}

	// Get the heading text
	$text = wp_strip_all_tags($block_content);
	if (empty($text)) {
		return $block_content;
	}

	// Create anchor slug
	$anchor = sanitize_title($text);

	// Get the heading level
	$level = isset($block['attrs']['level']) ? intval($block['attrs']['level']) : 2;

	// Add the ID attribute to the heading
	// Look for the opening tag with or without existing attributes
	$pattern = '/<h' . $level . '(\s+[^>]*)?>/i';
	$replacement = '<h' . $level . '$1 id="' . esc_attr($anchor) . '">';
	$block_content = preg_replace($pattern, $replacement, $block_content, 1);

	return $block_content;
}
// Use priority 5 to run earlier
add_filter('render_block', __NAMESPACE__ . '\\add_heading_anchors', 5, 2);
