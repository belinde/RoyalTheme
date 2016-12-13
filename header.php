<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php wp_head(); ?>
	<title><?php wp_title(); ?></title>
</head>
<body <?php body_class(); ?>>
<div style="width:300px; float: left; padding: 5px; border: 1px solid grey;">
	<?php dynamic_sidebar('barralaterale'); ?>
</div>
<div style="margin: 5px 5px 5px 320px; padding: 5px; border: 1px solid grey;">
