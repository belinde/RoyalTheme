<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <?php wp_head(); ?>
    <title><?php wp_title(); ?></title>
</head>
<body <?php body_class(); ?>>
<div id="wrapper">

    <div id="header" class="isIndex">
        <div id="header-inner">
            <div id="logo-container">
                <a href="index.php" id="logo"></a>
            </div>
            <div id="menu-container">
                <ul id="menu">
                    <!-- classe 'active' se nella sezione giusta -->
                    <li><a class="" href="?immobili">Immobili</a></li>
                    <li><a class="" href="?chisiamo">Chi Siamo</a></li>
                    <li><a class="" href="?clientela">La Nostra Clientela</a></li>
                    <li><a class="" href="?novita">Novità</a></li>
                </ul>
                <!-- doppio menu, per la versione mobile. -->
                <div id="menu-mobile">
                    <span class="toggler-menu toggler-ico ico-dehaze"></span>
                    <span class="toggler-menu toggler-bg"></span>
                    <ul class='menu-mobile-inner'>
                        <li><a class="" href="?immobili">Immobili</a></li>
                        <li><a class="" href="?chisiamo">Chi Siamo</a></li>
                        <li><a class="" href="?clientela">La Nostra Clientela</a></li>
                        <li><a class="" href="?novita">Novità</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

<?php //dynamic_sidebar('barralaterale'); ?>