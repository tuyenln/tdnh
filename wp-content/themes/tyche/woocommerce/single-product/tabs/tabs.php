<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );
if ( ! empty( $tabs ) ) : ?>
<div class="col-sm-8 col-xs-12">
	<div class="woocommerce-tabs wc-tabs-wrapper">
		<ul class="tabs wc-tabs tyche-wc-tabs" role="tablist">
			<?php foreach ( $tabs as $key => $tab ) : ?>
				<li class="<?php echo esc_attr( $key ); ?>_tab" id="tab-title-<?php echo esc_attr( $key ); ?>"
					role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
					<a href="#tab-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $tab['title'], $key ) ); ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php foreach ( $tabs as $key => $tab ) : ?>
			<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content wc-tab" id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
				<?php call_user_func( $tab['callback'], $key, $tab ); ?>
			</div>
		<?php endforeach; ?>
	</div>
</div>
<?php // @TB add news ?>
<!-- <div class="col-sm-4 hidden-xs"> -->
	<?php get_sidebar('shop'); ?>
<!-- </div> -->
<?php endif; ?>

<?php
global $wp_query;
$terms = get_the_terms( $wp_query->queried_object->ID, 'product_cat' );
if (count($terms) <= 0) {
	return;
}
$args = array(
	'post_type' => 'product',
	'posts_per_page' => 8,
	'product_cat' => $terms[0]->slug,
	'orderby' => 'rand'
);
$loop = new WP_Query( $args );
while ( $loop->have_posts() ) {
	$loop->the_post();
}

return;


// foreach ($terms as $term) {

// 	 $args = array(
// 		'post_status' => 'publish',
// 		'tax_query' => array(
// 			'taxonomy' => 'product_cat',
// 			'field'    => 'term_id',
// 			'terms'     =>   $term->term_id,
// 			'operator'  => 'IN'
// 			)
// 		);
// //     $the_query = wp_query($args);
// // $args = array( 'post_type' => 'product', 'stock' => 1, 'posts_per_page' => 2,'product_cat' => $term->term_id, 'orderby' =>'date','order' => 'ASC' );
//   $loop = new WP_Query( $args );
// while ( $loop->have_posts() ) {
// 	$loop->the_post();
// 	var_dump($loop);
// }
// global $product;

	// $a = get_terms('product', array('parent' => $term->term_id, 'post_status' => 'publish', 'orderby' => 'ASC'));
	// var_dump($a);
    // $args = array(
	// 	'post_type'             => 'product',
	// 	'post_status'           => 'publish',
	// 	'ignore_sticky_posts'   => 1,
	// 	'posts_per_page'        => '12',
	// 	'tax_query'             => array(
	// 		array(
	// 			'taxonomy'      => 'product_cat',
	// 			'field' => 'term_id', //This is optional, as it defaults to 'term_id'
	// 			'terms'         => $term->term_id,
	// 			'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
	// 		),
	// 		array(
	// 			'taxonomy'      => 'product_visibility',
	// 			'field'         => 'slug',
	// 			'terms'         => 'exclude-from-catalog', // Possibly 'exclude-from-search' too
	// 			'operator'      => 'NOT IN'
	// 		)
	// 	)
	// );
	// $products_list[] = new WP_Query($args);
// }
?>
	<div class="col-xs-12">
		<div class="m-header">
			<div>
				<h3><span>SẢN PHẨM CÙNG LOẠI</span></h3>
			</div>
		</div>
	</div>
	<div class="col-sm-12 col-xs-12">
<div class="col-sm-4">
</div>
</div>
