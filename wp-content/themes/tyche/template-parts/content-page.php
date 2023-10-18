<?php
/**
 * Template part for displaying pages.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Tyche
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ($title = get_the_title()) : ?>
	<div class="row">
		<div class="col-xs-12">
			<div class="m-header">
				<div>
					<h3><span><?php echo esc_html( $title ); ?></span></h3>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>
	<?php
	the_content();
	wp_link_pages(
		array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'tyche' ),
			'after'  => '</div>',
		)
	);
	?>
</article><!-- #post-## -->
