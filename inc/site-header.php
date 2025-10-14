<?php
/**
 * Site Header
 *
 * @package      NWU2025
 * @subpackage   site-header/01
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

/**
 * Register nav menus
 */
function be_register_menus() {
	register_nav_menus(
		[
			'primary' => esc_html__( 'Primary Navigation Menu', 'nwu-2025' ),
			'utility' => esc_html__( 'Utility Menu', 'nwu-2025' ),
		]
	);
}
add_action( 'after_setup_theme', 'be_register_menus' );

/**
 * Site Header
 */
function be_site_header() {
	echo '<a href="' . esc_url( home_url() ) . '" rel="home" class="site-header__logo header-logo" aria-label="' . esc_attr( get_bloginfo( 'name' ) ) . ' Home">';
	echo '<img src="' . esc_url( get_template_directory_uri() . '/assets/images/NWU_logo.svg' ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '">';
	echo '</a>';

	echo '<div class="site-header__toggles">';
	echo be_mobile_menu_toggle();
	echo '</div>';

	echo '<div class="nav-container">';
		echo '<nav class="nav-menu-top" role="navigation" aria-label="Utility Navigation">';
		if ( has_nav_menu( 'utility' ) ) {
			wp_nav_menu( array(
				'theme_location' => 'utility',
				'menu_id' => 'utility-menu',
				'container_class' => 'nav-utility',
				'depth' => 1 // No submenus for utility menu
			) );
		}
		echo '</nav>';

		echo '<nav class="nav-menu" role="navigation" aria-label="Primary Navigation">';
		if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'menu_id' => 'primary-menu',
				'container_class' => 'nav-primary'
			) );
		}
		echo '</nav>';
	echo '</div>';
}
add_action( 'tha_header_bottom', 'be_site_header', 11 );

/**
 * Mobile menu toggle
 */
function be_mobile_menu_toggle() {
	$output  = '<button aria-label="Toggle Menu" aria-expanded="false" class="menu-toggle">';
	$output .= be_icon( array( 'icon' => 'menu', 'class' => 'open', 'force' => true ) );
	$output .= be_icon( array( 'icon' => 'close', 'class' => 'close', 'force' => true ) );
	$output .= '</button>';
	return $output;
}

/**
 * Add a dropdown icon to menu items with children
 */
function be_nav_add_dropdown_icons( $output, $item, $depth, $args ) {

	if ( ! isset( $args->theme_location ) || 'primary' !== $args->theme_location ) {
		return $output;
	}

	if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
		// Use chevron-up-2 icon from assets/icons/utility/
		$icon = be_icon( array( 'icon' => 'chevron-up-2', 'size' => 24 ) );

		$output .= sprintf(
			'<button aria-label="Toggle Submenu for %s" aria-expanded="false" class="submenu-expand" tabindex="-1">%s</button>',
			esc_attr( $item->title ),
			$icon
		);
	}

	return $output;
}
add_filter( 'walker_nav_menu_start_el', 'be_nav_add_dropdown_icons', 10, 4 );

/**
 * Hide Member Pages menu item from logged out users
 */
function nwu_hide_member_pages_from_logged_out( $items, $args ) {
	// Only apply to utility menu
	if ( $args->theme_location !== 'utility' ) {
		return $items;
	}

	// If user is logged in, show all items
	if ( is_user_logged_in() ) {
		return $items;
	}

	// Loop through menu items and remove Member Pages for logged out users
	foreach ( $items as $key => $item ) {
		// Check if this is the Member Pages item
		// You can adjust these conditions based on how you identify the member pages link
		if (
			stripos( $item->title, 'member pages' ) !== false ||
			stripos( $item->url, 'member-pages' ) !== false ||
			in_array( 'member-pages-link', $item->classes, true )
		) {
			unset( $items[$key] );
		}
	}

	return $items;
}
add_filter( 'wp_nav_menu_objects', 'nwu_hide_member_pages_from_logged_out', 10, 2 );

/**
 * Add custom class to Member Pages menu item
 */
function nwu_add_member_pages_class( $classes, $item, $args ) {
	// Only apply to utility menu
	if ( $args->theme_location !== 'utility' ) {
		return $classes;
	}

	// Check if this is the Member Pages item
	if (
		stripos( $item->title, 'member pages' ) !== false ||
		stripos( $item->url, 'member-pages' ) !== false
	) {
		$classes[] = 'member-pages-link';
	}

	return $classes;
}
add_filter( 'nav_menu_css_class', 'nwu_add_member_pages_class', 10, 3 );
