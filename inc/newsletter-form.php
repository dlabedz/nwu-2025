<?php
/**
 * Newsletter Form
 *
 * @package      NWU2025
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

/**
 * Output Newsletter Form
 */
function be_newsletter_form() {
	?>
	<div id="mc_embed_signup" class="nwu-newsletter-form">
		<form action="https://nwu.us14.list-manage.com/subscribe/post?u=6b3231b57db1fb255b32d2164&amp;id=5c5d4e4eae&amp;f_id=00590ae0f0" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
			<div id="mc_embed_signup_scroll">

				<div class="mc-field-group">
					<label for="mce-EMAIL">Email Address <span class="required">*</span></label>
					<input type="email" name="EMAIL" class="required email" id="mce-EMAIL" required="" value="">
				</div>

				<div class="mc-field-group">
					<label for="mce-FNAME">First Name</label>
					<input type="text" name="FNAME" class="text" id="mce-FNAME" value="">
				</div>

				<div class="mc-field-group">
					<label for="mce-LNAME">Last Name</label>
					<input type="text" name="LNAME" class="text" id="mce-LNAME" value="">
				</div>

				<div id="mce-responses" class="clear">
					<div class="response" id="mce-error-response" style="display: none;"></div>
					<div class="response" id="mce-success-response" style="display: none;"></div>
				</div>

				<!-- Honeypot -->
				<div style="position: absolute; left: -5000px;" aria-hidden="true">
					<input type="text" name="b_6b3231b57db1fb255b32d2164_5c5d4e4eae" tabindex="-1" value="">
				</div>

				<div class="clear">
					<input type="submit" name="subscribe" id="mc-embedded-subscribe" class="button wp-element-button" value="Subscribe">
				</div>
			</div>
		</form>
	</div>
	<?php
}

/**
 * Enqueue Mailchimp Scripts
 */
function be_newsletter_scripts() {
	// Only load on pages with footer (basically everywhere except maybe admin)
	if ( is_admin() ) {
		return;
	}

	wp_enqueue_script(
		'mailchimp-validate',
		'//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js',
		array( 'jquery' ),
		null,
		true
	);

	// Add inline script for validation
	$inline_script = "
	(function($) {
		window.fnames = new Array();
		window.ftypes = new Array();
		fnames[0]='EMAIL';ftypes[0]='email';
		fnames[1]='FNAME';ftypes[1]='text';
		fnames[2]='LNAME';ftypes[2]='text';
	}(jQuery));
	var \$mcj = jQuery.noConflict(true);
	";

	wp_add_inline_script( 'mailchimp-validate', $inline_script );
}
add_action( 'wp_enqueue_scripts', 'be_newsletter_scripts' );
