<?php
/**
 * Quote & Featured News Block
 *
 * @package      NWU2025
 * @subpackage   blocks/quote-featured-news
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

$quote_text = get_field('quote_text');
$attribution = get_field('attribution');
$featured_post = get_field('featured_post');

if (!$quote_text && !$featured_post) {
    echo '<div class="block-quote-featured-news block-quote-featured-news--empty">Add a quote and select a featured post</div>';
    return;
}
?>

<div class="block-quote-featured-news">
    <div class="block-quote-featured-news__inner">

        <!-- Quote Column (60%) -->
        <div class="quote-column">
            <?php if ($quote_text): ?>
                <div class="quote-column__icon">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/NWU-Quote-white.png'); ?>" alt="" aria-hidden="true">
                </div>

                <blockquote class="quote-column__text">
                    <?php echo wp_kses_post($quote_text); ?>
                </blockquote>

                <?php if ($attribution): ?>
                    <cite class="quote-column__attribution">
                        <?php echo esc_html($attribution); ?>
                    </cite>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- Featured Post Column (40%) -->
        <?php if ($featured_post):
            $post_id = $featured_post->ID;
            $post_title = get_the_title($post_id);
            $post_permalink = get_permalink($post_id);
            $post_excerpt = get_the_excerpt($post_id);
            $post_date = get_the_date('F j, Y', $post_id);
            $author_id = get_post_field('post_author', $post_id);
            $author_name = get_the_author_meta('display_name', $author_id);
            $categories = get_the_terms($post_id, 'category');
            $tags = get_the_terms($post_id, 'post_tag');
        ?>
            <div class="featured-post-column">
                <div class="featured-post-column__date">
                    <time datetime="<?php echo esc_attr(get_the_date('c', $post_id)); ?>">
                        <?php echo esc_html($post_date); ?>
                    </time>
                </div>

                <div class="featured-post-column__author">
                    By <?php echo esc_html($author_name); ?>
                </div>

                <h3 class="featured-post-column__title">
                    <a href="<?php echo esc_url($post_permalink); ?>">
                        <?php echo esc_html($post_title); ?>
                    </a>
					<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/white-arrow.svg'); ?>" alt="" aria-hidden="true">
                </h3>

                <?php if ($post_excerpt): ?>
                    <div class="featured-post-column__excerpt">
                        <?php echo wp_kses_post($post_excerpt); ?>
                    </div>
                <?php endif; ?>

                <?php if (($categories && !is_wp_error($categories)) || ($tags && !is_wp_error($tags))): ?>
                    <div class="featured-post-column__taxonomy">
                        <?php if ($categories && !is_wp_error($categories)): ?>
                            <div class="taxonomy-group">
                                <?php foreach ($categories as $category):
                                    $category_link = get_term_link($category);
                                ?>
                                    <a href="<?php echo esc_url($category_link); ?>" class="taxonomy-term">
                                        <?php echo esc_html($category->name); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($tags && !is_wp_error($tags)): ?>
                            <div class="taxonomy-group">
                                <?php foreach ($tags as $tag):
                                    $tag_link = get_term_link($tag);
                                ?>
                                    <a href="<?php echo esc_url($tag_link); ?>" class="taxonomy-term is-style-pill">
                                        <?php echo esc_html($tag->name); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</div>
