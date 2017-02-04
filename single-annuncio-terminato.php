<div id="content">
	<div id="content-inner">
		<div class="annuncio-info">
			<div class="watermarked"
			     style="background-image: url('<?= get_the_post_thumbnail_url( null, 'royalslide' ) ?>');">
				<span>Immobile non più disponibile</span>
			</div>
			<h3>Immobile non più disponibile</h3>
			<p>Ci dispiace ma questo immobile è stato piazzato.</p>
			<div class="grid">
				<?php
				$relateds = Royal\Engine::getInstance()->queryRelateds();
				if ( $relateds and $relateds->have_posts() ) {
					echo '<p>Ti interessa qualcosa di simile?</p>';
					while ( $relateds->have_posts() ) {
						$relateds->the_post();
						get_template_part( 'annuncio', 'tile' );
					}
				}
				?>
			</div>
		</div>
	</div>
</div>