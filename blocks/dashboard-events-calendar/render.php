<?php
/**
 * Dashboard Events Calendar Block
 *
 * @package      NWU2025
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

$show_past_events    = get_field( 'show_past_events' );
$chapter_events_only = get_field( 'chapter_events_only' );

// Get month/year from query params or use current (site timezone).
$current_month = isset( $_GET['cal_month'] ) ? intval( $_GET['cal_month'] ) : (int) current_time( 'n' );
$current_year  = isset( $_GET['cal_year'] ) ? intval( $_GET['cal_year'] ) : (int) current_time( 'Y' );
?>

<div class="block-dashboard-events-calendar" data-chapter-events-only="<?php echo esc_attr( $chapter_events_only ? '1' : '0' ); ?>">

	<?php echo nwu_generate_calendar_html( $current_month, $current_year, $show_past_events, $chapter_events_only ); ?>

</div>
