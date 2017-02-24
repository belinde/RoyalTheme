<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="wrapper">

	<div id="header"<?php
	if ( is_home() or is_front_page() ) {
		echo ' class="isIndex"';
	}
	?> style="background-image: url('<?php header_image(); ?>');">
		<div id="header-inner">
			<div id="logo-container">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" id="logo" rel="home"></a>
			</div>
			<div id="menu-container">
				<?php
				wp_nav_menu( [
					'theme_location' => 'menuprincipale',
					'container'      => false,
					'menu_id'        => 'menu'
				] );

				?>
				<div id="menu-mobile">
					<span class="toggler-menu toggler-ico ico-dehaze"></span>
					<span class="toggler-menu toggler-bg"></span>
					<?php
					wp_nav_menu( [
						'theme_location' => 'menuprincipale',
						'container'      => false,
						'menu_class'     => 'menu-mobile-inner'
					] );
					?>
				</div>
			</div>
		</div>
	</div>
