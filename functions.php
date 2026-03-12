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
include_once get_template_directory() . '/inc/newsletter-form.php';

// Functionality.
require_once get_template_directory() . '/inc/blocks.php';
require_once get_template_directory() . '/inc/block-areas.php';
require_once get_template_directory() . '/inc/loop.php';
include_once get_template_directory() . '/inc/login-logo.php';
require_once get_template_directory() . '/inc/back-to-top.php';
require_once get_template_directory() . '/inc/user-roles.php';

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
 * Show admin bar only for specific user roles on the frontend
 */
function nwu_admin_bar_visibility() {
    if ( is_admin() ) {
        return; // Always show in WP admin
    }

    $roles_with_admin_bar = [
        'administrator',
        'chapter-chair',
        'grievance_contract',
        'monitor',
    ];

    $current_user = wp_get_current_user();

    if ( empty( $current_user->roles ) ) {
        show_admin_bar( false );
        return;
    }

    $has_role = array_intersect( $roles_with_admin_bar, $current_user->roles );

    if ( empty( $has_role ) ) {
        show_admin_bar( false );
    }
}
add_action( 'after_setup_theme', 'nwu_admin_bar_visibility' );

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
 * =============================================
 * TEMPORARY AUDIT TOOLS - REMOVE FOR PRODUCTION
 * =============================================
 *
 * Added: December 2024
 * Purpose: Content audit export tools
 * Remove before: Production launch
 *
 * Tools included:
 * - Export Pages CSV (Tools → Export Pages CSV)
 * - Export Page Content (Tools → Export Page Content)
 * - Export Plugins CSV (Tools → Export Plugins CSV)
 */


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


/**
 * ============================================
 * TEMPORARY - REMOVE BEFORE PRODUCTION DEPLOY
 * ============================================
 *
 * Export Page Content to HTML Archive
 * Added for content audit - December 2024
 *
 * Access via: Tools → Export Page Content
 *
 * @package NWU2025
 */

add_action('admin_menu', function() {
    add_management_page(
        'Export Page Content',
        'Export Page Content',
        'manage_options',
        'export-page-content',
        'nwu_export_page_content_page'
    );
});

