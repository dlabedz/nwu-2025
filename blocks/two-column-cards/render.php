<?php
/**
 * Two Column Cards block
 *
 * @package      NWU2025
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

$cards = get_field('cards');

// Block wrapper classes
$classes = ['block-two-column-cards'];
if (!empty($block['align'])) {
	$classes[] = 'align' . $block['align'];
}
if (!empty($block['className'])) {
	$classes[] = $block['className'];
}

// Color mappings
$color_map = [
	'cyan' => '#3DC5E0',
	'red' => '#D3012A',
	'yellow' => '#F4B438',
	'lavender' => '#C0A9FF',
	'navy' => '#022645',
	'blue' => '#054296',
	'jade' => '#0B616D',
	'violet' => '#5F3DC4',
	'newsprint' => '#DBD6D2',
	'cerulean' => '#3A7BE4',
];

$text_color_map = [
	'light' => '#FEFEFE',
	'dark' => '#0A0A0A',
];
?>

<div class="<?php echo esc_attr(implode(' ', $classes)); ?>">
	<div class="two-column-cards__grid">
		<?php if (!empty($cards)) : ?>
			<?php foreach ($cards as $card) :
				$title = isset($card['card_title']) ? $card['card_title'] : '';
				$text = isset($card['card_text']) ? $card['card_text'] : '';
				$link = isset($card['card_link']) ? $card['card_link'] : null;
				$bg_color = isset($card['background_color']) ? $card['background_color'] : 'cyan';
				$text_color = isset($card['text_color']) ? $card['text_color'] : 'dark';

				// Get actual color values
				$bg_color_value = isset($color_map[$bg_color]) ? $color_map[$bg_color] : $color_map['cyan'];
				$text_color_value = isset($text_color_map[$text_color]) ? $text_color_map[$text_color] : $text_color_map['dark'];

				// Determine button classes based on text color
				$button_class = ($text_color === 'light') ? 'button-light' : 'button-dark';
			?>
				<div class="two-column-cards__card"
					 style="background-color: <?php echo esc_attr($bg_color_value); ?>; color: <?php echo esc_attr($text_color_value); ?>;">

					<?php if (!empty($title)) : ?>
						<h3 class="two-column-cards__title"><?php echo esc_html($title); ?></h3>
					<?php endif; ?>

					<?php if (!empty($text)) : ?>
						<p class="two-column-cards__text"><?php echo esc_html($text); ?></p>
					<?php endif; ?>

					<?php if (!empty($link) && !empty($link['url'])) : ?>
						<div class="two-column-cards__button-wrapper">
							<a href="<?php echo esc_url($link['url']); ?>"
							   class="two-column-cards__button <?php echo esc_attr($button_class); ?>"
							   <?php echo !empty($link['target']) ? 'target="' . esc_attr($link['target']) . '"' : ''; ?>
							   <?php echo !empty($link['target']) && $link['target'] === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>
								<?php echo esc_html($link['title']); ?>
							</a>
						</div>
					<?php endif; ?>

				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</div>
