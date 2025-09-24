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
	echo '<p>This is where the footer menus will go</p>';
}
add_action( 'tha_footer_top', 'be_site_footer_top' );


/**
 * Copyright
 */
function be_site_footer_bottom() {
	echo '<p>&copy;' . date( 'Y' ) . ' ' . 'National Writers Union' . '. All rights reserved. <a href="">Log Out</a>.</p>';
}
add_action( 'tha_footer_bottom', 'be_site_footer_bottom' );
