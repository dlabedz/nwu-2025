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

// Check if CiviCRM classes are available
if (!class_exists('CRM_Core_Invoke')) {
    // Show placeholder when CiviCRM not available
    echo '<div style="padding: 40px; background: #f9f9f9; border: 2px dashed #ccc; text-align: center; border-radius: 4px;">';
    echo '<p style="margin: 0; color: #666; font-size: 16px;">📋 CiviCRM membership form will appear here</p>';
    echo '<p style="margin: 10px 0 0 0; color: #999; font-size: 14px;">(CiviCRM not implemented locally - form displays on staging/production)</p>';
    echo '</div>';
} else {
    // Use CiviCRM's page callback to render inline
    try {
        if (function_exists('civi_wp')) {
            civi_wp()->initialize();
        }

        // Capture the form output
        ob_start();
        CRM_Core_Invoke::invoke(['civicrm', 'member-signup']);
        $form_html = ob_get_clean();

        echo $form_html;
    } catch (Exception $e) {
        if (is_admin()) {
            echo '<p style="color: red;">Error loading form: ' . esc_html($e->getMessage()) . '</p>';
        }
    }
}

echo '</div>';
echo '</div>';
