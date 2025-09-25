<?php
/**
 * Site Footer
 *
 * @package      NWU2025
 * @subpackage   site-header/01
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

use NWU2025\Blocks\Social_Links;

/**
 * Site Footer
 */
function be_site_footer_top() {
	echo '<div class="footer-main-container">';
		echo '<div class="footer-column-1">';

			echo '<a href="' . esc_url( home_url() ) . '" rel="home" class="site-header__logo header-logo" aria-label="' . esc_attr( get_bloginfo( 'name' ) ) . ' Home">';
    			echo '<img src="' . esc_url( get_template_directory_uri() . '/assets/images/footer-logo.svg' ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '">';
			echo '</a>';

			echo '<nav class="nav-menu-top" role="navigation">';
				if ( has_nav_menu( 'utility' ) ) {
					wp_nav_menu( array( 'theme_location' => 'utility', 'menu_id' => 'utility-menu', 'container_class' => 'nav-utility' ) );
				}
			echo '</nav>';

			// Address
            $address = get_field('address', 'option');
            if ($address) {
                echo '<div class="footer-address">';
                	echo wp_kses_post($address); // Allows safe HTML tags
                echo '</div>';
            }

		echo '</div>';

		echo '<div class="footer-column-2">';
			echo '<nav class="footer-primary-container" role="navigation">';
				if ( has_nav_menu( 'footer-primary' ) ) {
					wp_nav_menu( array(
						'theme_location' => 'footer-primary',
						'menu_id' => 'footer-primary',
						'container_class' => 'footer-primary'
					) );
				}
			echo '</nav>';

			echo '<nav class="footer-secondary-container" role="navigation">';
				if ( has_nav_menu( 'footer-secondary' ) ) {
					wp_nav_menu( array(
						'theme_location' => 'footer-secondary',
						'menu_id' => 'footer-secondary',
						'container_class' => 'footer-secondary'
					) );
				}
			echo '</nav>';
		echo '</div>';

		echo '<div class="footer-column-3">';

		 // Newsletter Signup
			$title = get_field('newsletter_form_title', 'option');
			if ($address) {
				echo '<p class="">';
					echo esc_html($title);
				echo '</p>';
			}

			$subtitle = get_field('newsletter_form_subtitle', 'option');
			if ($address) {
				echo '<p class="">';
					echo esc_html($subtitle);
				echo '</p>';
			}

		echo '</div>';
	echo '</div>';
}
add_action( 'tha_footer_top', 'be_site_footer_top' );


/**
 * Copyright
 */
function be_site_footer_bottom() {
	echo '<p>&copy;' . date( 'Y' ) . ' ' . 'National Writers Union' . '. All rights reserved. <a href="">Log Out</a>.</p>';
}
add_action( 'tha_footer_bottom', 'be_site_footer_bottom' );
