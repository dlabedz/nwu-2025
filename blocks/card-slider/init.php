<?php
/**
 * Card Slider Block Init
 *
 * @package      NWU2025
 * @subpackage   blocks/card-slider
 * @author       Debbie Labedz
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

namespace NWU2025\Blocks\Card_Slider;

/**
 * Enqueue Swiper assets from CDN
 */
function enqueue_swiper_assets() {
    // Only enqueue if block is present on the page
    if (has_block('cwp/card-slider')) {
        // Swiper CSS from CDN
        wp_enqueue_style(
            'swiper-css',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
            [],
            '11.1.14'
        );

        // Swiper JS from CDN
        wp_enqueue_script(
            'swiper-js',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
            [],
            '11.1.14',
            true
        );
    }
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_swiper_assets');
add_action('admin_enqueue_scripts', __NAMESPACE__ . '\\enqueue_swiper_assets');
