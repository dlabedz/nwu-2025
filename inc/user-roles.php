<?php
/**
 * User Roles
 *
 * @package      NWU2025
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

namespace NWU2025;

/**
 * Remove unused roles from the user role dropdown in wp-admin.
 * Non-destructive — roles still exist but can't be assigned via UI.
 *
 * @param array $roles All registered roles.
 * @return array Filtered roles.
 */
function filter_editable_roles( $roles ) {
    $roles_to_hide = [ 'contributor', 'author', 'editor' ];

    foreach ( $roles_to_hide as $role ) {
        unset( $roles[ $role ] );
    }

    return $roles;
}
add_filter( 'editable_roles', __NAMESPACE__ . '\\filter_editable_roles' );

/**
 * Remove unused role filter links from wp-admin/users.php screen.
 *
 * @param array $views All view links.
 * @return array Filtered views.
 */
function filter_users_view_links( $views ) {
    $roles_to_hide = [ 'contributor', 'author', 'editor' ];

    foreach ( $roles_to_hide as $role ) {
        unset( $views[ $role ] );
    }

    return $views;
}
add_filter( 'views_users', __NAMESPACE__ . '\\filter_users_view_links' );
