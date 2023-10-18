<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link    https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Tyche
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta property="og:url" content="http://tapdoanngochung.vn" />
	<meta property="og:title" content="TAPDOANNGOCHUNG" />
	<meta property="og:description" content="Công ty TNHH Thiết Bị Điện Máy Ngọc Hùng" />
	<meta property="og:image" content="http://tapdoanngochung.vn/wp-content/uploads/2018/05/cropped-logo_NH_Artboard-1.png" />
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> -->
	<meta property="fb:app_id" content="276035396658377" />
<!-- 	<meta property="fb:admins" content="100001899951883"/> -->
	<html prefix="og: http://ogp.me/ns#">

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<!-- @TP add FB Plugin -->
<div id="fb-root"></div>
	<script type="text/javascript" language="javaScript">
    (function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=276035396658377";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
		</script>
<!-- <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2&appId=276035396658377&autoLogAppEvents=1"></script> -->
	
<div id="page" class="site">
<?php // @TB: ?>
<div id="header_contact">
	<div class="container">
		<div class="col-sm-12 m-red-line">
			<div class="m-logo">
			<?php
			$custom_logo_id = get_theme_mod( 'custom_logo' );
			
			// We have a logo. Logo is go.
			if ( $custom_logo_id ) {
				$custom_logo_attr = array(
				'class'    => 'custom-logo',
				'itemprop' => 'logo',
				);

				/*
				* If the logo alt attribute is empty, get the site title and explicitly
				* pass it to the attributes used by wp_get_attachment_image().
				*/
				$image_alt = get_post_meta( $custom_logo_id, '_wp_attachment_image_alt', true );
				if ( empty( $image_alt ) ) {
				$custom_logo_attr['alt'] = get_bloginfo( 'name', 'display' );
				}

				/*
				* If the alt attribute is not empty, there's no need to explicitly pass
				* it because wp_get_attachment_image() already adds the alt attribute.
				*/
				$logo = sprintf( '<a href="%1$s" class="custom-logo-link" rel="home" itemprop="url">%2$s</a>',
					esc_url( home_url( '/' ) ),
					wp_get_attachment_image( $custom_logo_id, 'full', false, $custom_logo_attr )
					);
				echo $logo;
			}
?>
			</div>
			<div class="m-site-title">
				TẬP ĐOÀN NGỌC HÙNG
			</div>
			<div class="clearfix"></div>
			<?php /*
			<div class="pull-right">
				<span class="pull-left" style="font-size:2em;margin-right:5px;margin-top:5px;">
				<i class="fa fa-mobile" aria-hidden="true"></i>
				</span>
				<span class="pull-left"  style="margin-top:5px;">
				<span style="font-size:.8em;line-height:1em;">HOTLINE</span><br>
				<span style="font-size:.6em;line-height:1em;"><?php
			$custom_hotline = get_customs('hotline');
			if (count($custom_hotline) > 0) {
				echo '<a href="tel:'.$custom_hotline[0].'">'.$custom_hotline[0].'</a>';
			}
		?></span>
				</span>
				<span class="pull-left" style="margin-left:10px;font-size:1.6em;margin-right:5px;margin-top:5px;">
				<i class="fa fa-envelope-o" aria-hidden="true"></i>
				</span>
				<span class="pull-left"  style="margin-top:5px;">
				<span style="font-size:.8em;line-height:1em;">HỘP THƯ</span><br>
				<span style="font-size:.6em;line-height:1em;"><?php
			$custom_email = get_customs('email');
			if (count($custom_email) > 0) {
				echo '<a href="mailto:'.$custom_email[0].'">'.$custom_email[0].'</a>';
			}
		?></span>
				</span>
			</div>
			*/ ?>
		</div>
	</div>
</div>
<?php // @TB end ?>
	<?php
	/**
	 * Enable / Disable the top bar
	 */
	if ( get_theme_mod( 'tyche_enable_top_bar', true ) ) :
		get_template_part( 'template-parts/top-header' );
	endif;
	?>
	<header id="masthead" class="site-header" role="banner">
		<div class="site-branding container">
			<div class="row">
				<div class="col-sm-4 header-logo">
					<?php /* @TB
					if ( has_custom_logo() ) :
						the_custom_logo();
					else :
						?>
						<div class="site-title-description">
							<?php
							$header_textcolor = get_theme_mod( 'header_textcolor' );
							if ( 'blank' !== $header_textcolor ) :
								?>
								<a class="site-title" href="<?php echo esc_url( get_home_url() ); ?>">
									<?php Tyche_Helper::customize_partial_blogname(); ?>
								</a>
								<?php
								$description = get_bloginfo( 'description', 'display' );
								if ( $description || is_customize_preview() ) :
									?>
									<p class="site-description"> <?php Tyche_Helper::customize_partial_blogdescription(); ?> </p>
								<?php endif; ?>

							<?php endif; ?>
						</div>
						<?php
					endif;
					*/
					?>
				</div>

				<?php if ( get_theme_mod( 'tyche_show_banner', false ) ) : ?>
					<div class="col-sm-8 header-banner">
						<?php
						// @TB
						// $banner = get_theme_mod( 'tyche_banner_type', 'image' );
						// get_template_part( 'template-parts/banner/banner', $banner );
						?>
					</div>
				<?php endif; ?>
			</div>
		</div><!-- .site-branding -->

		<nav id="site-navigation" class="main-navigation" role="navigation">
			<div class="container">
				<div class="row">
					<div class="col-md-3 col-xs-10">
					<?php
					
					?>
					</div>
					<div class="col-md-9 col-xs-2">
						<div class="pull-left">
						<?php
						wp_nav_menu(
							array(
								'menu'           => 'primary',
								'theme_location' => 'primary',
								'depth'          => 10,
								'container'      => '',
								'menu_id'        => 'desktop-menu',
								'menu_class'     => 'sf-menu',
								'fallback_cb'    => 'Tyche_Navwalker::fallback',
								'walker'         => new Tyche_Navwalker(),
							)
						);
						?>
						</div>
						<div style="text-align:right">
						<!-- /// Mobile Menu Trigger //////// -->
						<a href="#" id="mobile-menu-trigger"> <i class="fa fa-bars"></i> </a>
						<!-- end #mobile-menu-trigger -->
						</div>
					</div>
				</div>
			</div>
		</nav><!-- #site-navigation -->

	</header><!-- #masthead -->

	<?php
	/**
	 * Enable / Disable the main slider
	 */
	$show_on_front = get_option( 'show_on_front' );
	if ( get_theme_mod( 'tyche_enable_main_slider', true ) && is_front_page() && 'posts' !== $show_on_front ) :
		get_template_part( 'template-parts/main-slider' );
	endif;
	?>

	<div class="site-content">
