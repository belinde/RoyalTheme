<?php
use Royal\Engine;
use Royal\SearchForm;

get_header();
$royal = Engine::getInstance();
echo '<h1>Risultati ricerca</h1>';
$resQuery = $royal->queryRicerca( $_POST['royalsearch'] );
echo new SearchForm( '/ricerca/risultati', $_POST['royalsearch'] );

if ( $resQuery->have_posts()) {
	echo '<ol>';
	while ( $resQuery->have_posts() ) {
		$resQuery->the_post();
		the_title( '<li>', '</li>' );
	}
	echo '</ol>';
} else {
	echo "Nessun risultato!";
}

pr( $_POST['royalsearch'] );
//pr($resQuery->query_vars);
get_footer(); ?>
