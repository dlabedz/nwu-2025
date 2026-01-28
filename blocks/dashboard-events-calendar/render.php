<?php
/**
 * Dashboard Events Calendar Block
 *
 * @package      NWU2025
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

// Get block settings
$show_past_events    = get_field( 'show_past_events' );
$chapter_events_only = get_field( 'chapter_events_only' );

// Get current month/year from URL or use current date
$current_month = isset( $_GET['cal_month'] ) ? intval( $_GET['cal_month'] ) : date( 'n' );
$current_year  = isset( $_GET['cal_year'] ) ? intval( $_GET['cal_year'] ) : date( 'Y' );

// Validate month/year
$current_month = max( 1, min( 12, $current_month ) );
$current_year  = max( 2020, min( 2030, $current_year ) );

// Get first and last day of month
$first_day = date( 'Y-m-01', strtotime( "$current_year-$current_month-01" ) );
$last_day  = date( 'Y-m-t', strtotime( "$current_year-$current_month-01" ) );

// Get first day of week for the month
$first_day_of_week = date( 'w', strtotime( $first_day ) ); // 0 = Sunday

// Get total days in month
$days_in_month = date( 't', strtotime( $first_day ) );

// Build query args
$query_args = array(
	'post_type'      => 'events',
	'posts_per_page' => -1,
	'meta_key'       => 'event_date',
	'orderby'        => 'meta_value',
	'order'          => 'ASC',
	'meta_query'     => array(
		array(
			'key'     => 'event_date',
			'value'   => array( $first_day, $last_day ),
			'compare' => 'BETWEEN',
			'type'    => 'DATE',
		),
	),
);

// Filter by user's chapter if option is enabled
if ( $chapter_events_only ) {
	$user_id      = get_current_user_id();
	$user_chapter = get_user_meta( $user_id, 'nwu_user_chapter', true );

	if ( ! empty( $user_chapter ) ) {
		$query_args['meta_query'][] = array(
			'relation' => 'OR',
			array(
				'key'   => 'event_scope',
				'value' => 'all-chapters',
			),
			array(
				'key'   => 'event_chapter',
				'value' => $user_chapter,
			),
		);
	}
}

// Query events
$events_query = new WP_Query( $query_args );

// Organize events by day
$events_by_day = array();
if ( $events_query->have_posts() ) {
	while ( $events_query->have_posts() ) {
		$events_query->the_post();
		$event_date = get_field( 'event_date' );
		$day        = date( 'j', strtotime( $event_date ) );

		if ( ! isset( $events_by_day[ $day ] ) ) {
			$events_by_day[ $day ] = array();
		}

		$events_by_day[ $day ][] = array(
			'id'    => get_the_ID(),
			'title' => get_the_title(),
			'url'   => get_permalink(),
			'time'  => get_field( 'event_time' ),
		);
	}
	wp_reset_postdata();
}

// Calculate previous/next month
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

$current_page_url = get_permalink();
?>

<div class="block-dashboard-events-calendar">
	<div class="calendar-header">
		<h2><?php echo esc_html( date( 'F Y', strtotime( $first_day ) ) ); ?></h2>

		<div class="calendar-nav">
			<a href="<?php echo esc_url( add_query_arg( array( 'cal_month' => $prev_month, 'cal_year' => $prev_year ), $current_page_url ) ); ?>" class="calendar-nav__prev" aria-label="<?php esc_attr_e( 'Previous month', 'nwu-2025' ); ?>">
				<?php echo be_icon( array( 'icon' => 'chevron-large-left', 'size' => 20 ) ); ?>
			</a>
			<a href="<?php echo esc_url( add_query_arg( array( 'cal_month' => $next_month, 'cal_year' => $next_year ), $current_page_url ) ); ?>" class="calendar-nav__next" aria-label="<?php esc_attr_e( 'Next month', 'nwu-2025' ); ?>">
				<?php echo be_icon( array( 'icon' => 'chevron-large-right', 'size' => 20 ) ); ?>
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
		// Empty cells before first day of month
		for ( $i = 0; $i < $first_day_of_week; $i++ ) {
			echo '<div class="calendar-day calendar-day--empty"></div>';
		}

		// Days of the month
		for ( $day = 1; $day <= $days_in_month; $day++ ) {
			$is_today   = ( $day == date( 'j' ) && $current_month == date( 'n' ) && $current_year == date( 'Y' ) );
			$day_of_week = date( 'w', strtotime( "$current_year-$current_month-$day" ) );
			$is_weekend = ( $day_of_week == 0 || $day_of_week == 6 );
			$has_events = isset( $events_by_day[ $day ] );

			$day_classes = array( 'calendar-day' );
			if ( $is_today ) {
				$day_classes[] = 'calendar-day--today';
			}
			if ( $is_weekend ) {
				$day_classes[] = 'calendar-day--weekend';
			}
			if ( $has_events ) {
				$day_classes[] = 'calendar-day--has-events';
			}

			echo '<div class="' . esc_attr( implode( ' ', $day_classes ) ) . '">';
			echo '<span class="calendar-day__number">' . esc_html( $day ) . '</span>';

			if ( $has_events ) {
				echo '<div class="calendar-day__events">';
				foreach ( $events_by_day[ $day ] as $event ) {
					echo '<a href="' . esc_url( $event['url'] ) . '" class="event-dot" title="' . esc_attr( $event['title'] ) . '"></a>';
				}
				echo '</div>';
			}

			echo '</div>';
		}

		// Empty cells after last day of month
		$remaining_cells = ( 7 - ( ( $first_day_of_week + $days_in_month ) % 7 ) ) % 7;
		for ( $i = 0; $i < $remaining_cells; $i++ ) {
			echo '<div class="calendar-day calendar-day--empty"></div>';
		}
		?>
	</div>

	<div class="calendar-footer">
		<a href="<?php echo esc_url( get_post_type_archive_link( 'events' ) ); ?>" class="view-all-events">
			<?php esc_html_e( 'View All Events →', 'nwu-2025' ); ?>
		</a>
	</div>
</div>
