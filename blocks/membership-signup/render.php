<?php
/**
 * Membership Signup block
 */

$title = get_field('form_title');

if (!empty($title)) {
    echo '<h2>' . esc_html($title) . '</h2>';
}

echo do_shortcode('[civicrm component="afform" name="afformMembershipSignup"]');
