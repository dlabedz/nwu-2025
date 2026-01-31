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

// Check if we're on local (no CiviCRM) or staging/production
if (class_exists('CRM_Core_Invoke')) {
    // Staging/Production: Embed the form via seamless iframe
    $form_url = home_url('/civicrm/member-signup');
    ?>
    <iframe
        src="<?php echo esc_url($form_url); ?>"
        id="civicrm-form-iframe"
        class="civicrm-form-iframe"
        width="100%"
        frameborder="0"
        scrolling="no"
        title="Membership Signup Form">
    </iframe>

    <script>
    // Auto-resize iframe to content height
    window.addEventListener('message', function(e) {
        if (e.data && e.data.frameHeight) {
            var iframe = document.getElementById('civicrm-form-iframe');
            if (iframe) {
                iframe.style.height = e.data.frameHeight + 'px';
            }
        }
    });

    // Fallback: Set initial height
    document.addEventListener('DOMContentLoaded', function() {
        var iframe = document.getElementById('civicrm-form-iframe');
        if (iframe) {
            iframe.style.height = '1400px'; // Default height
        }
    });
    </script>
    <?php
} else {
    // Local: Show placeholder
    ?>
    <div class="civicrm-placeholder">
        <p style="margin: 0 0 10px 0; font-size: 18px; color: #666;">📋 CiviCRM Membership Form</p>
        <p style="margin: 0; font-size: 14px; color: #999;">Form will display here on staging/production</p>
    </div>
    <?php
}

echo '</div>';
echo '</div>';
