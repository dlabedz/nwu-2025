<?php
/**
 * Featured News Grid Block
 *
 * @package      NWU2025
 * @subpackage   blocks/featured-news-grid
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

$featured_posts = get_field('featured_posts');

if (!$featured_posts || empty($featured_posts)) {
    echo '<div class="block-featured-news-grid block-featured-news-grid--empty">Select posts to feature</div>';
    return;
}
?>

<div class="block-featured-news-grid">
    <div class="block-featured-news-grid__inner">
        <?php foreach ($featured_posts as $post):
            setup_postdata($post);
            $post_id = $post->ID;
            $categories = get_the_terms($post_id, 'category');
            $tags = get_the_terms($post_id, 'post_tag');
            $image_id = get_post_thumbnail_id($post_id);
            $excerpt = get_the_excerpt($post_id);
            $author_id = get_post_field('post_author', $post_id);
            $author_name = get_the_author_meta('display_name', $author_id);
            $post_date = get_the_date('F j, Y', $post_id);
        ?>
            <article class="featured-news-item">
                <div class="featured-news-item__left-column">
                    <div class="featured-news-item__date">
                        <time datetime="<?php echo esc_attr(get_the_date('c', $post_id)); ?>">
                            <?php echo esc_html($post_date); ?>
                        </time>
                    </div>

                    <div class="featured-news-item__author">
                        <?php echo esc_html($author_name); ?>
                    </div>

                    <?php if ($image_id): ?>
                        <div class="featured-news-item__image">
                            <a href="<?php echo esc_url(get_permalink($post_id)); ?>">
                                <?php echo wp_get_attachment_image($image_id, 'medium'); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="featured-news-item__right-column">
                    <h3 class="featured-news-item__title">
                        <a href="<?php echo esc_url(get_permalink($post_id)); ?>">
                            <?php echo esc_html(get_the_title($post_id)); ?>
                        </a>
                    </h3>

                    <?php if ($excerpt): ?>
                        <div class="featured-news-item__excerpt">
                            <?php echo wp_kses_post($excerpt); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (($categories && !is_wp_error($categories)) || ($tags && !is_wp_error($tags))): ?>
                        <div class="featured-news-item__taxonomy">
                            <?php if ($categories && !is_wp_error($categories)): ?>
                                <div class="featured-news-item__categories">
                                    <?php foreach ($categories as $category):
                                        $category_link = get_term_link($category);
                                    ?>
                                        <a href="<?php echo esc_url($category_link); ?>" class="taxonomy-term category-term">
                                            <?php echo esc_html($category->name); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($tags && !is_wp_error($tags)): ?>
                                <div class="featured-news-item__tags">
                                    <?php foreach ($tags as $tag):
                                        $tag_link = get_term_link($tag);
                                    ?>
                                        <a href="<?php echo esc_url($tag_link); ?>" class="taxonomy-term tag-term">
                                            <?php echo esc_html($tag->name); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; wp_reset_postdata(); ?>
    </div>
</div>
