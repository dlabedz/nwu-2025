<?php
/**
 * Archive - News & Events
 *
 * @package      NWU2025
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

// Full width layout
add_filter( 'be_page_layout', 'be_return_full_width_content' );

get_header();

// Get filter parameters
$category = isset( $_GET['category'] ) ? sanitize_text_field( $_GET['category'] ) : '';
$tag = isset( $_GET['tag'] ) ? sanitize_text_field( $_GET['tag'] ) : '';
$author = isset( $_GET['author_name'] ) ? sanitize_text_field( $_GET['author_name'] ) : '';
$year = isset( $_GET['year'] ) ? intval( $_GET['year'] ) : '';

// Determine post types based on context
$post_types = [ 'post', 'event' ];

// If on specific archive, limit to that type
if ( is_post_type_archive( 'event' ) && ! isset( $_GET['category'] ) && ! isset( $_GET['tag'] ) ) {
	$post_types = [ 'event' ];
} elseif ( is_home() && ! isset( $_GET['type'] ) ) {
	$post_types = [ 'post' ]; // Default "News" archive shows only posts
} elseif ( is_category() || is_tag() ) {
	$post_types = [ 'post' ]; // Categories/tags are for posts only
}

// Allow override via query param
if ( isset( $_GET['type'] ) ) {
	switch ( $_GET['type'] ) {
		case 'news':
			$post_types = [ 'post' ];
			break;
		case 'events':
			$post_types = [ 'event' ];
			break;
		case 'all':
			$post_types = [ 'post', 'event' ];
			break;
	}
}

// Build query args
$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

$args = [
	'post_type'      => $post_types,
	'posts_per_page' => 10,
	'paged'          => $paged,
	'post_status'    => 'publish',
	'orderby'        => 'date',
	'order'          => 'DESC',
];

// Apply existing WordPress query vars (category, tag, date, author)
if ( is_category() ) {
	$args['cat'] = get_queried_object_id();
} elseif ( is_tag() ) {
	$args['tag_id'] = get_queried_object_id();
} elseif ( is_date() ) {
	$args['year'] = get_query_var( 'year' );
	$args['monthnum'] = get_query_var( 'monthnum' );
	$args['day'] = get_query_var( 'day' );
} elseif ( is_author() ) {
	$args['author'] = get_queried_object_id();
}

// Apply manual filters from dropdowns
if ( ! empty( $category ) ) {
	$args['category_name'] = $category;
}

if ( ! empty( $tag ) ) {
	$args['tag'] = $tag;
}

if ( ! empty( $author ) ) {
	$args['author_name'] = $author;
}

if ( ! empty( $year ) && ! is_date() ) {
	$args['year'] = $year;
}

// Search
if ( get_search_query() ) {
	$args['s'] = get_search_query();
}

$query = new WP_Query( $args );

// Determine page title
$page_title = __( 'News & Events Archive', 'nwu-2025' );
if ( is_category() ) {
	$page_title = single_cat_title( '', false );
} elseif ( is_tag() ) {
	$page_title = single_tag_title( '', false );
} elseif ( is_author() ) {
	$page_title = get_the_author();
} elseif ( is_date() ) {
	if ( is_day() ) {
		$page_title = get_the_date();
	} elseif ( is_month() ) {
		$page_title = get_the_date( 'F Y' );
	} elseif ( is_year() ) {
		$page_title = get_the_date( 'Y' );
	}
} elseif ( is_post_type_archive( 'event' ) ) {
	$page_title = __( 'Events', 'nwu-2025' );
} elseif ( is_home() ) {
	$page_title = __( 'News', 'nwu-2025' );
}
?>

<div class="archive-news-events">
	<div class="wrap">

		<header class="archive-header">
			<h1 class="archive-title"><?php echo esc_html( $page_title ); ?></h1>

			<!-- Filter Bar -->
			<form class="archive-filters-form" method="get" action="">

				<div class="filter-row">

					<!-- Category Filter -->
					<div class="filter-group">
						<label for="category"><?php esc_html_e( 'Category', 'nwu-2025' ); ?></label>
						<select name="category" id="category">
							<option value=""><?php esc_html_e( 'Select', 'nwu-2025' ); ?></option>
							<?php
							$categories = get_categories( [ 'hide_empty' => true ] );
							foreach ( $categories as $cat ) :
								?>
								<option value="<?php echo esc_attr( $cat->slug ); ?>" <?php selected( $category, $cat->slug ); ?>>
									<?php echo esc_html( $cat->name ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>

					<!-- Tag Filter -->
					<div class="filter-group">
						<label for="tag"><?php esc_html_e( 'Tag', 'nwu-2025' ); ?></label>
						<select name="tag" id="tag">
							<option value=""><?php esc_html_e( 'Select', 'nwu-2025' ); ?></option>
							<?php
							$tags = get_tags( [ 'hide_empty' => true ] );
							foreach ( $tags as $post_tag ) :
								?>
								<option value="<?php echo esc_attr( $post_tag->slug ); ?>" <?php selected( $tag, $post_tag->slug ); ?>>
									<?php echo esc_html( $post_tag->name ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>

					<!-- Author Filter -->
					<div class="filter-group">
						<label for="author_name"><?php esc_html_e( 'Author Name', 'nwu-2025' ); ?></label>
						<select name="author_name" id="author_name">
							<option value=""><?php esc_html_e( 'Select', 'nwu-2025' ); ?></option>
							<?php
							$authors = get_users( [ 'who' => 'authors', 'orderby' => 'display_name' ] );
							foreach ( $authors as $author_obj ) :
								?>
								<option value="<?php echo esc_attr( $author_obj->user_nicename ); ?>" <?php selected( $author, $author_obj->user_nicename ); ?>>
									<?php echo esc_html( $author_obj->display_name ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>

					<!-- Year Filter -->
					<div class="filter-group">
						<label for="year"><?php esc_html_e( 'Year', 'nwu-2025' ); ?></label>
						<select name="year" id="year">
							<option value=""><?php esc_html_e( 'Select', 'nwu-2025' ); ?></option>
							<?php
							global $wpdb;
							$years = $wpdb->get_col( "SELECT DISTINCT YEAR(post_date) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type IN ('post', 'event') ORDER BY post_date DESC" );
							foreach ( $years as $year_option ) :
								?>
								<option value="<?php echo esc_attr( $year_option ); ?>" <?php selected( $year, $year_option ); ?>>
									<?php echo esc_html( $year_option ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>

				</div>

				<!-- Content Type Toggle -->
				<div class="filter-types">
					<a href="<?php echo esc_url( add_query_arg( 'type', 'all' ) ); ?>"
					   class="filter-type <?php echo in_array( 'post', $post_types ) && in_array( 'event', $post_types ) ? 'active' : ''; ?>">
						<?php esc_html_e( 'All', 'nwu-2025' ); ?>
					</a>
					<a href="<?php echo esc_url( add_query_arg( 'type', 'news' ) ); ?>"
					   class="filter-type <?php echo $post_types === [ 'post' ] ? 'active' : ''; ?>">
						<?php esc_html_e( 'News', 'nwu-2025' ); ?>
					</a>
					<a href="<?php echo esc_url( add_query_arg( 'type', 'events' ) ); ?>"
					   class="filter-type <?php echo $post_types === [ 'event' ] ? 'active' : ''; ?>">
						<?php esc_html_e( 'Events', 'nwu-2025' ); ?>
					</a>
				</div>

				<!-- Search Bar -->
				<div class="filter-search">
					<input type="search"
					       name="s"
					       placeholder="<?php esc_attr_e( 'Search Archives', 'nwu-2025' ); ?>"
					       value="<?php echo get_search_query(); ?>">
					<button type="submit" class="wp-element-button">
						<?php echo be_icon( [ 'icon' => 'search', 'size' => 20 ] ); ?>
						<span class="screen-reader-text"><?php esc_html_e( 'Search', 'nwu-2025' ); ?></span>
					</button>
				</div>

			</form>
		</header>

		<!-- Archive Listing -->
		<main class="site-main archive-listing" role="main">
			<?php if ( $query->have_posts() ) : ?>

				<div class="archive-results">
					<?php
					while ( $query->have_posts() ) :
						$query->the_post();

						// Date and Post Type
						$post_type = get_post_type();
						$post_date = get_the_date( 'M j, Y' );

						// Get type label
						if ( 'event' === $post_type ) {
							$post_type_label = __( 'Event', 'nwu-2025' );
							// Could also use: get_field( 'event_type' );
						} else {
							$categories = get_the_category();
							$post_type_label = ! empty( $categories ) ? $categories[0]->name : __( 'News', 'nwu-2025' );
						}
						?>

						<article class="archive-item archive-item--<?php echo esc_attr( $post_type ); ?>">

							<div class="archive-item__meta">
								<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
									<?php echo esc_html( $post_date ); ?>
								</time>
								<span class="archive-item__type"><?php echo esc_html( $post_type_label ); ?></span>
							</div>

							<h2 class="archive-item__title">
								<a href="<?php the_permalink(); ?>">
									<?php the_title(); ?>
								</a>
							</h2>

							<?php if ( has_excerpt() ) : ?>
								<div class="archive-item__excerpt">
									<?php the_excerpt(); ?>
								</div>
							<?php endif; ?>

							<a href="<?php the_permalink(); ?>" class="archive-item__arrow" aria-label="<?php esc_attr_e( 'Read more', 'nwu-2025' ); ?>">
								→
							</a>

						</article>

					<?php endwhile; ?>
				</div>

				<!-- Pagination -->
				<?php
				if ( $query->max_num_pages > 1 ) :
					?>
					<nav class="archive-pagination">
						<div class="pagination-newer">
							<?php
							if ( $paged > 1 ) {
								echo '<a href="' . esc_url( get_pagenum_link( $paged - 1 ) ) . '">' . esc_html__( 'Newer Posts', 'nwu-2025' ) . '</a>';
							}
							?>
						</div>
						<div class="pagination-older">
							<?php
							if ( $paged < $query->max_num_pages ) {
								echo '<a href="' . esc_url( get_pagenum_link( $paged + 1 ) ) . '">' . esc_html__( 'Older Posts', 'nwu-2025' ) . '</a>';
							}
							?>
						</div>
					</nav>
				<?php endif; ?>

			<?php else : ?>
				<p class="no-results"><?php esc_html_e( 'No items found.', 'nwu-2025' ); ?></p>
			<?php endif; ?>

			<?php wp_reset_postdata(); ?>
		</main>

		<!-- Explore Topics (Tag Cloud) -->
		<aside class="archive-topics">
			<h2><?php esc_html_e( 'Explore Topics', 'nwu-2025' ); ?></h2>
			<?php
			$topic_tags = get_tags( [ 'number' => 20, 'orderby' => 'count', 'order' => 'DESC' ] );
			if ( $topic_tags ) :
				echo '<div class="topic-tags">';
				foreach ( $topic_tags as $topic_tag ) :
					echo '<a href="' . esc_url( get_tag_link( $topic_tag ) ) . '" class="topic-tag">' . esc_html( $topic_tag->name ) . '</a>';
				endforeach;
				echo '</div>';
			endif;
			?>
		</aside>

	</div>
</div>

<?php get_footer(); ?>
