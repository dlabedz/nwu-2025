<?php
/**
 * Custom Post Types
 *
 * @package      NWU2025
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

namespace NWU2025\Post_Types;

/**
 * Register Chapters CPT
 */
function register_chapters_cpt() {
	$labels = [
		'name'                  => __( 'Chapters', 'nwu-2025' ),
		'singular_name'         => __( 'Chapter', 'nwu-2025' ),
		'add_new'               => __( 'Add New', 'nwu-2025' ),
		'add_new_item'          => __( 'Add New Chapter', 'nwu-2025' ),
		'edit_item'             => __( 'Edit Chapter', 'nwu-2025' ),
		'new_item'              => __( 'New Chapter', 'nwu-2025' ),
		'view_item'             => __( 'View Chapter', 'nwu-2025' ),
		'search_items'          => __( 'Search Chapters', 'nwu-2025' ),
		'not_found'             => __( 'No Chapters found', 'nwu-2025' ),
		'not_found_in_trash'    => __( 'No Chapters found in Trash', 'nwu-2025' ),
		'parent_item_colon'     => __( 'Parent Chapter:', 'nwu-2025' ),
		'menu_name'             => __( 'Chapters', 'nwu-2025' ),
		'archives'              => __( 'Chapter Archives', 'nwu-2025' ),
		'insert_into_item'      => __( 'Insert into chapter', 'nwu-2025' ),
		'uploaded_to_this_item' => __( 'Uploaded to this chapter', 'nwu-2025' ),
		'filter_items_list'     => __( 'Filter chapters list', 'nwu-2025' ),
		'items_list_navigation' => __( 'Chapters list navigation', 'nwu-2025' ),
		'items_list'            => __( 'Chapters list', 'nwu-2025' ),
	];

	$args = [
		'labels'              => $labels,
		'description'         => __( 'NWU Chapters across regions', 'nwu-2025' ),
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'query_var'           => true,
		'rewrite'             => [ 'slug' => 'chapters', 'with_front' => false ],
		'capability_type'     => 'post',
		'has_archive'         => true,
		'hierarchical'        => false,
		'menu_position'       => 20, // After Pages
		'menu_icon'           => 'dashicons-groups',
		'supports'            => [ 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields' ],
		'show_in_rest'        => true,
		'rest_base'           => 'chapters',
	];

	register_post_type( 'chapter', $args );

	// Register Chapter Regions Taxonomy
	$tax_labels = [
		'name'              => __( 'Regions', 'nwu-2025' ),
		'singular_name'     => __( 'Region', 'nwu-2025' ),
		'search_items'      => __( 'Search Regions', 'nwu-2025' ),
		'all_items'         => __( 'All Regions', 'nwu-2025' ),
		'parent_item'       => __( 'Parent Region', 'nwu-2025' ),
		'parent_item_colon' => __( 'Parent Region:', 'nwu-2025' ),
		'edit_item'         => __( 'Edit Region', 'nwu-2025' ),
		'update_item'       => __( 'Update Region', 'nwu-2025' ),
		'add_new_item'      => __( 'Add New Region', 'nwu-2025' ),
		'new_item_name'     => __( 'New Region Name', 'nwu-2025' ),
		'menu_name'         => __( 'Regions', 'nwu-2025' ),
	];

	$tax_args = [
		'hierarchical'      => true,
		'labels'            => $tax_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => [ 'slug' => 'region', 'with_front' => false ],
		'show_in_rest'      => true,
		'rest_base'         => 'chapter-regions',
	];

	register_taxonomy( 'chapter_region', [ 'chapter' ], $tax_args );
}
add_action( 'init', __NAMESPACE__ . '\\register_chapters_cpt' );

/**
 * Register Events CPT
 */
function register_events_cpt() {
	$labels = [
		'name'                  => __( 'Events', 'nwu-2025' ),
		'singular_name'         => __( 'Event', 'nwu-2025' ),
		'add_new'               => __( 'Add New', 'nwu-2025' ),
		'add_new_item'          => __( 'Add New Event', 'nwu-2025' ),
		'edit_item'             => __( 'Edit Event', 'nwu-2025' ),
		'new_item'              => __( 'New Event', 'nwu-2025' ),
		'view_item'             => __( 'View Event', 'nwu-2025' ),
		'search_items'          => __( 'Search Events', 'nwu-2025' ),
		'not_found'             => __( 'No Events found', 'nwu-2025' ),
		'not_found_in_trash'    => __( 'No Events found in Trash', 'nwu-2025' ),
		'parent_item_colon'     => __( 'Parent Event:', 'nwu-2025' ),
		'menu_name'             => __( 'Events', 'nwu-2025' ),
		'archives'              => __( 'Event Archives', 'nwu-2025' ),
		'insert_into_item'      => __( 'Insert into event', 'nwu-2025' ),
		'uploaded_to_this_item' => __( 'Uploaded to this event', 'nwu-2025' ),
		'filter_items_list'     => __( 'Filter events list', 'nwu-2025' ),
		'items_list_navigation' => __( 'Events list navigation', 'nwu-2025' ),
		'items_list'            => __( 'Events list', 'nwu-2025' ),
	];

	$args = [
		'labels'              => $labels,
		'description'         => __( 'NWU Events', 'nwu-2025' ),
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'query_var'           => true,
		'rewrite'             => [ 'slug' => 'events', 'with_front' => false ],
		'capability_type'     => 'post',
		'has_archive'         => true,
		'hierarchical'        => false,
		'menu_position'       => 21, // Right after Chapters
		'menu_icon'           => 'dashicons-calendar-alt',
		'supports'            => [ 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields' ],
		'show_in_rest'        => true,
		'rest_base'           => 'events',
	];

	register_post_type( 'event', $args );

	// Register Event Access Taxonomy (Public vs Members Only)
	$access_labels = [
		'name'              => __( 'Event Access', 'nwu-2025' ),
		'singular_name'     => __( 'Access Level', 'nwu-2025' ),
		'search_items'      => __( 'Search Access Levels', 'nwu-2025' ),
		'all_items'         => __( 'All Access Levels', 'nwu-2025' ),
		'edit_item'         => __( 'Edit Access Level', 'nwu-2025' ),
		'update_item'       => __( 'Update Access Level', 'nwu-2025' ),
		'add_new_item'      => __( 'Add New Access Level', 'nwu-2025' ),
		'new_item_name'     => __( 'New Access Level Name', 'nwu-2025' ),
		'menu_name'         => __( 'Access Level', 'nwu-2025' ),
	];

	$access_args = [
		'hierarchical'      => false,
		'labels'            => $access_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => [ 'slug' => 'event-access', 'with_front' => false ],
		'show_in_rest'      => true,
		'rest_base'         => 'event-access',
		'meta_box_cb'       => 'post_categories_meta_box', // Radio buttons instead of checkboxes
	];

	register_taxonomy( 'event_access', [ 'event' ], $access_args );
}
add_action( 'init', __NAMESPACE__ . '\\register_events_cpt' );

/**
 * Add default Event Access terms
 * Creates "Public" and "Members Only" terms automatically
 */
function add_default_event_access_terms() {
	// Check if terms already exist
	if ( ! term_exists( 'public', 'event_access' ) ) {
		wp_insert_term(
			'Public',
			'event_access',
			[
				'slug'        => 'public',
				'description' => __( 'Open to everyone', 'nwu-2025' ),
			]
		);
	}

	if ( ! term_exists( 'members-only', 'event_access' ) ) {
		wp_insert_term(
			'Members Only',
			'event_access',
			[
				'slug'        => 'members-only',
				'description' => __( 'Restricted to NWU members', 'nwu-2025' ),
			]
		);
	}
}
add_action( 'init', __NAMESPACE__ . '\\add_default_event_access_terms', 20 );

/**
 * Make Event Access taxonomy single-select (radio buttons)
 * Ensures events can only be Public OR Members Only, not both
 */
function event_access_radio_buttons() {
	?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('#event_accesschecklist input, #event_accesschecklist-pop input').each(function() {
				$(this).attr('type', 'radio');
			});
		});
	</script>
	<?php
}
add_action( 'admin_footer-post.php', __NAMESPACE__ . '\\event_access_radio_buttons' );
add_action( 'admin_footer-post-new.php', __NAMESPACE__ . '\\event_access_radio_buttons' );

/**
 * Set default Event Access to "Public" for new events
 */
function set_default_event_access( $post_id, $post ) {
	// Only run for events
	if ( 'event' !== $post->post_type ) {
		return;
	}

	// Only set default on first save (not on updates)
	if ( 'auto-draft' !== $post->post_status && 'publish' !== $post->post_status ) {
		return;
	}

	// Check if event already has an access level
	$terms = wp_get_object_terms( $post_id, 'event_access' );
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		return; // Already has a term, don't override
	}

	// Set default to "public"
	wp_set_object_terms( $post_id, 'public', 'event_access' );
}
add_action( 'save_post', __NAMESPACE__ . '\\set_default_event_access', 10, 2 );

/**
 * Flush rewrite rules on theme activation
 * This ensures custom post type URLs work immediately
 */
function flush_rewrite_rules_on_activation() {
	register_chapters_cpt();
	register_events_cpt();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, __NAMESPACE__ . '\\flush_rewrite_rules_on_activation' );
