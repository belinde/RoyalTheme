
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="wrapper">
	<div id="quick-contacts">
		<div id="quick-contacts-inner">
			<span>tel/fax +39 0185 303 436</span>
			<span style="text-align: right"><a href="https://www.facebook.com/immobiliareroyalchiavari" target="_blank"><img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjE2cHgiIGhlaWdodD0iMTZweCIgdmlld0JveD0iMCAwIDUxMCA1MTAiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDUxMCA1MTA7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPGc+Cgk8ZyBpZD0icG9zdC1mYWNlYm9vayI+CgkJPHBhdGggZD0iTTQ1OSwwSDUxQzIyLjk1LDAsMCwyMi45NSwwLDUxdjQwOGMwLDI4LjA1LDIyLjk1LDUxLDUxLDUxaDQwOGMyOC4wNSwwLDUxLTIyLjk1LDUxLTUxVjUxQzUxMCwyMi45NSw0ODcuMDUsMCw0NTksMHogICAgIE00MzMuNSw1MXY3Ni41aC01MWMtMTUuMywwLTI1LjUsMTAuMi0yNS41LDI1LjV2NTFoNzYuNXY3Ni41SDM1N1Y0NTloLTc2LjVWMjgwLjVoLTUxVjIwNGg1MXYtNjMuNzUgICAgQzI4MC41LDkxLjgsMzIxLjMsNTEsMzY5Ljc1LDUxSDQzMy41eiIgZmlsbD0iI0ZGRkZGRiIvPgoJPC9nPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo=" /></a></span>
		</div>
	</div>
	<div id="header"<?php
	if ( is_home() or is_front_page() ) {
		echo ' class="isIndex"';
	}
	// if ( is_single() ) {
	// 	echo ' class="isSingle"';
	// }
	?> style="background-image: url('<?php getHeaderImage(); ?>');">
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
