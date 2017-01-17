<?php
use Royal\Engine;

$royal = Engine::getInstance();
$post  = get_post();
$affitto = false;
?>
<div id="content">
	<div id="content-inner">
		<?php the_slideshow_gallery('photos'); ?>
		<div class="annuncio-info grid">
            <div class="col lg-2">
                <h3>Prezzo</h3>
                <p class="annuncio-price"><?php $royal->getFields()['prezzo']->printer( $post ); ?><?php echo ($affitto ? '/mese' : ''); ?></p>
            </div>
			<div class="col lg-6">
				<h3>Descrizione</h3>
				<?php the_content(); ?>
			</div>
			<div class="col lg-4 md-4 sm-6 xs-12">
                <h3>Dettagli</h3>
				<p>
					<?php

					the_terms( $post->ID, 'comune', 'Comune:&nbsp;', ', ', '<br>' );
					the_terms( $post->ID, 'tipologia', 'Tipologia:&nbsp;', ', ', '<br>' );
					the_terms( $post->ID, 'contratto', 'Contratto:&nbsp;', ', ', '<br>' );
					$royal->theInformations();

					?>
				</p>
			</div>
			<div class="col lg-4 md-4 sm-6 xs-12">
                <h3>Mappa</h3>
                <?php Engine::getInstance()->theMap(); ?>
			</div>
			<div class="col lg-4 md-4 sm-12 xs-12">
                <h3>Planimetrie</h3>
                <?php the_slideshow_gallery('planimetries'); ?>
			</div>
			<div class="col lg-4 md-4 sm-12">
				<h3>Richiedi Informazioni</h3>
				<form class="royalFormInfo">
					<input type="hidden" class="royalFormAnnuncio" value="<?php echo esc_attr( get_the_title() ); ?>">
					<input type="text" placeholder="Nome" class="royalFormNome"/>
					<input type="text" placeholder="Email" class="royalFormEmail"/>
					<textarea placeholder="Il tuo messaggio" class="royalFormTesto"></textarea>
					<input type="submit" value="Invia Messaggio"/>
					<ul class="royalFormErrori"></ul>
					<div class="royalFormOk" style="display:none;">Grazie per l'interesse, verrai ricontattato il prima
						possibile.
					</div>
				</form>
			</div>
		</div>
	</div><!-- end content-inner-->
</div><!--end content-->
