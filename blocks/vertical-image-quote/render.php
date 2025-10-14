<?php
/**
 * Vertical Image Quote block
 *
 * @package      NWU2025
 * @subpackage   blocks/vertical-image-quote
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

// Get ACF fields
$quote = get_field('quote');
$attribution = get_field('attribution');
$image_id = get_field('image');
$image_caption = get_field('image_caption');
$show_badge = get_field('show_badge');

// Block attributes
$block_id = isset($block['anchor']) ? $block['anchor'] : 'vertical-image-quote-' . $block['id'];
$class_name = 'vertical-image-quote';
if (!empty($block['className'])) {
    $class_name .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $class_name .= ' align' . $block['align'];
}

// Background color support for the quote section
$bg_color_class = '';
if (!empty($block['backgroundColor'])) {
    $bg_color_class = 'has-' . $block['backgroundColor'] . '-background-color';
}
if (!empty($block['textColor'])) {
    $class_name .= ' has-' . $block['textColor'] . '-color has-text-color';
}
?>

<div id="<?php echo esc_attr($block_id); ?>" class="<?php echo esc_attr($class_name); ?>">
    <div class="vertical-image-quote__inner">

        <!-- Content Section (Top) -->
        <div class="vertical-image-quote__content <?php echo esc_attr($bg_color_class); ?>">
            <?php if ($show_badge) : ?>
                <div class="vertical-image-quote__badge">
                    <?php echo file_get_contents(get_template_directory() . '/assets/images/writers-union-badge.svg'); ?>
                </div>
            <?php endif; ?>

            <div class="vertical-image-quote__quote-icon">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/NWU-Quote-white.png'); ?>" alt="" aria-hidden="true">
            </div>

            <blockquote class="vertical-image-quote__quote">
                <?php if (!empty($quote)) : ?>
                    <?php echo wp_kses_post(wpautop($quote)); ?>
                <?php else : ?>
                    <p><em>Add your quote text here...</em></p>
                <?php endif; ?>
            </blockquote>

            <?php if (!empty($attribution)) : ?>
                <cite class="vertical-image-quote__attribution">
                    <?php echo esc_html($attribution); ?>
                </cite>
            <?php endif; ?>
        </div>

        <!-- Image Section (Bottom, extends down) -->
        <div class="vertical-image-quote__image-wrapper">
            <?php if ($image_id) : ?>
                <?php echo wp_get_attachment_image($image_id, 'large', false, ['class' => 'vertical-image-quote__image']); ?>

                <?php if (!empty($image_caption)) : ?>
                    <p class="vertical-image-quote__caption"><?php echo esc_html($image_caption); ?></p>
                <?php endif; ?>
            <?php else : ?>
                <div class="vertical-image-quote__image-placeholder">
                    <p>Select an image</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>
