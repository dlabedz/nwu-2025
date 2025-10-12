<?php
/**
 * News & Events Feed block
 *
 * @package      NWU2025
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

namespace NWU2025\Blocks\News_Events_Feed;

// Get ACF fields
$heading = get_field('heading') ?: 'News & Events';
$view_all_link = get_field('view_all_link');

// Set default link if not set
if ( empty( $view_all_link ) ) {
	$view_all_link = array(
		'url' => get_post_type_archive_link('post'),
		'title' => 'View All News & Events',
		'target' => ''
	);
}

// Query latest 3 posts from both post types
$args = array(
	'post_type'      => array( 'post', 'event' ),
	'posts_per_page' => 3,
	'post_status'    => 'publish',
	'orderby'        => 'date',
	'order'          => 'DESC',
	'no_found_rows'  => true,
	'ignore_sticky_posts' => true,
);

$query = new \WP_Query($args);

if ( ! $query->have_posts() ) {
	wp_reset_postdata();
	return;
}

?>

<div class="block-news-events-feed">
	<div class="u-width-constrained">
	<div class="news-events-feed__header">
		<div class="news-events-feed__icon">
			<?php echo be_icon( array( 'icon' => 'menu', 'group' => 'utility', 'size' => 40 ) ); ?>
		</div>
		<h2 class="news-events-feed__heading"><?php echo esc_html( $heading ); ?></h2>
		<a href="<?php echo esc_url( $view_all_link['url'] ); ?>" class="news-events-feed__view-all"<?php echo ! empty( $view_all_link['target'] ) ? ' target="' . esc_attr( $view_all_link['target'] ) . '"' : ''; ?>>
			<?php echo esc_html( $view_all_link['title'] ); ?>
			<?php echo be_icon( array( 'icon' => 'chevron-large-right', 'group' => 'utility', 'size' => 20 ) ); ?>
		</a>
	</div>

	<div class="news-events-feed__items">
		<?php
		while ( $query->have_posts() ) :
			$query->the_post();

			// Get post data
			$post_id = get_the_ID();
			$post_date = get_the_date('F j, Y');
			$post_author = get_the_author();
			$post_title = get_the_title();
			$post_excerpt = get_the_excerpt();
			$post_url = get_permalink();

			// Get categories and tags
			$categories = get_the_category();
			$tags = get_the_tags();
			?>

			<article class="news-events-feed__item">
				<div class="news-events-feed__meta">
					<time class="news-events-feed__date" datetime="<?php echo esc_attr( get_the_date('c') ); ?>">
						<?php echo esc_html( $post_date ); ?>
					</time>
					<p class="news-events-feed__author">
						<?php echo esc_html( $post_author ); ?>
					</p>
				</div>

				<div class="news-events-feed__content">
					<h3 class="news-events-feed__title">
						<a href="<?php echo esc_url( $post_url ); ?>">
							<?php echo esc_html( $post_title ); ?>
							<?php echo be_icon( array( 'icon' => 'chevron-large-right', 'group' => 'utility', 'size' => 20, 'class' => 'news-events-feed__title-icon' ) ); ?>
						</a>
					</h3>

					<?php if ( ! empty( $post_excerpt ) ) : ?>
						<div class="news-events-feed__excerpt">
							<?php echo wp_kses_post( $post_excerpt ); ?>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $categories ) || ! empty( $tags ) ) : ?>
						<div class="news-events-feed__terms">
							<?php if ( ! empty( $categories ) ) : ?>
								<?php foreach ( $categories as $category ) : ?>
									<a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="news-events-feed__term news-events-feed__term--category">
										<?php echo esc_html( $category->name ); ?>
									</a>
								<?php endforeach; ?>
							<?php endif; ?>

							<?php if ( ! empty( $tags ) ) : ?>
								<?php foreach ( $tags as $tag ) : ?>
									<a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="news-events-feed__term news-events-feed__term--tag">
										<?php echo esc_html( $tag->name ); ?>
									</a>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</article>

		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
	</div>
	</div>
</div>
