<?php
/**
 * News Grid Block
 *
 * @package      NWU2025
 * @subpackage   blocks/news-grid
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

$number_of_posts = get_field('number_of_posts') ?: 3;
$show_tags = get_field('show_tags');
$columns = get_field('layout') ?: 3;

// Query news posts
$args = [
    'post_type' => 'post',
    'posts_per_page' => $number_of_posts,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC'
];

$news_query = new WP_Query($args);

$classes = [
    'block-news-grid',
    'block-news-grid--columns-' . $columns
];
?>

<div class="<?php echo esc_attr(implode(' ', $classes)); ?>">

    <div class="block-news-grid__header">
        <h2 class="block-news-grid__title">Featured News</h2>
        <a href="<?php echo esc_url(get_post_type_archive_link('post')); ?>" class="block-news-grid__view-all">
            View All Posts
        </a>
    </div>

    <?php if ($news_query->have_posts()): ?>
        <div class="block-news-grid__posts">
            <?php
            $post_count = 0;
            while ($news_query->have_posts() && $post_count < $number_of_posts):
                $news_query->the_post();
                $post_count++;
                $image_id = get_post_thumbnail_id();
            ?>
                <article class="news-grid-item">
                    <?php if ($image_id): ?>
                        <div class="news-grid-item__image">
                            <a href="<?php the_permalink(); ?>">
                                <?php echo wp_get_attachment_image($image_id, 'medium'); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="news-grid-item__content">
                        <h3 class="news-grid-item__title">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h3>

                        <div class="news-grid-item__meta">
                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                <?php echo esc_html(get_the_date('F j, Y')); ?>
                            </time>
                        </div>
                    </div>
                </article>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    <?php else: ?>
        <p>No news posts found.</p>
    <?php endif; ?>

    <?php if ($show_tags):
        $tags = get_tags(['hide_empty' => true, 'number' => 10]);
        if ($tags):
    ?>
        <div class="block-news-grid__tags">
            <?php foreach ($tags as $tag): ?>
                <a href="<?php echo esc_url(get_term_link($tag)); ?>" class="tag-button">
                    <?php echo esc_html($tag->name); ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php
        endif;
    endif;
    ?>
</div>
