<?php
/**
 * Upcoming Events Block
 *
 * @package      NWU2025
 * @subpackage   blocks/upcoming-events
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

$number_of_events = get_field('number_of_events') ?: 5;
$show_past_events = get_field('show_past_events');
$title = get_field('title') ?: 'Upcoming Events';

// Query events
$args = [
    'post_type' => 'event',
    'posts_per_page' => $number_of_events,
    'post_status' => 'publish',
    'orderby' => 'meta_value',
    'order' => 'ASC',
    'meta_key' => 'event_date'
];

if (!$show_past_events) {
    $args['meta_query'] = [
        [
            'key' => 'event_date',
            'value' => date('Y-m-d'),
            'compare' => '>=',
            'type' => 'DATE'
        ]
    ];
}

$events_query = new WP_Query($args);
?>

<div class="block-upcoming-events">
    <h3 class="block-upcoming-events__title"><?php echo esc_html($title); ?></h3>

    <?php if ($events_query->have_posts()): ?>
        <div class="block-upcoming-events__list">
            <?php while ($events_query->have_posts()): $events_query->the_post();
                $event_date = get_field('event_date');
                $event_time = get_field('event_time');
                $event_location = get_field('event_location');
            ?>
                <article class="upcoming-event-item">
                    <?php if ($event_date): ?>
                        <div class="upcoming-event-item__date">
                            <span class="date-month"><?php echo esc_html(date('M', strtotime($event_date))); ?></span>
                            <span class="date-day"><?php echo esc_html(date('j', strtotime($event_date))); ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="upcoming-event-item__content">
                        <h4 class="upcoming-event-item__title">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h4>

                        <?php if ($event_time): ?>
                            <div class="upcoming-event-item__time">
                                <?php echo esc_html($event_time); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($event_location): ?>
                            <div class="upcoming-event-item__location">
                                <?php echo esc_html($event_location); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>

        <a href="<?php echo esc_url(get_post_type_archive_link('event')); ?>" class="block-upcoming-events__view-all">
            View All Events →
        </a>
    <?php else: ?>
        <p class="block-upcoming-events__empty">No upcoming events.</p>
    <?php endif; ?>
</div>
