<h1>Annunciaziò, annunciaziò</h1>
<article>
	<?php use Royal\Engine;

	the_title( '<h2 class="entry-title">', '</h2>' );
	$terms = get_the_terms( get_the_ID(), 'comune');
	pr($terms);
	?>

	<div class="entry-content"><?php the_content(); ?></div>
	<?php Engine::getInstance()->theInformations(); ?>
	<?php Engine::getInstance()->theGallery(); ?>
	<?php Engine::getInstance()->theMap(); ?>
</article>