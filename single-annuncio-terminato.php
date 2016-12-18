<h1>Annuncio terminato</h1>
<article>
	<?php use Royal\Engine;

	the_title( '<h2 class="entry-title">', '</h2>' ); ?>
	<p>Ci dispiace ma questo immobile è stato piazzato. Ti interessa qualcosa di simile?</p>
	<?php Engine::getInstance()->theRelateds() ?>
</article>