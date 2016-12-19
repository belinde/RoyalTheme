<h1>Annunciaziò, annunciaziò</h1>
<article>
	<?php use Royal\Engine;

	the_title( '<h2 class="entry-title">', '</h2>' ); ?>
	<div class="entry-content"><?php the_content(); ?></div>
	<?php Engine::getInstance()->theInformations(); ?>
	<?php Engine::getInstance()->theGallery(); ?>
	<?php Engine::getInstance()->theMap(); ?>
</article>