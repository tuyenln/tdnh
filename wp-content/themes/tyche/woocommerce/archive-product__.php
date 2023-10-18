<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function wpa_107952_featured () {
	if( !is_product_category() && !is_shop() ) {
		return;
	}
	$args = array(
		'status' => 'publish',
		'featured' => 'yes',
		'limit' => 8,
		'orderby'  => array(
			'ID' => 'DESC',
		),
	);
	$product_cat = get_query_var('product_cat');
	if ($product_cat) {
		$args['product_cat'] = $product_cat;
	}
	$output = '';
	$products = wc_get_products( $args );
	if ( wc_get_loop_prop( 'total' ) ) {
		foreach ($products as $p) {
			$id = $p->get_id();
			$data = get_post($id);
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), array(255, 204), true  );
			$image = count($image) ? $image[0]: '';
			$link = get_permalink($id);
			$output .= <<<EOT
			<div class="m-cell col-sm-3 col-xs-6">
			<a href="{$link}" class="m-image" style="background-image:url({$image})"></a>
			<div class="m-cell-line"></div>
			<a href="{$link}" class="m-link">{$data->post_title}</a>
			</div>
EOT;
	// 		//<div class="m-cell-line"></div>
	// 		//<a href="{$link}" class="m-btn">Xem thêm</a>
		}
		echo '<div class="col-xs-12"><div class="m-header"><div><h3><span>SẢN PHẨM TIÊU BIỂU</span></h3></div></div></div><div class="woocommerce">' . $output . '</div><div class="clearfix"></div>';
	}
	recent_products();
}

function recent_products() {
	$args = array(
		'status' => 'publish',
		'limit' => 8,
		'orderby'  => array(
			'ID' => 'DESC',
		),
	);
	$product_cat = get_query_var('product_cat');
	if ($product_cat) {
		$args['product_cat'] = $product_cat;
	}
	$output = '';
	$products = wc_get_products( $args );
	if ( wc_get_loop_prop( 'total' ) ) {
		foreach ($products as $p) {
			$id = $p->get_id();
			$data = get_post($id);
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), array(255, 204), true  );
			$image = count($image) ? $image[0]: '';
			$link = get_permalink($id);
			$output .= <<<EOT
			<div class="m-cell col-sm-3 col-xs-6">
			<a href="{$link}" class="m-image" style="background-image:url({$image})"></a>
			<div class="m-cell-line"></div>
			<a href="{$link}" class="m-link">{$data->post_title}</a>
			</div>
EOT;
			//<a href="{$link}" class="m-btn">Xem thêm</a>
		}
		echo '<div class="col-xs-12"><div class="m-header"><div><h3><span>SẢN PHẨM MỚI</span></h3></div></div></div><div class="woocommerce">' . $output . '</div><div class="clearfix"></div>';
	}
}

add_action( 'woocommerce_after_main_content', 'wpa_107952_featured' );

$layout = get_theme_mod( 'tyche_shop_layout', 'fullwidth' );

get_header( 'shop' ); ?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<?php
			/**
			 * woocommerce_before_main_content hook.
			 *
			 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
			 * @hooked woocommerce_breadcrumb - 20
			 * @hooked WC_Structured_Data::generate_website_data() - 30
			 */
			do_action( 'woocommerce_before_main_content' );
			?>
		</div>
	</div>
	<div class="row">
		<?php if ( is_active_sidebar( 'shop-sidebar' ) && 'left' === $layout ) : ?>
			<div class="col-md-4 hidden-xs">
				<?php
				/**
				 * woocommerce_sidebar hook.
				 *
				 * @hooked woocommerce_get_sidebar - 10
				 */
				do_action( 'woocommerce_sidebar' );
				?>
			</div>
		<?php endif; ?>
		<div class="<?php echo ( is_active_sidebar( 'shop-sidebar' ) && 'fullwidth' !== $layout ) ? 'col-md-8 tyche-has-sidebar' : 'col-md-12'; ?>">
			<?php
			/**
			 * woocommerce_archive_description hook.
			 *
			 * @hooked woocommerce_taxonomy_archive_description - 10
			 * @hooked woocommerce_product_archive_description - 10
			 */
			do_action( 'woocommerce_archive_description' );
			?>

			<?php   if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>

				<?php // @TB change header categroy page  SHOP PAGE ?>
				<?php
				/*
				<h1 class="woocommerce-products-header__title page-title"></h1>
				*/
				?>
				<div class="col-xs-12">
					<div class="m-header">
						<div>
							<h3><span><?php is_shop()  ? print('SẢN PHẨM') : woocommerce_page_title(); ?>1323</span></h3>
						</div>
					</div>
				</div>

			<?php endif; ?>

			<?php if ( have_posts() ) : ?>

				<?php
				/**
				 * woocommerce_before_shop_loop hook.
				 *
				 * @hooked wc_print_notices - 10
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				do_action( 'woocommerce_before_shop_loop' );
				?>

				<?php echo '<div class="woocommerce">'; //woocommerce_product_loop_start(); ?>

				<?php
				if ( tyche_get_loop_prop( 'total' ) ) {
					while ( have_posts() ) {
						the_post();

						/**
						 * Hook: woocommerce_shop_loop.
						 *
						 * @hooked WC_Structured_Data::generate_product_data() - 10
						 */
						do_action( 'woocommerce_shop_loop' );
						global $product;
						$id = $product->get_id();
						$image = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), array(255, 204), true  );
						$image = count($image) ? $image[0]: '';
						$link = get_permalink($id);
						echo <<<EOT
						<div class="m-cell col-sm-3 col-xs-6">
						<a href="{$link}" class="m-image" style="background-image:url({$image})"></a>
						<div class="m-cell-line"></div>
						<a href="{$link}" class="m-link">{$product->get_title()}</a>
						</div>
EOT;
// 						wc_get_template_part( 'content', 'product' );
					}
				}
				echo '</div>';
// 				woocommerce_product_loop_end();
				?>

				<?php
				/**
				 * woocommerce_after_shop_loop hook.
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
				?>

			<?php else : ?>

				<?php
				/**
				 * woocommerce_no_products_found hook.
				 *
				 * @hooked wc_no_products_found - 10
				 */
				do_action( 'woocommerce_no_products_found' );
				?>

			<?php endif;  ?>
		</div>
		<?php if ( is_active_sidebar( 'shop-sidebar' ) && 'right' === $layout ) : ?>
			<div class="col-md-4 hidden-xs">
				<?php
				/**
				 * woocommerce_sidebar hook.
				 *
				 * @hooked woocommerce_get_sidebar - 10
				 */
				do_action( 'woocommerce_sidebar' );
				?>
			</div>
		<?php endif; ?>
		<?php
		/**
		 * woocommerce_after_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
		?>
	</div>
</div>
<?php
// global $wp_query;
// $terms = get_the_terms( $wp_query->queried_object->ID, 'product_cat' );
// if (count($terms) <= 0) {
// 	return;
// }
// $args = array(
// 	'post_type' => 'product',
// 	'posts_per_page' => 8,
// 	'product_cat' => $terms[0]->slug,
// 	'orderby' => 'rand'
// );
// $loop = new WP_Query( $args );
// while ( $loop->have_posts() ) {
// 	$loop->the_post();
// }

?>
<!-- <div class="container">
	<div class="row">
		<div class="row">
	</div>
</div> -->
<?php get_footer( 'shop' ); ?>
