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
