<?php
/**
 * Chapters Archive
 *
 * @package      NWU2025
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

// Full width layout
add_filter( 'be_page_layout', 'be_return_full_width_content' );

get_header();

// Get all chapters
$args = [
	'post_type'      => 'chapter',
	'posts_per_page' => -1, // Get all chapters
	'orderby'        => 'title',
	'order'          => 'ASC',
	'post_status'    => 'publish',
];

$chapters = new WP_Query( $args );
?>

<div class="archive-chapters">
	<div class="wrap">

		<header class="archive-header">
			<h1 class="archive-title"><?php esc_html_e( 'Chapters', 'nwu-2025' ); ?></h1>
		</header>

		<!-- Chapters Grid -->
		<main class="chapters-grid" role="main">
			<?php if ( $chapters->have_posts() ) : ?>

				<?php while ( $chapters->have_posts() ) : $chapters->the_post(); ?>

					<?php
					// Get chapter fields
					$chapter_id = get_the_ID();
					$cities = get_field( 'cities' ); // Could be a text field or repeater
					?>

					<article class="chapter-card">

						<h2 class="chapter-card__title">
							<a href="<?php the_permalink(); ?>">
								<?php the_title(); ?>
							</a>
						</h2>

						<?php if ( has_post_thumbnail() ) : ?>
							<a href="<?php the_permalink(); ?>" class="chapter-card__image">
								<?php the_post_thumbnail( 'medium_large' ); ?>
							</a>
						<?php else : ?>
							<a href="<?php the_permalink(); ?>" class="chapter-card__image chapter-card__image--placeholder">
								<div class="chapter-placeholder">
									<?php echo be_icon( [ 'icon' => 'groups', 'size' => 80 ] ); ?>
								</div>
							</a>
						<?php endif; ?>

						<?php if ( $cities ) : ?>
							<div class="chapter-card__cities">
								<?php echo esc_html( $cities ); ?>
							</div>
						<?php endif; ?>

					</article>

				<?php endwhile; ?>

			<?php else : ?>
				<p class="no-results"><?php esc_html_e( 'No chapters found.', 'nwu-2025' ); ?></p>
			<?php endif; ?>

			<?php wp_reset_postdata(); ?>
		</main>

	</div><!-- .wrap -->
</div><!-- .archive-chapters -->

<!-- Custom Map Block Area (outside wrap for full-width capability) -->
<?php
if ( function_exists( 'NWU2025\Block_Areas\show' ) ) {
	NWU2025\Block_Areas\show( 'chapters-map' );
}
?>

<?php get_footer(); ?>
