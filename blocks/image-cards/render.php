<?php
/**
 * Image Cards Block
 *
 * @package      NWU2025
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

$cards = get_field('cards');

if (empty($cards)) {
	return;
}

// Block wrapper attributes
$block_id = 'image-cards-' . $block['id'];
if (!empty($block['anchor'])) {
	$block_id = $block['anchor'];
}

$class_name = 'block-image-cards';
if (!empty($block['className'])) {
	$class_name .= ' ' . $block['className'];
}

?>

<div id="<?php echo esc_attr($block_id); ?>" class="<?php echo esc_attr($class_name); ?>">
	<div class="image-cards__grid">
		<?php foreach ($cards as $card) :
			$title = isset($card['title']) ? $card['title'] : '';
			$image_id = isset($card['image']) ? $card['image'] : '';
			$bg_color = isset($card['bg_color']) ? $card['bg_color'] : 'cyan';
			$text_color = isset($card['text_color']) ? $card['text_color'] : 'dark';
			$link = isset($card['link']) ? $card['link'] : '';

			// Build card classes
			$card_classes = ['image-card'];
			$card_classes[] = 'has-' . esc_attr($bg_color) . '-background-color';
			$card_classes[] = 'has-' . esc_attr($text_color) . '-text-color';

			// Determine if card should be a link
			$is_link = !empty($link['url']);
			$tag = $is_link ? 'a' : 'div';
			$link_attrs = '';

			if ($is_link) {
				$link_attrs .= ' href="' . esc_url($link['url']) . '"';
				if (!empty($link['target'])) {
					$link_attrs .= ' target="' . esc_attr($link['target']) . '"';
					if ($link['target'] === '_blank') {
						$link_attrs .= ' rel="noopener noreferrer"';
					}
				}
				if (!empty($link['title'])) {
					$link_attrs .= ' aria-label="' . esc_attr($link['title']) . '"';
				}
			}
		?>

		<<?php echo $tag; ?> class="<?php echo esc_attr(implode(' ', $card_classes)); ?>"<?php echo $link_attrs; ?>>
			<?php if ($image_id) : ?>
				<div class="image-card__image">
					<?php echo wp_get_attachment_image($image_id, 'large'); ?>
				</div>
			<?php endif; ?>

			<?php if ($title) : ?>
				<div class="image-card__content">
					<h3 class="image-card__title"><?php echo esc_html($title); ?></h3>
				</div>
			<?php endif; ?>
		</<?php echo $tag; ?>>

		<?php endforeach; ?>
	</div>
</div>
