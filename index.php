<?php
get_header();
while ( have_posts() ) {
	the_post();
	?>
	<article>
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<div class="entry-content"><?php the_content(); ?></div>
	</article>
	<?php
}
get_footer(); ?>
