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

get_header();
?>

<div class="dashboard-wrapper">

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
			<h1><?php printf( esc_html__( 'Hello, %s!', 'nwu-2025' ), esc_html( $first_name ) ); ?></h1>

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
				<p>
					<?php
					printf(
						/* translators: Quick links for dashboard actions */
						wp_kses_post( __( 'From your account dashboard you can manage your <a href="%1$s">benefits</a>, <a href="%2$s">membership</a>, find <a href="%3$s">other members</a>, <a href="%4$s">create a post</a>, or request <a href="%5$s">services</a> or <a href="%6$s">support</a>.', 'nwu-2025' ) ),
						esc_url( '#' ),
						esc_url( home_url( '/edit-member-profile' ) ),
						esc_url( '#' ),
						esc_url( '#' ),
						esc_url( '#' ),
						esc_url( '#' )
					);
					?>
				</p>
			</div>
		</main>
	</div>

	<!-- Section 2: Page Content (All blocks added via editor) -->
	<div class="dashboard-content-wrapper">
		<?php
		// This renders all blocks added to the page content in the WordPress editor
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
		?>
	</div>

</div><!-- .dashboard-wrapper -->

<?php get_footer(); ?>
