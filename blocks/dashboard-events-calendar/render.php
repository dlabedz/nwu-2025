<?php
/**
 * Dashboard Events Calendar Block
 *
 * @package      NWU2025
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

$show_past_events   = get_field( 'show_past_events' );
$chapter_events_only = get_field( 'chapter_events_only' );

// Get month/year from query params or use current
$current_month = isset( $_GET['cal_month'] ) ? intval( $_GET['cal_month'] ) : date( 'n' );
$current_year  = isset( $_GET['cal_year'] ) ? intval( $_GET['cal_year'] ) : date( 'Y' );

// Get user's chapter if filtering by chapter
$user_chapter = null;
if ( $chapter_events_only ) {
	$user_id      = get_current_user_id();
	$user_chapter = get_user_meta( $user_id, 'nwu_user_chapter', true );
}

// Calculate first and last day of month
$first_day = strtotime( "$current_year-$current_month-01" );
$last_day  = strtotime( date( 'Y-m-t', $first_day ) );

// Query events for this month
$args = array(
	'post_type'      => 'events',
	'posts_per_page' => -1,
	'meta_query'     => array(
		array(
			'key'     => 'event_date',
			'value'   => array( date( 'Y-m-d', $first_day ), date( 'Y-m-d', $last_day ) ),
			'compare' => 'BETWEEN',
			'type'    => 'DATE',
		),
	),
	'orderby'        => 'meta_value',
	'order'          => 'ASC',
	'meta_key'       => 'event_date',
);

// Filter by chapter if needed
if ( $chapter_events_only && ! empty( $user_chapter ) ) {
	$args['meta_query'][] = array(
		'relation' => 'OR',
		array(
			'key'     => 'event_scope',
			'value'   => 'All Chapters',
			'compare' => '=',
		),
		array(
			'key'     => 'event_chapter',
			'value'   => $user_chapter,
			'compare' => '=',
		),
	);
}

$events_query = new WP_Query( $args );

// Organize events by day
$events_by_day = array();
if ( $events_query->have_posts() ) {
	while ( $events_query->have_posts() ) {
		$events_query->the_post();
		$event_date = get_field( 'event_date' );
		if ( $event_date ) {
			$day = date( 'j', strtotime( $event_date ) );
			if ( ! isset( $events_by_day[ $day ] ) ) {
				$events_by_day[ $day ] = array();
			}
			$events_by_day[ $day ][] = get_the_ID();
		}
	}
	wp_reset_postdata();
}

// Calculate navigation URLs
$prev_month = $current_month - 1;
$prev_year  = $current_year;
if ( $prev_month < 1 ) {
	$prev_month = 12;
	$prev_year--;
}

$next_month = $current_month + 1;
$next_year  = $current_year;
if ( $next_month > 12 ) {
	$next_month = 1;
	$next_year++;
}

$prev_url = add_query_arg( array( 'cal_month' => $prev_month, 'cal_year' => $prev_year ) );
$next_url = add_query_arg( array( 'cal_month' => $next_month, 'cal_year' => $next_year ) );

// Get calendar grid data
$month_start_day = date( 'w', $first_day ); // 0 (Sun) to 6 (Sat)
$days_in_month   = date( 't', $first_day );
$today_day       = ( date( 'n' ) == $current_month && date( 'Y' ) == $current_year ) ? date( 'j' ) : 0;

// Generate unique block ID for AJAX
$block_id = 'calendar-' . uniqid();
?>

<div class="block-dashboard-events-calendar" data-block-id="<?php echo esc_attr( $block_id ); ?>">

	<div class="calendar-wrapper">
		<div class="calendar-header">
			<h3><?php echo esc_html( date( 'F Y', $first_day ) ); ?></h3>

			<div class="calendar-nav">
				<a href="<?php echo esc_url( $prev_url ); ?>" class="calendar-nav__prev" aria-label="<?php esc_attr_e( 'Previous Month', 'nwu-2025' ); ?>">
					<?php echo be_icon( array( 'icon' => 'chevron-large-left', 'size' => 24 ) ); ?>
				</a>
				<a href="<?php echo esc_url( $next_url ); ?>" class="calendar-nav__next" aria-label="<?php esc_attr_e( 'Next Month', 'nwu-2025' ); ?>">
					<?php echo be_icon( array( 'icon' => 'chevron-large-right', 'size' => 24 ) ); ?>
				</a>
			</div>
		</div>

		<div class="calendar-grid">
			<!-- Day headers -->
			<div class="calendar-day-header">Sun</div>
			<div class="calendar-day-header">Mon</div>
			<div class="calendar-day-header">Tue</div>
			<div class="calendar-day-header">Wed</div>
			<div class="calendar-day-header">Thu</div>
			<div class="calendar-day-header">Fri</div>
			<div class="calendar-day-header">Sat</div>

			<?php
			// Empty cells before month starts
			for ( $i = 0; $i < $month_start_day; $i++ ) {
				echo '<div class="calendar-day calendar-day--empty"></div>';
			}

			// Days of the month
			for ( $day = 1; $day <= $days_in_month; $day++ ) {
				$day_classes   = array( 'calendar-day' );
				$day_of_week   = date( 'w', strtotime( "$current_year-$current_month-$day" ) );
				$has_events    = isset( $events_by_day[ $day ] );
				$is_today      = ( $day == $today_day );

				if ( $is_today ) {
					$day_classes[] = 'calendar-day--today';
				}
				if ( $has_events ) {
					$day_classes[] = 'calendar-day--has-events';
				}
				if ( $day_of_week == 0 || $day_of_week == 6 ) {
					$day_classes[] = 'calendar-day--weekend';
				}

				echo '<div class="' . esc_attr( implode( ' ', $day_classes ) ) . '">';
				echo '<span class="calendar-day-number">' . esc_html( $day ) . '</span>';

				if ( $has_events ) {
					echo '<span class="calendar-event-dot"></span>';
				}

				echo '</div>';
			}
			?>
		</div>

	</div>

</div>
