<?php
/**
 * Template Name: Contact Page Template
 *
 * @package Tyche
 */
?>

<?php
get_header();

$breadcrumbs_enabled = get_theme_mod( 'tyche_enable_post_breadcrumbs', true );
if ( $breadcrumbs_enabled ) {
	?>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<?php Tyche_Helper::add_breadcrumbs(); ?>
			</div>
		</div>
	</div>
<?php } ?>

<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<div class="m-header">
				<div>
					<h3><span><?php echo esc_html( get_the_title() ); ?></span></h3>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6">
			
		</div>
		<div class="col-sm-6">
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<div class="tyche-icon-box">
						<div class="icon">
							<span class="fa fa-mobile"></span>
						</div>
						<div class="text">
							<?php echo esc_html__( 'HOTLINE', 'tyche' ); ?>
							<span><?php 
								$custom_hotline = esc_html( get_theme_mod( 'tyche_contact_phone', '' ) );
								if ($custom_hotline) {
									echo '<span><a href="tel:'.$custom_hotline.'">'.$custom_hotline.'</a></span>';
								}?></span>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6">
					<div class="tyche-icon-box">
						<div class="icon">
							<span class="fa fa-envelope-o"></span>
						</div>
						<div class="text">
							<?php echo esc_html__( 'EMAIL', 'tyche' ); ?>
							<span><?php 
								$custom_email = esc_html( get_theme_mod( 'tyche_email', '' ) );
								if ($custom_email) {
									echo '<span><a href="mailto:'.$custom_email.'">'.$custom_email.'</a></span>';
								}?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<hr/>
	<div class="row">
		<div class="col-sm-4">
			<?php //echo do_shortcode( get_theme_mod( 'tyche_contact_page_shortcode_form', '' ) ); ?>
			<?php
			if ( have_posts() ) :
				while ( have_posts() ) :
					the_post();
					the_content();
				endwhile;
			endif;
			?>
		</div>
		<div class="col-sm-8">
			<div id="tyche-map">
				<?php echo do_shortcode( get_theme_mod( 'tyche_contact_page_shortcode_map', '' ) ); ?>
			</div>
		</div>
	</div>

</div>
<?php get_footer(); ?>
