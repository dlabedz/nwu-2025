<?php
/**
 * Card Slider Block
 *
 * @package      NWU2025
 * @subpackage   blocks/card-slider
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

// Get block settings
$slider_title = get_field('slider_title');
$bleed = get_field('bleed_direction') ?: 'none';
$bg_style = get_field('background_style') ?: 'none';
$cards = get_field('cards');
$block_id = 'card-slider-' . $block['id'];

// Build classes
$classes = ['block-card-slider'];
$classes[] = 'bleed-' . $bleed;
$classes[] = 'bg-' . $bg_style;

// Add block align class if set
if (!empty($block['align'])) {
    $classes[] = 'align' . $block['align'];
}

// Add custom classes if set
if (!empty($block['className'])) {
    $classes[] = $block['className'];
}

// Early return if no cards
if (empty($cards)) {
    if (is_admin()) {
        echo '<div class="acf-block-preview"><p>Please add cards to the slider.</p></div>';
    }
    return;
}

// Color mapping
$color_map = [
    'jade' => '#0B616D',
    'yellow' => '#F4B438',
    'red' => '#D3012A',
    'violet' => '#5F3DC4',
    'cyan' => '#3DC5E0',
    'black' => '#0A0A0A',
    'lavender' => '#C0A9FF',
    'blue' => '#2F80ED'
];

$text_color_map = [
    'light' => '#FEFEFE',
    'dark' => '#0A0A0A'
];

// Generate unique ID for Swiper instance
$slider_id = 'swiper-' . uniqid();
?>

<div id="<?php echo esc_attr($block_id); ?>" class="<?php echo esc_attr(implode(' ', $classes)); ?>">
    <div class="card-slider__container">
        <?php if (!empty($slider_title)): ?>
            <h2 class="card-slider__main-title"><?php echo esc_html($slider_title); ?></h2>
        <?php endif; ?>

        <div class="<?php echo esc_attr($slider_id); ?> swiper">
            <div class="swiper-wrapper">
                <?php foreach ($cards as $card):
                    $bg_color = isset($card['bg_color']) && isset($color_map[$card['bg_color']])
                        ? $color_map[$card['bg_color']]
                        : $color_map['jade'];
                    $text_color = isset($card['text_color']) && isset($text_color_map[$card['text_color']])
                        ? $text_color_map[$card['text_color']]
                        : $text_color_map['light'];
                ?>
                    <div class="swiper-slide">
                        <div class="card-slider__card" style="background-color: <?php echo esc_attr($bg_color); ?>;">
                            <div class="card-slider__content">
                                <?php if (!empty($card['title'])): ?>
                                    <h3 class="card-slider__title" style="color: <?php echo esc_attr($text_color); ?>;">
                                        <?php echo esc_html($card['title']); ?>
                                    </h3>
                                <?php endif; ?>

                                <?php if (!empty($card['image'])): ?>
                                    <div class="card-slider__image">
                                        <?php echo wp_get_attachment_image($card['image'], 'medium'); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($card['description'])): ?>
                                    <p class="card-slider__description" style="color: <?php echo esc_attr($text_color); ?>;">
                                        <?php echo esc_html($card['description']); ?>
                                    </p>
                                <?php endif; ?>

                                <?php if (!empty($card['link']['url'])): ?>
                                    <a href="<?php echo esc_url($card['link']['url']); ?>"
                                       class="card-slider__link wp-element-button"
                                       style="background-color: <?php echo esc_attr($text_color); ?>; color: <?php echo esc_attr($bg_color); ?>; border-color: <?php echo esc_attr($text_color); ?>;"
                                       <?php if (!empty($card['link']['target'])): ?>
                                           target="<?php echo esc_attr($card['link']['target']); ?>"
                                           rel="noopener noreferrer"
                                       <?php endif; ?>>
                                        <?php echo esc_html($card['link']['title'] ?: 'Learn More'); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php if (!is_admin()): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Swiper !== 'undefined') {
        new Swiper('.<?php echo esc_js($slider_id); ?>', {
            slidesPerView: 1,
            spaceBetween: 16,
            loop: <?php echo count($cards) > 1 ? 'true' : 'false'; ?>,
            keyboard: {
                enabled: true,
                onlyInViewport: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 24,
                },
            }
        });
    }
});
</script>
<?php endif; ?>