function nwu_export_page_content_page() {
    // Get all pages for the selection list
    $pages = get_posts([
        'post_type' => 'page',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'post_status' => ['publish', 'draft', 'private'],
    ]);

    // Get unique parent pages for filter
    $parents = [];
    foreach ($pages as $page) {
        if ($page->post_parent && !isset($parents[$page->post_parent])) {
            $parent = get_post($page->post_parent);
            if ($parent) {
                $parents[$page->post_parent] = $parent->post_title;
            }
        }
    }
    asort($parents);

    ?>
    <div class="wrap">
        <h1>Export Page Content for Archive</h1>
        <p>Select the pages you want to export. Each page will be saved as an HTML file that can be opened in Word or Google Docs.</p>

        <form method="post">
            <?php wp_nonce_field('export_page_content', 'export_content_nonce'); ?>

            <!-- Tab Navigation -->
            <h2 class="nav-tab-wrapper">
                <a href="#tab-browse" class="nav-tab nav-tab-active" onclick="switchTab(event, 'tab-browse')">Browse & Select</a>
                <a href="#tab-ids" class="nav-tab" onclick="switchTab(event, 'tab-ids')">Paste Page IDs</a>
            </h2>

            <!-- Tab 1: Browse & Select -->
            <div id="tab-browse" class="tab-content" style="margin-top: 20px;">
                <h2>Search & Filter</h2>

                <!-- Row 1: Search, Status, Parent -->
                <div style="display: flex; gap: 15px; margin-bottom: 15px; flex-wrap: wrap;">
                    <div>
                        <label for="page-search"><strong>Search:</strong></label><br>
                        <input type="text" id="page-search" placeholder="Type to search..." style="width: 250px; padding: 5px;">
                    </div>

                    <div>
                        <label for="status-filter"><strong>Status:</strong></label><br>
                        <select id="status-filter" style="padding: 5px;">
                            <option value="">All Statuses</option>
                            <option value="publish">Published</option>
                            <option value="draft">Draft</option>
                            <option value="private">Private</option>
                        </select>
                    </div>

                    <div>
                        <label for="parent-filter"><strong>Parent Page:</strong></label><br>
                        <select id="parent-filter" style="padding: 5px;">
                            <option value="">All Pages</option>
                            <option value="top-level">Top Level Only</option>
                            <option value="children">Child Pages Only</option>
                            <?php foreach ($parents as $parent_id => $parent_title) : ?>
                                <option value="<?php echo $parent_id; ?>">Children of: <?php echo esc_html($parent_title); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Row 2: Date Filters -->
                <div style="display: flex; gap: 15px; margin-bottom: 15px; flex-wrap: wrap; align-items: flex-end;">
                    <div>
                        <label for="date-type"><strong>Date Type:</strong></label><br>
                        <select id="date-type" style="padding: 5px;">
                            <option value="modified">Last Modified</option>
                            <option value="created">Created</option>
                        </select>
                    </div>

                    <div>
                        <label for="date-from"><strong>From:</strong></label><br>
                        <input type="date" id="date-from" style="padding: 4px;">
                    </div>

                    <div>
                        <label for="date-to"><strong>To:</strong></label><br>
                        <input type="date" id="date-to" style="padding: 4px;">
                    </div>

                    <div>
                        <button type="button" class="button" onclick="clearDateFilters()">Clear Dates</button>
                    </div>

                    <div style="border-left: 1px solid #ccc; padding-left: 15px;">
                        <label><strong>Quick:</strong></label><br>
                        <select id="date-quick" style="padding: 5px;" onchange="applyQuickDate()">
                            <option value="">Choose...</option>
                            <option value="30">Last 30 days</option>
                            <option value="90">Last 90 days</option>
                            <option value="180">Last 6 months</option>
                            <option value="365">Last year</option>
                            <option value="older-1">Older than 1 year</option>
                            <option value="older-2">Older than 2 years</option>
                            <option value="older-3">Older than 3 years</option>
                        </select>
                    </div>
                </div>

                <p>
                    <button type="button" class="button" onclick="selectVisible()">Select All Visible</button>
                    <button type="button" class="button" onclick="deselectAll()">Deselect All</button>
                    <span id="selection-count" style="margin-left: 15px; color: #666;">0 pages selected</span>
                    <span id="visible-count" style="margin-left: 15px; color: #666;">(<?php echo count($pages); ?> showing)</span>
                </p>

                <div id="pages-list" style="max-height: 400px; overflow-y: auto; border: 1px solid #ccc; padding: 15px; background: #fff;">
                    <?php foreach ($pages as $page) :
                        $parent_title = $page->post_parent ? get_the_title($page->post_parent) : '';
                        $parent_display = $parent_title ? ' (Parent: ' . $parent_title . ')' : '';
                        $status_label = $page->post_status !== 'publish' ? ' [' . ucfirst($page->post_status) . ']' : '';

                        // Format dates for data attributes (YYYY-MM-DD)
                        $created_date = date('Y-m-d', strtotime($page->post_date));
                        $modified_date = date('Y-m-d', strtotime($page->post_modified));

                        // Format dates for display
                        $modified_display = date('M j, Y', strtotime($page->post_modified));
                    ?>
                        <label class="page-item"
                               data-title="<?php echo esc_attr(strtolower($page->post_title)); ?>"
                               data-status="<?php echo esc_attr($page->post_status); ?>"
                               data-parent="<?php echo esc_attr($page->post_parent); ?>"
                               data-id="<?php echo $page->ID; ?>"
                               data-created="<?php echo esc_attr($created_date); ?>"
                               data-modified="<?php echo esc_attr($modified_date); ?>"
                               style="display: block; padding: 8px 5px; border-bottom: 1px solid #eee;">
                            <input type="checkbox" name="page_ids[]" value="<?php echo $page->ID; ?>" onchange="updateCount()">
                            <strong><?php echo esc_html($page->post_title); ?></strong>
                            <span style="color: #666;"><?php echo esc_html($parent_display . $status_label); ?></span>
                            <span style="color: #999; font-size: 12px;"> — ID: <?php echo $page->ID; ?> — Modified: <?php echo $modified_display; ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Tab 2: Paste IDs -->
            <div id="tab-ids" class="tab-content" style="margin-top: 20px; display: none;">
                <h2>Paste Page IDs</h2>
                <p>Paste page IDs from your audit spreadsheet. One ID per line, or comma-separated.</p>

                <textarea id="page-ids-text" name="page_ids_text" rows="10" style="width: 100%; max-width: 400px; font-family: monospace;" placeholder="123
456
789

or

123, 456, 789"></textarea>

                <p style="color: #666; font-size: 13px;">
                    <strong>Tip:</strong> Copy the ID column from your spreadsheet and paste it here.
                </p>
            </div>

            <h2 style="margin-top: 20px;">Export Options</h2>

            <p>
                <label>
                    <input type="checkbox" name="include_meta" value="1" checked>
                    Include metadata (URL, date, author, etc.)
                </label>
            </p>
            <p>
                <label>
                    <input type="checkbox" name="include_featured" value="1" checked>
                    Include featured image
                </label>
            </p>
            <p>
                <label>
                    <input type="checkbox" name="include_acf" value="1">
                    Include ACF custom fields (if any)
                </label>
            </p>

            <p style="margin-top: 20px;">
                <input type="submit" name="export_content" class="button button-primary button-hero" value="Download Archive (ZIP)">
            </p>
        </form>
    </div>

    <script>
    function switchTab(event, tabId) {
        event.preventDefault();

        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(tab => tab.style.display = 'none');
        document.querySelectorAll('.nav-tab').forEach(tab => tab.classList.remove('nav-tab-active'));

        // Show selected tab
        document.getElementById(tabId).style.display = 'block';
        event.target.classList.add('nav-tab-active');
    }

    function filterPages() {
        const search = document.getElementById('page-search').value.toLowerCase();
        const status = document.getElementById('status-filter').value;
        const parent = document.getElementById('parent-filter').value;
        const dateType = document.getElementById('date-type').value;
        const dateFrom = document.getElementById('date-from').value;
        const dateTo = document.getElementById('date-to').value;

        let visibleCount = 0;

        document.querySelectorAll('.page-item').forEach(item => {
            const title = item.dataset.title;
            const itemStatus = item.dataset.status;
            const itemParent = item.dataset.parent;
            const itemCreated = item.dataset.created;
            const itemModified = item.dataset.modified;

            // Choose which date to filter by
            const itemDate = dateType === 'created' ? itemCreated : itemModified;

            let show = true;

            // Search filter
            if (search && !title.includes(search)) {
                show = false;
            }

            // Status filter
            if (status && itemStatus !== status) {
                show = false;
            }

            // Parent filter
            if (parent === 'top-level' && itemParent !== '0') {
                show = false;
            } else if (parent === 'children' && itemParent === '0') {
                show = false;
            } else if (parent && parent !== 'top-level' && parent !== 'children' && itemParent !== parent) {
                show = false;
            }

            // Date from filter
            if (dateFrom && itemDate < dateFrom) {
                show = false;
            }

            // Date to filter
            if (dateTo && itemDate > dateTo) {
                show = false;
            }

            item.style.display = show ? 'block' : 'none';
            if (show) visibleCount++;
        });

        document.getElementById('visible-count').textContent = '(' + visibleCount + ' showing)';
    }

    function clearDateFilters() {
        document.getElementById('date-from').value = '';
        document.getElementById('date-to').value = '';
        document.getElementById('date-quick').value = '';
        filterPages();
    }

    function applyQuickDate() {
        const quick = document.getElementById('date-quick').value;
        const today = new Date();

        let fromDate = '';
        let toDate = '';

        if (quick === '30' || quick === '90' || quick === '180' || quick === '365') {
            // Last X days
            const daysAgo = new Date(today);
            daysAgo.setDate(daysAgo.getDate() - parseInt(quick));
            fromDate = daysAgo.toISOString().split('T')[0];
            toDate = today.toISOString().split('T')[0];
        } else if (quick === 'older-1') {
            // Older than 1 year
            const oneYearAgo = new Date(today);
            oneYearAgo.setFullYear(oneYearAgo.getFullYear() - 1);
            toDate = oneYearAgo.toISOString().split('T')[0];
        } else if (quick === 'older-2') {
            // Older than 2 years
            const twoYearsAgo = new Date(today);
            twoYearsAgo.setFullYear(twoYearsAgo.getFullYear() - 2);
            toDate = twoYearsAgo.toISOString().split('T')[0];
        } else if (quick === 'older-3') {
            // Older than 3 years
            const threeYearsAgo = new Date(today);
            threeYearsAgo.setFullYear(threeYearsAgo.getFullYear() - 3);
            toDate = threeYearsAgo.toISOString().split('T')[0];
        }

        document.getElementById('date-from').value = fromDate;
        document.getElementById('date-to').value = toDate;
        filterPages();
    }

    function selectVisible() {
        document.querySelectorAll('.page-item').forEach(item => {
            if (item.style.display !== 'none') {
                item.querySelector('input[type="checkbox"]').checked = true;
            }
        });
        updateCount();
    }

    function deselectAll() {
        document.querySelectorAll('.page-item input[type="checkbox"]').forEach(cb => {
            cb.checked = false;
        });
        updateCount();
    }

    function updateCount() {
        const count = document.querySelectorAll('.page-item input[type="checkbox"]:checked').length;
        document.getElementById('selection-count').textContent = count + ' page' + (count !== 1 ? 's' : '') + ' selected';
    }

    // Attach filter events
    document.getElementById('page-search').addEventListener('input', filterPages);
    document.getElementById('status-filter').addEventListener('change', filterPages);
    document.getElementById('parent-filter').addEventListener('change', filterPages);
    document.getElementById('date-type').addEventListener('change', filterPages);
    document.getElementById('date-from').addEventListener('change', filterPages);
    document.getElementById('date-to').addEventListener('change', filterPages);

    // Initial count
    updateCount();
    </script>
    <?php
}

