<?php
/**
 * Member Access Gating
 * Checks the "Members Only" ACF field on pages and restricts
 * content to users with the WordPress "member" role.
 * Role is kept in sync with CiviCRM membership via nwu-civicrm-integration plugin.
 */

namespace NWU2025;

/**
 * Check if the current page is members-only.
 * Returns true if the gate should be shown.
 */
function nwu_is_members_only_page(): bool {
    if ( ! function_exists( 'get_field' ) ) {
        return false;
    }
    return (bool) get_field( 'members_only' );
}

/**
 * Check if the current user has an active member role.
 * Also returns true for editors/admins so they can preview gated content.
 */
function nwu_current_user_is_member(): bool {
    if ( ! is_user_logged_in() ) {
        return false;
    }
    $user = wp_get_current_user();
    $allowed_roles = [ 'member', 'editor', 'administrator' ];
    return (bool) array_intersect( $allowed_roles, (array) $user->roles );
}

/**
 * Render the members-only gate message.
 */
function nwu_render_members_only_message(): void {
    ?>
    <div class="members-only-gate">
        <div class="members-only-gate__inner">
            <p class="members-only-gate__eyebrow">Members Only</p>
            <h2 class="members-only-gate__heading">This page is available to NWU members.</h2>
            <p class="members-only-gate__body">Join the National Writers Union to access this page and all member benefits.</p>
            <div class="members-only-gate__actions">
                <?php if ( ! is_user_logged_in() ) : ?>
                    <a href="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>" class="btn-default">
                        Log In
                    </a>
                <?php endif; ?>
                <a href="<?php echo esc_url( home_url( '/membership/' ) ); ?>" class="btn-default">
                    Join NWU
                </a>
            </div>
        </div>
    </div>
    <?php
}
