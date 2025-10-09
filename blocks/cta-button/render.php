<?php
/**
 * CTA Button block
 *
 * @package      NWU2025
 * @subpackage   blocks/cta-button
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

// Get ACF fields
$button = get_field('button');
$text_color = get_field('text_color');
$background_color = get_field('background_color');

// Validate required fields
if (empty($button) || empty($button['url'])) {
    return;
}

// Text color classes
$text_class = 'light' === $text_color ? 'has-white-color' : 'has-black-color';

// Background color classes - map to theme.json slugs
$bg_class = 'has-' . esc_attr($background_color) . '-background-color';

// Block wrapper classes - background applies here
$block_classes = [
    'block-cta-button',
    'has-background',
    $bg_class
];

// Button classes - transparent background with border
$button_classes = [
    'cta-button',
    'wp-element-button',
    $text_class
];

// Target attribute
$target = !empty($button['target']) ? ' target="' . esc_attr($button['target']) . '"' : '';
$rel = false !== strpos($target, '_blank') ? ' rel="noopener noreferrer"' : '';

?>
<div class="<?php echo esc_attr(join(' ', $block_classes)); ?>">
    <div class="cta-button__wrapper">
        <div class="cta-button__decoration cta-button__decoration--left" aria-hidden="true">
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/NWU-Loop.png'); ?>" alt="">
        </div>

        <a href="<?php echo esc_url($button['url']); ?>"
           class="<?php echo esc_attr(join(' ', $button_classes)); ?>"
           <?php echo $target . $rel; ?>>
            <?php echo esc_html($button['title']); ?>
        </a>

        <div class="cta-button__decoration cta-button__decoration--right" aria-hidden="true">
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/NWU-Lines.png'); ?>" alt="">
        </div>
    </div>
</div>
