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

// Arrow SVG icons
$arrow_left = '<svg xmlns="http://www.w3.org/2000/svg" width="70" height="33" viewBox="0 0 70 33" fill="none"><path d="M2.86035 17.5059L2 16.7529L2.86035 16L18.8604 2L20.1768 3.50586L6.55469 15.4248L68.5615 15.4248L68.5615 17.4248L5.80469 17.4248L20.1768 30L18.8604 31.5059L2.86035 17.5059Z" fill="currentColor"/></svg>';
$arrow_right = '<svg xmlns="http://www.w3.org/2000/svg" width="70" height="33" viewBox="0 0 70 33" fill="none"><path d="M67.1396 17.5059L68 16.7529L67.1396 16L51.1396 2L49.8232 3.50586L63.4453 15.4248L1.43848 15.4248L1.43848 17.4248L64.1953 17.4248L49.8232 30L51.1396 31.5059L67.1396 17.5059Z" fill="currentColor"/></svg>';

// Generate unique ID for Swiper instance
$slider_id = 'swiper-' . uniqid();
?>

<div id="<?php echo esc_attr($block_id); ?>" class="<?php echo esc_attr(implode(' ', $classes)); ?>">
    <div class="card-slider__container">
        <?php if (!empty($slider_title)): ?>
            <div class="card-slider__title-wrapper">
                <?php if ($bleed === 'right'): ?>
                    <span class="card-slider__arrow card-slider__arrow--left"><?php echo $arrow_left; ?></span>
                <?php endif; ?>

                <h2 class="card-slider__main-title"><?php echo esc_html($slider_title); ?></h2>

                <?php if ($bleed === 'left'): ?>
                    <span class="card-slider__arrow card-slider__arrow--right"><?php echo $arrow_right; ?></span>
                <?php endif; ?>
            </div>
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
            watchSlidesProgress: true,
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 24,
                },
            },
            on: {
                init: function() {
                    equalizeSlideHeights(this);
                },
                resize: function() {
                    equalizeSlideHeights(this);
                }
            }
        });

        function equalizeSlideHeights(swiper) {
            const slides = swiper.slides;
            let maxHeight = 0;

            slides.forEach(slide => {
                slide.style.height = 'auto';
            });

            slides.forEach(slide => {
                const height = slide.offsetHeight;
                if (height > maxHeight) {
                    maxHeight = height;
                }
            });

            slides.forEach(slide => {
                slide.style.height = maxHeight + 'px';
            });
        }
    }
});
</script>
<?php endif; ?>
