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

echo '<div class="membership-signup-block">';

if (!empty($form_title)) {
    echo '<h2 class="membership-signup-title">' . esc_html($form_title) . '</h2>';
}

echo '<div class="membership-signup-form">';

// Check if CiviCRM is available
if (!class_exists('CRM_Core_Invoke')) {
    // Local: Show placeholder
    echo '<div class="civicrm-placeholder">';
    echo '<p>📋 CiviCRM Membership Form will display here on staging/production</p>';
    echo '</div>';
} else {
    // Staging/Production: Invoke CiviCRM directly

    // Make sure CiviCRM is initialized
    if (function_exists('civi_wp')) {
        civi_wp()->initialize();
    }

    // Add CiviCRM resources (CSS, JS)
    CRM_Core_Resources::singleton()->addCoreResources();

    // Get the Afform and render it
    echo '<div id="bootstrap-theme">';
    echo CRM_Core_Smarty::singleton()->fetchWith('string:<div af-form-name="afformMembershipSignup"></div>', []);
    echo '</div>';

    // Load the Angular module for this specific form
    CRM_Core_Resources::singleton()->addScriptFile('civicrm', 'ang/afformMembershipSignup.aff.js', -9000, 'html-header', FALSE);
}

echo '</div>';
echo '</div>';
