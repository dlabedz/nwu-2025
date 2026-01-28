<?php
/**
 * Membership Signup Form block
 *
 * @package      NWU2025
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

$form_title = get_field('form_title');
$form_shortcode = get_field('form_shortcode');

if (empty($form_shortcode)) {
    $form_shortcode = '[civicrm component="afform" name="afformMembershipSignup"]';
}

echo '<div class="membership-signup-block">';

	echo '<div class="membership-signup-content">';

	if (!empty($form_title)) {
		echo '<h2 class="membership-signup-title">' . esc_html($form_title) . '</h2>';
	}

	if (!empty($form_shortcode)) {
		echo '<div class="membership-signup-form">';
		echo do_shortcode($form_shortcode);
		echo '</div>';
	}

	echo '</div>';


echo '</div>';
