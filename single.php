<?php
/**
 * Single Post
 *
 * @package      NWU2025
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

use NWU2025\Block_Areas;

/**
 * After Post
 */
function be_after_post() {
	Block_Areas\show( 'after-post' );
}
add_action( 'tha_content_while_after', 'be_after_post', 8 );


// Build the page.
require get_template_directory() . '/index.php';
