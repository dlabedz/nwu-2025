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

/**
 * Site Footer
 */
function be_site_footer_top() {
	echo '<div class="footer-main-container">';

		// Column 1: Logo, Utility Menu, Address
		echo '<div class="footer-column-1">';

			echo '<div class="footer-badge">';
				echo '<a href="' . esc_url( home_url() ) . '" rel="home" class="site-footer__logo footer-logo" aria-label="' . esc_attr( get_bloginfo( 'name' ) ) . ' Home">';
					echo '<img src="' . esc_url( get_template_directory_uri() . '/assets/images/footer-logo.svg' ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '">';
				echo '</a>';

				echo '<nav class="footer-utility-nav" role="navigation" aria-label="Utility Menu">';
					if ( has_nav_menu( 'utility' ) ) {
						wp_nav_menu( array(
							'theme_location' => 'utility',
							'menu_id' => 'footer-utility-menu',
							'container_class' => 'nav-utility'
						) );
					}
				echo '</nav>';
			echo '</div>';

			echo '<div class="footer-address">';
				// Address Title
				$address_title = get_field('address_title', 'option');
				if ($address_title) {
					echo '<h4 class="address-title">';
						echo esc_html($address_title);
					echo '</h4>';
				}

				// Address Content
				$address = get_field('address', 'option');
				if ($address) {
					echo '<div class="address">';
						echo wp_kses_post($address);
					echo '</div>';
				}
			echo '</div>';

		echo '</div>';

		// Column 2: Navigation Menus
		echo '<div class="footer-column-2">';
			echo '<nav class="footer-primary-container" role="navigation" aria-label="Primary Footer Navigation">';
				if ( has_nav_menu( 'footer-primary' ) ) {
					wp_nav_menu( array(
						'theme_location' => 'footer-primary',
						'menu_id' => 'footer-primary',
						'container_class' => 'footer-primary'
					) );
				}
			echo '</nav>';

			echo '<nav class="footer-secondary-container" role="navigation" aria-label="Secondary Footer Navigation">';
				if ( has_nav_menu( 'footer-secondary' ) ) {
					wp_nav_menu( array(
						'theme_location' => 'footer-secondary',
						'menu_id' => 'footer-secondary',
						'container_class' => 'footer-secondary'
					) );
				}
			echo '</nav>';
		echo '</div>';

		// Column 3: Newsletter & Social
		echo '<div class="footer-column-3">';

			// Newsletter Title & Social Links Header
			$newsletter_title = get_field('newsletter_form_title', 'option');

			echo '<div class="newsletter-header">';
				if ($newsletter_title) {
					echo '<div class="newsletter-title">';
						echo esc_html($newsletter_title);
					echo '</div>';
				}

				// Social Media Links
				$facebook_url = get_field('facebook_url', 'option');
				$twitter_url = get_field('twitter_url', 'option');
				$linkedin_url = get_field('linkedin_url', 'option');

				if ($facebook_url || $twitter_url || $linkedin_url) {
					echo '<div class="footer-social-links" aria-label="Social Media Links">';

						if ($facebook_url) {
							echo '<a href="' . esc_url($facebook_url) . '" target="_blank" rel="noopener noreferrer" aria-label="Follow us on Facebook" class="social-link social-link--facebook">';
								echo '<img src="' . esc_url(get_template_directory_uri() . '/assets/icons/utility/facebook.svg') . '" alt="Facebook" width="24" height="24">';
							echo '</a>';
						}

						if ($twitter_url) {
							echo '<a href="' . esc_url($twitter_url) . '" target="_blank" rel="noopener noreferrer" aria-label="Follow us on X (formerly Twitter)" class="social-link social-link--twitter">';
								echo '<img src="' . esc_url(get_template_directory_uri() . '/assets/icons/utility/x.svg') . '" alt="X" width="24" height="24">';
							echo '</a>';
						}

						if ($linkedin_url) {
							echo '<a href="' . esc_url($linkedin_url) . '" target="_blank" rel="noopener noreferrer" aria-label="Follow us on LinkedIn" class="social-link social-link--linkedin">';
								echo '<img src="' . esc_url(get_template_directory_uri() . '/assets/icons/utility/linkedin.svg') . '" alt="LinkedIn" width="24" height="24">';
							echo '</a>';
						}

					echo '</div>';
				}
			echo '</div>';

			// Newsletter Subtitle
			$newsletter_subtitle = get_field('newsletter_form_subtitle', 'option');
			if ($newsletter_subtitle) {
				echo '<p class="newsletter-subtitle">';
					echo esc_html($newsletter_subtitle);
				echo '</p>';
			}

			// Newsletter Form (CiviCRM embed area)
			echo '<div class="newsletter-form">';
				// Add CiviCRM newsletter form embed here
			echo '</div>';

		echo '</div>';

	echo '</div>';
}
add_action( 'tha_footer_top', 'be_site_footer_top' );


/**
 * Copyright
 */
function be_site_footer_bottom() {
	echo '<div class="footer-copyright-bar">';
		echo '<p class="copyright">&copy; ' . date( 'Y' ) . ' National Writers Union. All rights reserved. <a href="' . esc_url(wp_logout_url(home_url())) . '">Log Out</a>.</p>';
	echo '</div>';
}
add_action( 'tha_footer_bottom', 'be_site_footer_bottom' );
