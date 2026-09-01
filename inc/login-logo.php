<?php
/**
 * Login Logo
 *
 * @package      NWU2025
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

/**
 * Login Logo URL
 *
 * @param string $url URL.
 */
function be_login_header_url( $url ) {
	return esc_url( home_url() );
}
add_filter( 'login_headerurl', 'be_login_header_url' );
add_filter( 'login_headertext', '__return_empty_string' );

/**
 * Login Logo
 */
function be_login_logo() {

	$logo_path   = '/assets/icons/logo/logo.svg';
	$logo_width  = 212;
	$logo_height = 40;

	if ( ! file_exists( get_theme_file_path( $logo_path ) ) ) {
		return;
	}

	$logo   = get_theme_file_uri( $logo_path );
	$height = floor( $logo_height / $logo_width * 312 );
	$styles = sprintf(
		'.login h1 a {
			background-image: url(%s);
			background-size: contain;
			background-repeat: no-repeat;
			background-position: center center;
			display: block;
			overflow: hidden;
			text-indent: -9999em;
			width: 312px;
			height: %dpx;
		}',
		esc_url( $logo ),
		$height
	);
	wp_add_inline_style( 'theme-style', $styles );
}
//add_action( 'login_head', 'be_login_logo' );

/**
 * Force-print the global styles stylesheet on the /login route.
 */
function nwu_force_global_styles_on_login() {

	$is_login_route = is_page( 'login' )
		|| ( function_exists( 'tml_is_login_page' ) && tml_is_login_page() );

	if ( ! $is_login_route ) {
		return;
	}

	global $wp_styles;
	if ( isset( $wp_styles->registered['global-styles'] ) && in_array( 'global-styles', $wp_styles->done, true ) ) {
		return;
	}

	printf( '<style id="nwu-global-styles-fallback">%s</style>', wp_get_global_stylesheet() );
}
add_action( 'wp_head', 'nwu_force_global_styles_on_login', 20 );
