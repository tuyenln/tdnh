<?php
/**
 * Tyche functions and definitions.
 *
 * @link    https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Tyche
 */

/**
 * Start Tyche theme framework
 */
require_once 'inc/class-tyche-autoloader.php';
$tyche = new Tyche();

include(WP_CONTENT_DIR . '/my_custom_shortcodes.php');
add_shortcode('view_post', 'tb_view_post');
add_shortcode('home_news_list', 'tb_home_news_list');
add_shortcode('home_product_category', 'tb_home_product_category');
//add_shortcode('feature_products', 'm_feature_products');

function theme_js_script() {
    wp_enqueue_script( 'theme-script', get_template_directory_uri() . '/assets/js/aos.js');
    }
add_action('wp_enqueue_scripts', 'theme_js_script');
