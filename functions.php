<?php
/**
 * Functions
 *
 * @package      NWU2025
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

// Theme.
require_once get_template_directory() . '/inc/tha-theme-hooks.php';
require_once get_template_directory() . '/inc/layouts.php';
require_once get_template_directory() . '/inc/helper-functions.php';
require_once get_template_directory() . '/inc/wordpress-cleanup.php';
require_once get_template_directory() . '/inc/comments.php';
include_once get_template_directory() . '/inc/site-header.php';
include_once get_template_directory() . '/inc/site-footer.php';
include_once get_template_directory() . '/inc/archive-header.php';
include_once get_template_directory() . '/inc/archive-navigation.php';
include_once get_template_directory() . '/inc/template-tags.php';
require_once get_template_directory() . '/inc/post-types.php';
require_once get_template_directory() . '/inc/button-styles.php';
require_once get_template_directory() . '/inc/block-config.php';

// Functionality.
require_once get_template_directory() . '/inc/blocks.php';
require_once get_template_directory() . '/inc/block-areas.php';
require_once get_template_directory() . '/inc/loop.php';
include_once get_template_directory() . '/inc/login-logo.php';
require_once get_template_directory() . '/inc/back-to-top.php';

// Plugin Support.
require_once get_template_directory() . '/inc/acf.php';
require_once get_template_directory() . '/inc/wordpress-seo.php';
include_once get_template_directory() . '/inc/wpforms.php';

/**
 * Enqueue scripts and styles.
 */
