<?php
use Royal\Engine;
use Royal\SearchForm;

get_header();
$royal    = Engine::getInstance();
$ricerca  = isset( $_POST['royalsearch'] ) ? $_POST['royalsearch'] : [ ];
$resQuery = $royal->queryRicerca( $ricerca );

?>
	<div id="content">
		<div id="content-inner">
			<div class="grid">
				<div class="col lg-3">
					<?php
					echo new SearchForm( '/ricerca/avanzata/', $ricerca );

					if ( $ricerca ) {
						?>
						<h3>Ricevi aggiornamenti su questa ricerca:</h3>
						<form method="post" action="/ricerca/salva/">
							<div style="display: none;"><?php echo new SearchForm( false, $ricerca );?></div>

							<label for="royalNome">Nome:</label>
							<input type="text" name="interesse[nome]" id="royalNome">
							<label for="royalMail">E-mail:</label>
							<input type="text" name="interesse[mail]" id="royalMail">
							<label for="royalPhone">Telefono:</label>
							<input type="text" name="interesse[telefono]" id="royalPhone">
<input type="submit" value="Registra la ricerca">
						</form>
						<?php
					}
					?>
				</div>
				<div class="col lg-9">
					<div class="grid">
						<?php
						if ( $resQuery->have_posts() ) {
							while ( $resQuery->have_posts() ) {
								$resQuery->the_post();
								get_template_part( 'annuncio', 'tile' );
							}
						} else {
							echo 'nessun risultato';
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php

//echo new SearchForm(Engine::URL_RISULTATI, $ricerca);
get_footer();