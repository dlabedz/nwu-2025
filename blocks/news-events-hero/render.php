<?php
/**
 * News and Events Hero Block
 *
 * @package      NWU2025
 * @subpackage   blocks/news-events-hero
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

$featured_post = get_field('featured_post');
$featured_event = get_field('featured_event');
$bg_color = get_field('background_color') ?: 'navy';
$text_color = get_field('text_color') ?: 'light';

if (!$featured_post) {
    echo '<div class="block-news-events-hero block-news-events-hero--empty">Select a post to feature</div>';
    return;
}

// Get featured post data
$post_id = $featured_post->ID;
$post_title = get_the_title($post_id);
$post_excerpt = get_the_excerpt($post_id);
$post_permalink = get_permalink($post_id);

// Get custom image or use post thumbnail
$custom_image = get_field('custom_image');
$image_id = $custom_image ?: get_post_thumbnail_id($post_id);
$image_caption = get_field('image_caption');

$classes = [
    'block-news-events-hero',
	'',
    'has-' . $bg_color . '-background-color',
    'has-' . $text_color . '-text'
];

if ($featured_event) {
    $classes[] = 'has-event';
}

if ($image_id) {
    $classes[] = 'has-image';
}
?>

<div class="<?php echo esc_attr(implode(' ', $classes)); ?>">
    <div class="block-news-events-hero__inner">

        <!-- Featured Post Section -->
        <div class="hero-featured-post">
            <div class="hero-featured-post__label">Pinned Post</div>

            <h1 class="hero-featured-post__title">
                <?php echo esc_html($post_title); ?>
            </h1>

            <?php if ($post_excerpt): ?>
                <div class="hero-featured-post__excerpt">
                    <?php echo wp_kses_post($post_excerpt); ?>
                </div>
            <?php endif; ?>

            <a href="<?php echo esc_url($post_permalink); ?>" class="hero-featured-post__button wp-element-button">
                Continue Reading
            </a>

            <?php if ($image_id): ?>
                <div class="hero-featured-post__image">
                    <?php echo wp_get_attachment_image($image_id, 'large'); ?>
                    <?php if ($image_caption): ?>
                        <div class="hero-featured-post__caption">
                            <?php echo esc_html($image_caption); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Featured Event Section (Optional) -->
        <?php if ($featured_event):
            $event_id = $featured_event->ID;
            $event_title = get_the_title($event_id);
            $event_permalink = get_permalink($event_id);
            $event_date = get_field('event_date', $event_id);
            $event_time = get_field('event_time', $event_id);
            $event_location = get_field('event_location', $event_id);
        ?>
            <div class="hero-featured-event">
                <div class="hero-featured-event__label">Next Event</div>

                <h2 class="hero-featured-event__title">
                    <?php echo esc_html($event_title); ?>
                </h2>

                <div class="hero-featured-event__details">
                    <?php if ($event_date): ?>
                        <div class="hero-featured-event__date">
                            <strong>Date:</strong> <?php echo esc_html(date('F j, Y', strtotime($event_date))); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($event_time): ?>
                        <div class="hero-featured-event__time">
                            <strong>Time:</strong> <?php echo esc_html($event_time); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($event_location): ?>
                        <div class="hero-featured-event__location">
                            <strong>Location:</strong> <?php echo esc_html($event_location); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <a href="<?php echo esc_url($event_permalink); ?>" class="hero-featured-event__button wp-element-button">
                    Event Information
                </a>
            </div>
        <?php endif; ?>

    </div>
</div>