function be_scripts() {

	wp_enqueue_script( 'theme-global', get_theme_file_uri( '/assets/js/global.js' ), [], filemtime( get_theme_file_path( '/assets/js/global.js' ) ), true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_style( 'theme-style', get_theme_file_uri( '/assets/css/main.css' ), array(), filemtime( get_theme_file_path( '/assets/css/main.css' ) ) );

}
add_action( 'wp_enqueue_scripts', 'be_scripts' );


/**
 * Enqueue Google Fonts
 */
function nwu_2025_google_fonts() {
    // Combine multiple fonts in one URL for better performance
    $google_fonts_url = 'https://fonts.googleapis.com/css2?' .
        'family=Oswald:wght@200..700&' .
        'family=DM+Mono:ital,wght@0,300;0,400;0,500;1,300;1,400;1,500&' .
        'family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&' .
        'display=swap';

    wp_enqueue_style( 'nwu-2025-google-fonts', $google_fonts_url, array(), null );
}
add_action( 'wp_enqueue_scripts', 'nwu_2025_google_fonts' );


/**
 * Gutenberg scripts and styles
 */
function be_gutenberg_scripts() {
	wp_enqueue_script( 'theme-editor', get_theme_file_uri( '/assets/js/editor.js' ), array( 'wp-blocks', 'wp-dom' ), filemtime( get_theme_file_path( '/assets/js/editor.js' ) ), true );
}
add_action( 'enqueue_block_editor_assets', 'be_gutenberg_scripts' );

if ( ! function_exists( 'be_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function be_setup() {
		/*
		 * Make theme available for translation.
		 */
		load_theme_textdomain( 'NWU2025_textdomain', get_template_directory() . '/languages' );

		// Editor Styles.
		add_theme_support( 'editor-styles' );
		add_editor_style( 'assets/css/editor-style.css' );

		// Admin Bar Styling.
		add_theme_support( 'admin-bar', array( 'callback' => '__return_false' ) );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Body open hook.
		add_theme_support( 'body-open' );

		// Remove block templates.
		remove_theme_support( 'block-templates' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/**
		 * Set the content width in pixels, based on the theme's design and stylesheet.
		 */
		$GLOBALS['content_width'] = apply_filters( 'be_content_width', 800 );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			[
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'script',
				'style',
			]
		);

		// Gutenberg.

		// -- Responsive embeds
		add_theme_support( 'responsive-embeds' );

	}

endif;
add_action( 'after_setup_theme', 'be_setup' );

/**
 * Template Hierarchy
 *
 * @param string $template Template.
 */
function be_template_hierarchy( $template ) {

	if ( is_search() ) {
		$template = get_query_template( 'archive' );
	}
	return $template;
}
add_filter( 'template_include', 'be_template_hierarchy' );


/**
 * Hide admin bar on frontend
 */
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
    if (!is_admin()) {
        show_admin_bar(false);
    }
}

/**
 * Register all navigation menus for NWU 2025 theme
 */
function nwu_2025_register_all_menus() {
    register_nav_menus(array(
        // Header menus
        'primary'           => __('Primary Navigation', 'nwu-2025'),
        'utility'           => __('Utility Menu (Top Bar)', 'nwu-2025'),

        // Footer menus
        'footer-primary'       => __('Footer Primary Links', 'nwu-2025'),
        'footer-secondary'  => __('Footer Secondary Links', 'nwu-2025'),

        // Social menus
        'social-footer'     => __('Social Links (Footer)', 'nwu-2025'),

        // Specialized menus
        'sidebar'           => __('Sidebar Navigation', 'nwu-2025'),
        'mobile'            => __('Mobile Menu', 'nwu-2025'),
        'member-dashboard'  => __('Member Dashboard Menu', 'nwu-2025'),

    ));
}
add_action('after_setup_theme', 'nwu_2025_register_all_menus');

/**
 * Add dynamic login/logout link to utility menu
 */
add_filter('wp_nav_menu_items', 'nwu2025_add_login_logout_link', 10, 2);
function nwu2025_add_login_logout_link($items, $args) {
    // Only add to utility menu
    if ($args->theme_location === 'utility') {
        if (is_user_logged_in()) {
            $items .= '<li class="menu-item menu-item-logout">';
            $items .= '<a href="' . esc_url(wp_logout_url(home_url())) . '">' . esc_html__('Logout', 'nwu-2025') . '</a>';
            $items .= '</li>';
        } else {
            $items .= '<li class="menu-item menu-item-login">';
            $items .= '<a href="' . esc_url(wp_login_url(get_permalink())) . '">' . esc_html__('Login', 'nwu-2025') . '</a>';
            $items .= '</li>';
        }
    }
    return $items;
}

/**
 * Remove default archive header
 */
remove_action( 'tha_header_after', 'be_archive_header', 16 );

/**
 * Register custom block styles
 */
function nwu_2025_register_block_styles() {

	// Group Block Styles
	register_block_style('core/group', [
		'name'  => 'contained',
		'label' => __('Contained', 'nwu-2025'),
	]);

	register_block_style('core/group', [
		'name'  => 'contained-left',
		'label' => __('Contained Left', 'nwu-2025'),
	]);

	register_block_style('core/group', [
		'name'  => 'contained-right',
		'label' => __('Contained Right', 'nwu-2025'),
	]);

	register_block_style('core/group', [
		'name'  => 'half-left',
		'label' => __('Half Width Left', 'nwu-2025'),
	]);

	register_block_style('core/group', [
		'name'  => 'half-right',
		'label' => __('Half Width Right', 'nwu-2025'),
	]);

	// Column Block Styles
	register_block_style('core/columns', [
		'name'  => 'constrained-content',
		'label' => __('Constrained Content', 'nwu-2025'),
	]);

	register_block_style('core/columns', [
		'name'  => 'no-padding',
		'label' => __('No Padding', 'nwu-2025'),
	]);

	// Cover Block Styles
	register_block_style('core/cover', [
		'name'  => 'contained-content',
		'label' => __('Contained Content', 'nwu-2025'),
	]);

	register_block_style('core/cover', [
		'name'  => 'split-left',
		'label' => __('Split Left', 'nwu-2025'),
	]);

	register_block_style('core/cover', [
		'name'  => 'split-right',
		'label' => __('Split Right', 'nwu-2025'),
	]);
}
add_action('init', 'nwu_2025_register_block_styles');

/**
 * Remove default block appender
 * Prevents automatic paragraph block insertion
 */
add_filter( 'block_editor_settings_all', function( $settings ) {
    // Disable the default block (paragraph) from auto-inserting
    $settings['__experimentalPreferredStyleVariations'] = array(
        'core/paragraph' => array()
    );

    return $settings;
}, 10, 1 );


/**
 * Export Plugins to CSV
 *
 * Access via: Tools → Export Plugins CSV
 *
 * @package NWU2025
 */

add_action('admin_menu', function() {
    add_management_page(
        'Export Plugins to CSV',
        'Export Plugins CSV',
        'manage_options',
        'export-plugins-csv',
        'nwu_export_plugins_csv_page'
    );
});

function nwu_export_plugins_csv_page() {
    ?>
    <div class="wrap">
        <h1>Export Plugins to CSV</h1>
        <p>Download a CSV of all installed plugins.</p>
        <form method="post">
            <?php wp_nonce_field('export_plugins_csv', 'export_plugins_nonce'); ?>
            <p>
                <input type="submit" name="export_plugins" class="button button-primary" value="Download CSV">
            </p>
        </form>
    </div>
    <?php
}

add_action('admin_init', function() {
    if (!isset($_POST['export_plugins']) || !current_user_can('manage_options')) {
        return;
    }

    if (!wp_verify_nonce($_POST['export_plugins_nonce'], 'export_plugins_csv')) {
        wp_die('Security check failed');
    }

    // Get all plugins
    if (!function_exists('get_plugins')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    $all_plugins = get_plugins();
    $active_plugins = get_option('active_plugins', []);

    // Set headers for CSV download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=nwu-plugins-' . date('Y-m-d') . '.csv');

    $output = fopen('php://output', 'w');

    // Add BOM for Excel
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

    // Header row
    $headers = [
        'Plugin Name',
        'Status',
        'Version',
        'Author',
        'Description',
        'Plugin URI',
        'Folder/File',
        'Requires WP',
        'Requires PHP',
        'Keep/Remove',
        'Notes',
    ];

    fputcsv($output, $headers);

    // Output each plugin
    foreach ($all_plugins as $plugin_file => $plugin_data) {
        $is_active = in_array($plugin_file, $active_plugins) ? 'Active' : 'Inactive';

        $row = [
            $plugin_data['Name'],
            $is_active,
            $plugin_data['Version'],
            strip_tags($plugin_data['Author']),
            strip_tags($plugin_data['Description']),
            $plugin_data['PluginURI'],
            $plugin_file,
            $plugin_data['RequiresWP'] ?: 'Not specified',
            $plugin_data['RequiresPHP'] ?: 'Not specified',
            '', // Keep/Remove - empty for team
            '', // Notes - empty for team
        ];

        fputcsv($output, $row);
    }

    fclose($output);
    exit;
});


/**
 * Export Pages to CSV
 *
 * Add to functions.php temporarily, run export, then remove.
 * Access via: Tools → Export Pages CSV
 *
 * @package NWU2025
 */

// Add admin menu item
add_action('admin_menu', function() {
    add_management_page(
        'Export Pages to CSV',
        'Export Pages CSV',
        'manage_options',
        'export-pages-csv',
        'nwu_export_pages_csv_page'
    );
});

function nwu_export_pages_csv_page() {
    ?>
    <div class="wrap">
        <h1>Export Pages to CSV</h1>
        <p>Click the button below to download a CSV of all pages with metadata.</p>
        <form method="post">
            <?php wp_nonce_field('export_pages_csv', 'export_nonce'); ?>
            <p>
                <label>
                    <input type="checkbox" name="include_drafts" value="1" checked>
                    Include drafts and private pages
                </label>
            </p>
            <p>
                <label>
                    <input type="checkbox" name="include_children" value="1" checked>
                    Show parent page hierarchy
                </label>
            </p>
            <p>
                <input type="submit" name="export_pages" class="button button-primary" value="Download CSV">
            </p>
        </form>
    </div>
    <?php
}

// Handle the export
add_action('admin_init', function() {
    if (!isset($_POST['export_pages']) || !current_user_can('manage_options')) {
        return;
    }

    if (!wp_verify_nonce($_POST['export_nonce'], 'export_pages_csv')) {
        wp_die('Security check failed');
    }

    $include_drafts = isset($_POST['include_drafts']);
    $include_children = isset($_POST['include_children']);

    // Query all pages
    $args = [
        'post_type' => 'page',
        'posts_per_page' => -1,
        'orderby' => 'menu_order title',
        'order' => 'ASC',
        'post_status' => $include_drafts ? ['publish', 'draft', 'private', 'pending'] : 'publish',
    ];

    $pages = get_posts($args);

    // Set headers for CSV download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=nwu-pages-export-' . date('Y-m-d') . '.csv');

    // Open output stream
    $output = fopen('php://output', 'w');

    // Add BOM for Excel UTF-8 compatibility
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

    // CSV header row
    $headers = [
        'ID',
        'Title',
        'Slug',
        'Status',
        'Parent Page',
        'Parent ID',
        'Hierarchy Level',
        'Full Path',
        'URL',
        'Author',
        'Date Created',
        'Last Modified',
        'Word Count',
        'Has Featured Image',
        'Template',
        'Menu Order',
        'Comments',
        'Action Needed',
        'Notes',
    ];

    fputcsv($output, $headers);

    // Helper function to get page depth
    function nwu_get_page_depth($page_id) {
        $depth = 0;
        $parent_id = wp_get_post_parent_id($page_id);
        while ($parent_id) {
            $depth++;
            $parent_id = wp_get_post_parent_id($parent_id);
        }
        return $depth;
    }

    // Helper function to get full hierarchy path
    function nwu_get_page_hierarchy_path($page_id) {
        $path = [];
        $current_id = $page_id;
        while ($current_id) {
            $page = get_post($current_id);
            array_unshift($path, $page->post_title);
            $current_id = $page->post_parent;
        }
        return implode(' → ', $path);
    }

    // Output each page
    foreach ($pages as $page) {
        $parent_title = '';
        $parent_id = '';

        if ($page->post_parent) {
            $parent = get_post($page->post_parent);
            $parent_title = $parent ? $parent->post_title : '';
            $parent_id = $page->post_parent;
        }

        $author = get_userdata($page->post_author);
        $word_count = str_word_count(strip_tags($page->post_content));
        $template = get_page_template_slug($page->ID);
        $has_featured = has_post_thumbnail($page->ID) ? 'Yes' : 'No';

        $row = [
            $page->ID,
            $page->post_title,
            $page->post_name,
            $page->post_status,
            $parent_title,
            $parent_id,
            nwu_get_page_depth($page->ID),
            $include_children ? nwu_get_page_hierarchy_path($page->ID) : $page->post_title,
            get_permalink($page->ID),
            $author ? $author->display_name : '',
            $page->post_date,
            $page->post_modified,
            $word_count,
            $has_featured,
            $template ?: 'Default',
            $page->menu_order,
            $page->comment_count,
            '', // Action Needed - empty for team to fill
            '', // Notes - empty for team to fill
        ];

        fputcsv($output, $row);
    }

    fclose($output);
    exit;
});

