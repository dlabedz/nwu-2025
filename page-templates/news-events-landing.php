<?php
/**
 * Template Name: News & Events Landing
 *
 * @package      NWU2025
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

// Full width layout
add_filter( 'be_page_layout', 'be_return_full_width_content' );

get_header();
?>

<div class="news-events-landing">
	<?php
	// This page uses blocks in the content editor
	// Hero, Featured Posts, Upcoming Events, etc. are all custom blocks

	while ( have_posts() ) :
		the_post();
		?>
		<article <?php post_class(); ?>>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		</article>
		<?php
	endwhile;
	?>
</div>

<?php get_footer(); ?>
