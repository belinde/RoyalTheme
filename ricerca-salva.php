<?php
use Royal\Engine;
use Royal\Interesse;

get_header();
?>
	<div id="content">
		<div id="content-inner">
			<?php
			$royal = Engine::getInstance();

			$ricercaId = $royal->editRicerca();

			/** @var Interesse $interesse */
			$interesse = get_post_meta( $ricercaId, 'royal_interesse', true );

			$resQuery = $royal->queryRicerca(get_post_meta( $ricercaId, 'royal_ricerca', true ));

			?>
			<p><strong><?php echo $interesse->getName(); ?></strong>, grazie per esserti registrato al nostro servizio. Quando verrà inserito un annuncio che soddisfa la tua ricerca te lo notificheremo a questi recapiti:</p>
			<dl>
				<dt>Telefono:</dt>
				<dd><?php echo $interesse->getPhone(); ?></dd>
				<dt>E-mail:</dt>
				<dd><?php echo $interesse->getMail(); ?></dd>
			</dl>
			<div class="col lg-9">
				<div class="grid">
					<?php
					if ($resQuery->have_posts()) {
						while ($resQuery->have_posts()) {
							$resQuery->the_post();
							get_template_part('annuncio', 'tile');
						}
					} else {
						echo 'nessun risultato';
					}
					?>
				</div>
			</div>
		</div>
	</div>
<?php get_footer(); ?>