// Handle the export
add_action('admin_init', function() {
    if (!isset($_POST['export_content']) || !current_user_can('manage_options')) {
        return;
    }

    if (!wp_verify_nonce($_POST['export_content_nonce'], 'export_page_content')) {
        wp_die('Security check failed');
    }

    $page_ids = [];

    // Get IDs from checkboxes
    if (!empty($_POST['page_ids'])) {
        $page_ids = array_map('intval', $_POST['page_ids']);
    }

    // Get IDs from textarea (supports comma-separated or newline-separated)
    if (!empty($_POST['page_ids_text'])) {
        $text = sanitize_textarea_field($_POST['page_ids_text']);
        // Split by commas, newlines, or spaces
        $text_ids = preg_split('/[\s,]+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $text_ids = array_map('intval', $text_ids);
        $text_ids = array_filter($text_ids); // Remove zeros
        $page_ids = array_merge($page_ids, $text_ids);
    }

    // Remove duplicates
    $page_ids = array_unique($page_ids);

    if (empty($page_ids)) {
        wp_die('No pages selected. <a href="javascript:history.back()">Go back</a>');
    }

    $include_meta = isset($_POST['include_meta']);
    $include_featured = isset($_POST['include_featured']);
    $include_acf = isset($_POST['include_acf']);

    // Create temporary directory
    $upload_dir = wp_upload_dir();
    $temp_dir = $upload_dir['basedir'] . '/page-exports-' . time();
    wp_mkdir_p($temp_dir);

    // Track exported and not found
    $exported = 0;
    $not_found = [];

    // Generate HTML for each page
    foreach ($page_ids as $page_id) {
        $page = get_post($page_id);
        if (!$page || $page->post_type !== 'page') {
            $not_found[] = $page_id;
            continue;
        }

        $html = nwu_generate_page_html($page, $include_meta, $include_featured, $include_acf);

        // Create safe filename
        $filename = sanitize_file_name($page->post_name ?: 'page-' . $page_id) . '.html';
        file_put_contents($temp_dir . '/' . $filename, $html);
        $exported++;
    }

    if ($exported === 0) {
        wp_die('No valid pages found to export. IDs not found: ' . implode(', ', $not_found) . '<br><a href="javascript:history.back()">Go back</a>');
    }

    // Create ZIP file
    $zip_path = $upload_dir['basedir'] . '/nwu-page-archive-' . date('Y-m-d') . '.zip';
    $zip = new ZipArchive();

    if ($zip->open($zip_path, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
        $files = glob($temp_dir . '/*.html');
        foreach ($files as $file) {
            $zip->addFile($file, basename($file));
        }
        $zip->close();
    }

    // Clean up temp files
    array_map('unlink', glob($temp_dir . '/*'));
    rmdir($temp_dir);

    // Download ZIP
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="nwu-page-archive-' . date('Y-m-d') . '.zip"');
    header('Content-Length: ' . filesize($zip_path));
    readfile($zip_path);

    // Clean up ZIP
    unlink($zip_path);
    exit;
});

function nwu_generate_page_html($page, $include_meta = true, $include_featured = true, $include_acf = true) {
    $author = get_userdata($page->post_author);
    $content = apply_filters('the_content', $page->post_content);

    $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>' . esc_html($page->post_title) . '</title>
    <style>
        body {
            font-family: Georgia, "Times New Roman", serif;
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            line-height: 1.6;
            color: #333;
        }
        h1 {
            font-size: 28px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .meta-box {
            background: #f5f5f5;
            padding: 15px;
            margin-bottom: 30px;
            border-left: 4px solid #0073aa;
            font-size: 14px;
        }
        .meta-box p { margin: 5px 0; }
        .meta-label { font-weight: bold; }
        .featured-image {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
        }
        .acf-fields {
            background: #fff8e5;
            padding: 15px;
            margin-top: 30px;
            border-left: 4px solid #f0ad4e;
        }
        .acf-fields h3 { margin-top: 0; }
        img { max-width: 100%; height: auto; }
        .archive-notice {
            background: #d4edda;
            padding: 10px 15px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
            font-size: 12px;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="archive-notice">
        <strong>Archived Page</strong> — Exported from NWU website on ' . date('F j, Y') . '
    </div>

    <h1>' . esc_html($page->post_title) . '</h1>';

    if ($include_meta) {
        $html .= '
    <div class="meta-box">
        <p><span class="meta-label">Original URL:</span> ' . esc_url(get_permalink($page->ID)) . '</p>
        <p><span class="meta-label">Page ID:</span> ' . $page->ID . '</p>
        <p><span class="meta-label">Status:</span> ' . ucfirst($page->post_status) . '</p>
        <p><span class="meta-label">Author:</span> ' . ($author ? esc_html($author->display_name) : 'Unknown') . '</p>
        <p><span class="meta-label">Created:</span> ' . date('F j, Y', strtotime($page->post_date)) . '</p>
        <p><span class="meta-label">Last Modified:</span> ' . date('F j, Y', strtotime($page->post_modified)) . '</p>
        <p><span class="meta-label">Slug:</span> ' . esc_html($page->post_name) . '</p>';

        if ($page->post_parent) {
            $parent = get_post($page->post_parent);
            $html .= '
        <p><span class="meta-label">Parent Page:</span> ' . esc_html($parent->post_title) . '</p>';
        }

        $html .= '
    </div>';
    }

    if ($include_featured && has_post_thumbnail($page->ID)) {
        $featured_url = get_the_post_thumbnail_url($page->ID, 'large');
        $html .= '
    <img src="' . esc_url($featured_url) . '" alt="Featured Image" class="featured-image">';
    }

    $html .= '
    <div class="content">
        ' . $content . '
    </div>';

    // Include ACF fields if requested
    if ($include_acf && function_exists('get_fields')) {
        $fields = get_fields($page->ID);
        if (!empty($fields)) {
            $html .= '
    <div class="acf-fields">
        <h3>Custom Fields</h3>';
            foreach ($fields as $key => $value) {
                if (!empty($value) && !is_array($value)) {
                    $html .= '
        <p><span class="meta-label">' . esc_html($key) . ':</span> ' . esc_html($value) . '</p>';
                } elseif (is_array($value)) {
                    $html .= '
        <p><span class="meta-label">' . esc_html($key) . ':</span> [Complex data]</p>';
                }
            }
            $html .= '
    </div>';
        }
    }

    $html .= '
</body>
</html>';

    return $html;
}

/**
 * Enqueue dashboard calendar script
 */
function nwu_enqueue_dashboard_calendar_script() {
	// Check if file exists
	$script_path = get_theme_file_path( '/assets/js/dashboard-calendar.js' );

	if ( ! file_exists( $script_path ) ) {
		error_log( 'Calendar script file does not exist: ' . $script_path );
		return;
	}

	wp_enqueue_script(
		'nwu-dashboard-calendar',
		get_theme_file_uri( '/assets/js/dashboard-calendar.js' ),
		array(), // No dependencies
		filemtime( $script_path ), // Use file modification time for cache busting
		true // Load in footer
	);

	wp_localize_script(
		'nwu-dashboard-calendar',
		'nwu_calendar',
		array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'calendar_navigation' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'nwu_enqueue_dashboard_calendar_script' );

/**
 * Generate calendar HTML
 */
function nwu_generate_calendar_html($month, $year, $show_past_events = false, $chapter_events_only = false) {
	// Get user's chapter if filtering by chapter
	$user_chapter = null;
	if ( $chapter_events_only ) {
		$user_id      = get_current_user_id();
		$user_chapter = get_user_meta( $user_id, 'nwu_user_chapter', true );
	}

	// Calculate first and last day of month
	$first_day = strtotime( "$year-$month-01" );
	$last_day  = strtotime( date( 'Y-m-t', $first_day ) );

	// Query events for this month
	$args = array(
		'post_type'      => 'events',
		'posts_per_page' => -1,
		'meta_query'     => array(
			array(
				'key'     => 'event_date',
				'value'   => array( date( 'Y-m-d', $first_day ), date( 'Y-m-d', $last_day ) ),
				'compare' => 'BETWEEN',
				'type'    => 'DATE',
			),
		),
		'orderby'        => 'meta_value',
		'order'          => 'ASC',
		'meta_key'       => 'event_date',
	);

	// Filter by chapter if needed
	if ( $chapter_events_only && ! empty( $user_chapter ) ) {
		$args['meta_query']['relation'] = 'AND';
		$args['meta_query'][] = array(
			'relation' => 'OR',
			array(
				'key'     => 'event_scope',
				'value'   => 'all',
				'compare' => '=',
			),
			array(
				'key'     => 'event_chapter',
				'value'   => $user_chapter,
				'compare' => '=',
			),
		);
	}

	$events_query = new WP_Query( $args );

	// Organize events by day
	$events_by_day = array();
	if ( $events_query->have_posts() ) {
		while ( $events_query->have_posts() ) {
			$events_query->the_post();
			$event_date = get_field( 'event_date' );
			if ( $event_date ) {
				$day = date( 'j', strtotime( $event_date ) );
				if ( ! isset( $events_by_day[ $day ] ) ) {
					$events_by_day[ $day ] = array();
				}
				$events_by_day[ $day ][] = get_the_ID();
			}
		}
		wp_reset_postdata();
	}

	// Calculate navigation URLs
	$prev_month = $month - 1;
	$prev_year  = $year;
	if ( $prev_month < 1 ) {
		$prev_month = 12;
		$prev_year--;
	}

	$next_month = $month + 1;
	$next_year  = $year;
	if ( $next_month > 12 ) {
		$next_month = 1;
		$next_year++;
	}

	$prev_url = add_query_arg( array( 'cal_month' => $prev_month, 'cal_year' => $prev_year ) );
	$next_url = add_query_arg( array( 'cal_month' => $next_month, 'cal_year' => $next_year ) );

	// Get calendar grid data
	$month_start_day = date( 'w', $first_day );
	$days_in_month   = date( 't', $first_day );
	$today_day       = ( date( 'n' ) == $month && date( 'Y' ) == $year ) ? date( 'j' ) : 0;

	// Start building HTML
	ob_start();
	?>
	<div class="calendar-wrapper">
		<div class="calendar-header">
			<h3><?php echo esc_html( date( 'F Y', $first_day ) ); ?></h3>

			<div class="calendar-nav">
				<a href="<?php echo esc_url( $prev_url ); ?>" class="calendar-nav__prev" aria-label="<?php esc_attr_e( 'Previous Month', 'nwu-2025' ); ?>">
					<?php echo be_icon( array( 'icon' => 'chevron-large-left', 'size' => 24 ) ); ?>
				</a>
				<a href="<?php echo esc_url( $next_url ); ?>" class="calendar-nav__next" aria-label="<?php esc_attr_e( 'Next Month', 'nwu-2025' ); ?>">
					<?php echo be_icon( array( 'icon' => 'chevron-large-right', 'size' => 24 ) ); ?>
				</a>
			</div>
		</div>

		<div class="calendar-grid">
			<div class="calendar-day-header">Sun</div>
			<div class="calendar-day-header">Mon</div>
			<div class="calendar-day-header">Tue</div>
			<div class="calendar-day-header">Wed</div>
			<div class="calendar-day-header">Thu</div>
			<div class="calendar-day-header">Fri</div>
			<div class="calendar-day-header">Sat</div>

			<?php
			for ( $i = 0; $i < $month_start_day; $i++ ) {
				echo '<div class="calendar-day calendar-day--empty"></div>';
			}

			for ( $day = 1; $day <= $days_in_month; $day++ ) {
				$day_classes   = array( 'calendar-day' );
				$day_of_week   = date( 'w', strtotime( "$year-$month-$day" ) );
				$has_events    = isset( $events_by_day[ $day ] );
				$is_today      = ( $day == $today_day );

				if ( $is_today ) {
					$day_classes[] = 'calendar-day--today';
				}
				if ( $has_events ) {
					$day_classes[] = 'calendar-day--has-events';
				}
				if ( $day_of_week == 0 || $day_of_week == 6 ) {
					$day_classes[] = 'calendar-day--weekend';
				}

				echo '<div class="' . esc_attr( implode( ' ', $day_classes ) ) . '">';
				echo '<span class="calendar-day-number">' . esc_html( $day ) . '</span>';

				if ( $has_events ) {
					echo '<span class="calendar-event-dot"></span>';
				}

				echo '</div>';
			}
			?>
		</div>

		<div class="calendar-footer">
			<a href="<?php echo esc_url( get_post_type_archive_link( 'events' ) ); ?>" class="view-all-events">
				<?php esc_html_e( 'View All Events →', 'nwu-2025' ); ?>
			</a>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * AJAX handler for calendar month loading
 */
function nwu_load_calendar_month() {
	check_ajax_referer( 'calendar_navigation', 'nonce' );

	$month = isset( $_POST['month'] ) ? intval( $_POST['month'] ) : date( 'n' );
	$year  = isset( $_POST['year'] ) ? intval( $_POST['year'] ) : date( 'Y' );

	$html = nwu_generate_calendar_html($month, $year);

	wp_send_json_success( array( 'html' => $html ) );
}
add_action( 'wp_ajax_load_calendar_month', 'nwu_load_calendar_month' );
add_action( 'wp_ajax_nopriv_load_calendar_month', 'nwu_load_calendar_month' );

/**
 * Add page slug as body class
 */
add_filter( 'body_class', function( $classes ) {
	if ( is_singular() ) {
		$post = get_post();
		if ( $post && isset( $post->post_name ) ) {
			$classes[] = 'page-' . sanitize_html_class( $post->post_name );
		}
	}
	return $classes;
}, 9999 );


/**
 * ONE-TIME SCRIPT: Add Chapter Details block to existing chapters
 *
 * HOW TO USE:
 * 1. Add this code to functions.php
 * 2. Load any WordPress admin page
 * 3. You'll see a success notice
 * 4. Remove this entire function block from functions.php
 *
 * IMPORTANT: This modifies the database. Run it on each environment separately:
 * - Run on LOCAL, or
 * - Push code to STAGING and run there, or
 * - Do both separately
 *
 * Do NOT rely on Git to sync database changes!
 */
add_action( 'admin_init', function() {
	// Check if we've already run this script
	$option_key = 'nwu_chapter_details_block_added';

	if ( get_option( $option_key ) ) {
		return; // Already ran, exit
	}

	// Get all chapter posts (published, draft, private, etc.)
	$chapters = get_posts( [
		'post_type'      => 'chapter',
		'posts_per_page' => -1,
		'post_status'    => 'any', // Include all statuses
	] );

	if ( empty( $chapters ) ) {
		// No chapters found, mark as complete anyway
		update_option( $option_key, time() );
		return;
	}

	$updated_count = 0;
	$skipped_count = 0;

	foreach ( $chapters as $chapter ) {
		$content = $chapter->post_content;

		// Check if this chapter already has the chapter-details block
		if ( strpos( $content, 'wp:acf/chapter-details' ) !== false ) {
			$skipped_count++;
			continue; // Skip - already has it
		}

		// Create the Chapter Details block markup
		$chapter_details_block = '<!-- wp:acf/chapter-details /-->';

		// Prepend the block to existing content
		if ( ! empty( trim( $content ) ) ) {
			// Chapter has existing content - add block with spacing
			$new_content = $chapter_details_block . "\n\n" . $content;
		} else {
			// Chapter has no content - just add the block
			$new_content = $chapter_details_block;
		}

		// Update the chapter post
		$result = wp_update_post( [
			'ID'           => $chapter->ID,
			'post_content' => $new_content,
		], true ); // true = return WP_Error on failure

		if ( ! is_wp_error( $result ) ) {
			$updated_count++;
		}
	}

	// Mark as complete with timestamp
	update_option( $option_key, time() );

	// Show admin notice with results
	add_action( 'admin_notices', function() use ( $updated_count, $skipped_count ) {
		$message = '<strong>Chapter Details Block Script Complete!</strong><br>';
		$message .= 'Updated: ' . $updated_count . ' chapter(s)<br>';
		$message .= 'Skipped (already had block): ' . $skipped_count . ' chapter(s)<br>';
		$message .= '<em>You can now remove the one-time script from functions.php</em>';

		echo '<div class="notice notice-success is-dismissible">';
		echo '<p>' . $message . '</p>';
		echo '</div>';
	} );
}, 99 );

/**
 * Redirect non-admin users away from WordPress admin
 * Members should only access the front-end
 */
add_action( 'admin_init', 'nwu_restrict_admin_access' );
function nwu_restrict_admin_access() {
	// Allow admins, editors, authors, contributors
	if ( current_user_can( 'edit_posts' ) ) {
		return;
	}

	// Allow AJAX requests
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		return;
	}

	// Redirect members and subscribers to their account page
	wp_redirect( home_url( '/my-account/' ) ); // Update with your actual account page URL
	exit;
}

/**
 * Hide admin bar for members on front-end
 */
add_action( 'after_setup_theme', 'nwu_hide_admin_bar_for_members' );
function nwu_hide_admin_bar_for_members() {
	if ( ! current_user_can( 'edit_posts' ) ) {
		show_admin_bar( false );
	}
}
