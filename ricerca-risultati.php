<?php
use Royal\Engine;
use Royal\SearchForm;

get_header();
$royal = Engine::getInstance();
echo '<h1>Risultati ricerca</h1>';
$resQuery = $royal->queryRicerca( $_POST['royalsearch'] );
echo new SearchForm( '/ricerca/risultati', $_POST['royalsearch'] );

if ( $resQuery->have_posts() ) {
	echo '<ol>';
	while ( $resQuery->have_posts() ) {
		$resQuery->the_post();
		the_title( '<h2><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' );
		the_post_thumbnail('thumbnail');
	}
	echo '</ol>';
} else {
	echo "Nessun risultato!";
}

?>
<table>
	<tr>
		<td valign="top"><?php pr( $_POST['royalsearch'] ); ?></td>
		<td valign="top"><?php pr( $resQuery->query ); ?></td>
		<td valign="top"><?php pr( $resQuery->query_vars ); ?></td>
	</tr>
</table>
<?php

print( $resQuery->request );
//pr($resQuery->query_vars);
get_footer(); ?>
