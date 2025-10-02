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

		<!-- Optional: Who We Are / Divisions Section -->
		<section class="chapters-info">
			<div class="chapters-info__grid">

				<div class="chapters-info__section">
					<h2><?php esc_html_e( 'Who We Are', 'nwu-2025' ); ?></h2>
					<?php
					// This could come from an ACF Options field
					$who_we_are = get_field( 'chapters_who_we_are', 'option' );
					if ( $who_we_are ) :
						echo wp_kses_post( $who_we_are );
					else :
						?>
						<p>
							<?php esc_html_e( 'NWU members are located across the United States. Members are organized into Chapter structures to advocate for members, build power, and connect with other Chapters. Click on a location to learn more about a Chapter.', 'nwu-2025' ); ?>
						</p>
					<?php endif; ?>
				</div>

				<div class="chapters-info__section">
					<h2><?php esc_html_e( 'Divisions', 'nwu-2025' ); ?></h2>
					<?php
					// This could be a custom block or ACF field
					$divisions = get_field( 'chapters_divisions', 'option' );
					if ( $divisions ) :
						echo wp_kses_post( $divisions );
					else :
						?>
						<ul>
							<li><?php esc_html_e( 'Digital Media / Freelance Solidarity Project', 'nwu-2025' ); ?></li>
							<li><?php esc_html_e( 'Escritores en Español', 'nwu-2025' ); ?></li>
							<li><?php esc_html_e( 'Book Division', 'nwu-2025' ); ?></li>
							<li><?php esc_html_e( 'Literary Translators Organizing Committee', 'nwu-2025' ); ?></li>
						</ul>
					<?php endif; ?>
				</div>

			</div>
		</section>

		<!-- Custom Map Block Area -->
		<?php
		// Check if there's a custom block area for the chapters map
		if ( function_exists( 'NWU2025\Block_Areas\show' ) ) {
			NWU2025\Block_Areas\show( 'chapters-map' );
		}
		?>

	</div>
</div>

<?php get_footer(); ?>
