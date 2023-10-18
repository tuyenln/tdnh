<?php

function tb_view_post($atts) {
	$id = null;
	$load_more = null;
	extract(shortcode_atts(array(
		'id' => $id,
		'load_more' => $load_more
	), $atts));

	$filter = array( 'post_type' => 'page' );
	//$filter[is_numeric($id) ? 'id' : 'name'] = $id;
	if (empty($load_more)) {
		$load_more = $id;
	}
	if (is_numeric($id)) {
		// $link = get_permalink( $id );
		$filter['p'] = $id;
	} else {
		// $link = home_url( '/'.$id.'/' );
		// $link = get_permalink( get_page_by_path( $id )->ID );
		$filter['name'] = $id;
	}
	if (is_numeric($load_more)) {
		$link = get_permalink( $load_more );
	} else {
		$link = home_url( '/'.$load_more.'/' );
	}

	ob_start();
	$recent = new WP_Query($filter);
	while($recent->have_posts()) {
		$recent->the_post();
		the_content();
	}
	$output = ob_get_contents();
	ob_end_clean();
	wp_reset_postdata();

	$output .= '<div class="clearfix"></div><div class="m-load-more">
	<a href="' . $link . '">Load more →</a>
	</div>';
	return $output;
}

function tb_home_news_list($atts) {
	$news_id = null;
	$id = null;
	extract(shortcode_atts(array(
		'id' => $id,
		'news_id' => $news_id
	), $atts));
	$args = array(
		'category' => $id,
		'numberposts' => 5,
		'post_type' => 'post',
		'post_status' => 'publish',
		// 'category_name' => 'news',
		'suppress_filters' => true );
	$recent_posts = wp_get_recent_posts($args);
    $output = '<div id="news_home" class="row aos-all">
		<div class="col-sm-6 aos-item" data-aos="fade-left">
			<div class="m-news-cell col-xs-12">
				<a href="' . get_permalink( $recent_posts[0]['ID']) . '" class="m-news-image" style="background-image:url(' . wp_get_attachment_url( get_post_thumbnail_id($recent_posts[0]['ID']) ) . ')"></a>
				<div class="m-news-name">' . $recent_posts[0]['post_title'] . '
				</div>
			</div>
		</div>
		<div class="col-sm-6 aos-item" data-aos="fade-right">';
	unset($recent_posts[0]);
	foreach( $recent_posts as $recent ) {
		$link = get_permalink($recent['ID']);
	$output .= '<div class="visible-xs"><div class="clearfix"></div><div style="height:20px"></div></div><div class="col-sm-6">
				<div class="m-news-cell">
					<a href="' . $link . '" class="m-news-image" style="background-image:url(' . wp_get_attachment_url( get_post_thumbnail_id($recent['ID']) ) . ')"></a>
					<div class="m-news-name">'  . $recent['post_title'] . '</div>
				</div>
			</div>';
	}
	$output .=	'</div>
	</div>
	<div class="m-load-more">
		<a href="'. get_permalink( $news_id ) .'">Load more →</a>
	</div>';
	wp_reset_postdata();
	return $output;
}

function tb_home_product_category($atts) {
	$id = null;
	extract(shortcode_atts(array(
		'id' => $id
	), $atts));
	$catTerms = get_terms('product_cat', array('parent' => $id, 'hide_empty' => 0, 'orderby' => 'ASC'));
	$output  = '';
	foreach($catTerms as $catTerm) {
		$thumbnail_id = get_woocommerce_term_meta($catTerm->term_id, 'thumbnail_id', true);
		// $image = wp_get_attachment_url($thumbnail_id);
		$image = wp_get_attachment_image_src( $thumbnail_id, array(255, 204), true  );
		$image = count($image) ? $image[0]: '';
		$link =  get_term_link( $catTerm->term_id ,'product_cat');
		$output .= '<div class="m-cell col-sm-3 col-xs-6">'.
		'<a href="' . $link . '" class="m-image" style="background-image:url(' . $image . ')"></a>
		<div class="m-cell-line"></div>
		<a href="' . $link . '" class="m-link">' . $catTerm->name . '</a>
		</div>';
	}
	// <a href="' . $link . '" class="m-btn">Xem thêm</a>
	return $output;
}
// include(WP_CONTENT_DIR . '/my_custom_shortcodes.php');
// add_shortcode('view_post', 'tb_view_post');
function tb_feature_products () {
	if( !is_product_category() ) {
		return;
	}

    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'ignore_sticky_posts' => 1,
		'posts_per_page' => 8,
        'meta_query' => array(
            array(
                'key' => '_visibility',
                'value' => array('catalog', 'visible'),
                'compare' => 'IN'
            ),
            array(
                'key' => '_featured',
                'value' => 'yes'
            )
		)
	);
	$product_cat = get_query_var('product_cat');
	if ($product_cat) {
		$args['product_cat'] = $product_cat;
	}
    ob_start();
	$products = new WP_Query( $args );
    $woocommerce_loop['columns'] = 4;
    if ( $products->have_posts() )  {
        woocommerce_product_loop_start();
            while ( $products->have_posts() ) {
				 $products->the_post();
                wc_get_template_part( 'content', 'product' );
			} // end of the loop.
        woocommerce_product_loop_end();
	}
    $output = '<div class="col-xs-12"><div class="m-header"><div><h3><span>SẢN PHẨM TIÊU BIỂU</span></h3></div></div></div><div class="woocommerce">' . ob_get_clean() . '</div>';
	wp_reset_postdata();
	return $output;
}
