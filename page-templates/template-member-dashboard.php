<?php
/**
 * Template Name: Member Dashboard
 *
 * @package      NWU2025
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

// Redirect if not logged in
if ( ! is_user_logged_in() ) {
	wp_redirect( wp_login_url( get_permalink() ) );
	exit;
}

// Get current user data
$current_user = wp_get_current_user();
$first_name   = $current_user->first_name ? $current_user->first_name : $current_user->user_login;

// If CiviCRM is handling this request, unhook it from the_content filter
// so it doesn't render a second time via apply_filters( 'the_content', ... )
if ( function_exists( 'civicrm_initialize' ) && isset( $_GET['civiwp'] ) && $_GET['civiwp'] === 'CiviCRM' ) {
	civicrm_initialize();
	remove_filter( 'the_content', array( 'CRM_Utils_Hook', 'alterContent' ), 99 );
}

get_header();
?>

<div class="dashboard-wrapper">

	<!-- Page Header: Breadcrumbs + Title -->
	<div class="dashboard-page-header">
		<?php
		if ( function_exists( 'aioseo_breadcrumbs' ) ) {
			aioseo_breadcrumbs();
		}
		?>
		<h1 class="dashboard-page-title"><?php the_title(); ?></h1>
	</div>

	<!-- Section 1: Sidebar + Main Greeting -->
	<div class="dashboard-top-section">
		<aside class="dashboard-sidebar">
			<h2 class="dashboard-sidebar__title screen-reader-text"><?php esc_html_e( 'Dashboard Navigation', 'nwu-2025' ); ?></h2>
			<?php
			if ( has_nav_menu( 'member-dashboard' ) ) {
				wp_nav_menu(
					array(
						'theme_location'  => 'member-dashboard',
						'menu_id'         => 'dashboard-menu',
						'container'       => 'nav',
						'container_class' => 'dashboard-nav',
						'menu_class'      => 'dashboard-menu',
						'fallback_cb'     => false,
					)
				);
			} else {
				echo '<p class="no-menu-assigned">' . esc_html__( 'Please assign a menu to "Member Dashboard Menu" location.', 'nwu-2025' ) . '</p>';
			}
			?>
		</aside>

		<main class="dashboard-greeting">
			<h2 class="dashboard-greeting-title">
				<?php
				printf(
					/* translators: %s: user first name */
					esc_html__( 'Hello, %s!', 'nwu-2025' ),
					esc_html( $first_name )
				);
				?>
			</h2>

			<p class="dashboard-logout-link">
				<?php
				printf(
					/* translators: %1$s: user first name, %2$s: logout URL */
					esc_html__( '(Not %1$s? %2$s)', 'nwu-2025' ),
					esc_html( $first_name ),
					'<a href="' . esc_url( wp_logout_url( home_url() ) ) . '">' . esc_html__( 'Log out', 'nwu-2025' ) . '</a>'
				);
				?>
			</p>

			<div class="dashboard-intro">
				<?php
				$intro_block = get_posts( array(
					'post_type'      => 'block_area',
					'name'           => 'dashboard-intro',
					'posts_per_page' => 1,
					'post_status'    => 'publish',
				) );

				if ( $intro_block ) {
					// CiviCRM filter already removed above if civiwp is set,
					// so this apply_filters call won't trigger a second CiviCRM render
					echo do_shortcode( apply_filters( 'the_content', $intro_block[0]->post_content ) );
				}
				?>
			</div>
		</main>
	</div>

	<!-- Section 2: CiviCRM forms or full-width blocks added via editor -->
	<div class="dashboard-content-wrapper">
		<?php
		if ( function_exists( 'civicrm_initialize' ) && isset( $_GET['civiwp'] ) && $_GET['civiwp'] === 'CiviCRM' ) {
			// CiviCRM is handling this request — output it directly
			$config = CRM_Core_Config::singleton();
			ob_start();
			CRM_Core_Invoke::invoke( explode( '/', CRM_Utils_Array::value( 'q', $_GET, '' ) ) );
			$civicrm_output = ob_get_clean();
			echo $civicrm_output;
		} else {
			// Normal dashboard page — render block content as usual
			while ( have_posts() ) :
				the_post();
				the_content();
			endwhile;
		}
		?>
	</div>

</div><!-- .dashboard-wrapper -->

<?php get_footer(); ?>
