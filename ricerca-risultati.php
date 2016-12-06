<?php
use Royal\Engine;

get_header();
$royal = Engine::getInstance();
echo '<h1>Risultati ricerca</h1>';
$resQuery = $royal->queryRicerca( $_POST['royalsearch'] );
$royal->theSearchForm( '/ricerca/risultati', $resQuery );

var_dump($resQuery);
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
get_footer(); ?>
