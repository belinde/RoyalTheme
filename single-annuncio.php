<?php
use Royal\Engine;

get_header();
$royal = Engine::getInstance();
echo '<h1>La ricerca</h1>';
$royal->theSearchForm('/');
while ( have_posts() ) {
	the_post();
	?>
	<h1>Annunciaziò, annunciaziò</h1>
	<article>
		<?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
		<div class="entry-content"><?php the_content(); ?></div>
		<?php $royal->theInformations(); ?>
		<?php $royal->theGallery(); ?>
	</article>
	<?php
}
get_footer(); ?>
