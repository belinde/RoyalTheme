<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <?php wp_head(); ?>
    <script type="application/ld+json">
    {
        "@context" : "http://schema.org",
        "@type" : "LocalBusiness",
	    "image": "http://www.immobiliareroyal.it/wp-content/themes/RoyalTheme/images/logo.png",
        "address" : {
        "@type": "PostalAddress",
        "addressLocality": "Chiavari",
        "addressRegion": "Genova",
        "postalCode": "16043",
        "streetAddress": "Corso Garibaldi 78" },
        "name":"Royal Immobiliare",
        "url":"http://www.immobiliareroyal.it",
        "email":"info@immobiliareroyal.it",
        "telephone":"+390185303436"
    }

    </script>
</head>
<body <?php body_class(); ?>>
<div id="wrapper">
    <div id="header">
        <div class="logo-container">
            <a class="logo" href="<?php echo esc_url(home_url('/')); ?>" rel="home"></a>
        </div>
        <div class="upline">
            <div class="upline-inner">
                <span class="tel-link"><span><a
                            href="tel:+390185303436">+39&nbsp;0185&nbsp;30&thinsp;34&thinsp;36</a></span></span>
                <span class="tel-link -cell"><span><a
                            href="tel:+393397519758">+39&nbsp;339&nbsp;75&thinsp;19&thinsp;758</a></span></span>
                <span class="tel-link -mail"><span><a
                            href="mailto:info@immobiliareroyal.it">info@immobiliareroyal.it</a></span></span>
                <a href="https://www.facebook.com/immobiliareroyalchiavari" class="fb-link" target="_blank"></a>
            </div>
        </div>
        <div class="menu">
            <div class="menu-inner">
                <?php
                wp_nav_menu([
                    'theme_location' => 'menuprincipale',
                    'container'      => false,
                    'menu_id'        => 'menu'
                ]);

                ?>
            </div>
        </div>
        <div class="menu-mobile">
            <span class="toggler-menu toggler-ico ico-dehaze"></span>
            <span class="toggler-menu toggler-bg"></span>
            <?php
            wp_nav_menu([
                'theme_location' => 'menuprincipale',
                'container'      => false,
                'menu_class'     => 'menu-mobile-inner'
            ]);
            ?>
        </div>
        <div class="header-image <?php echo (is_home() or is_front_page()) ? 'isIndex' : null ?>"
             style="background-image: url('<?php getHeaderImage(); ?>');"></div>
    </div>
    <!-- <div id="quick-contacts">
		<div id="quick-contacts-inner">
			<span class="tel-link">tel/fax +39 0185 303 436</span>
			<span style="text-align: right"><a href="https://www.facebook.com/immobiliareroyalchiavari" class="fb-link"
			 target="_blank"></a></span>
		</div>
	</div>
	<div id="header"<?php
    if (is_home() or is_front_page()) {
        echo ' class="isIndex"';
    }
    // if ( is_single() ) {
    // 	echo ' class="isSingle"';
    // }
    ?> style="background-image: url('<?php getHeaderImage(); ?>');">
		<div id="header-inner">
			<div id="logo-container">
				<a href="<?php echo esc_url(home_url('/')); ?>" id="logo" rel="home"></a>
			</div>
			<div id="menu-container">
				<?php
    wp_nav_menu([
        'theme_location' => 'menuprincipale',
        'container'      => false,
        'menu_id'        => 'menu'
    ]);

    ?>
				<div id="menu-mobile">
					<span class="toggler-menu toggler-ico ico-dehaze"></span>
					<span class="toggler-menu toggler-bg"></span>
					<?php
    wp_nav_menu([
        'theme_location' => 'menuprincipale',
        'container'      => false,
        'menu_class'     => 'menu-mobile-inner'
    ]);
    ?>
				</div>
			</div>
		</div> -->
    <!-- </div> -->