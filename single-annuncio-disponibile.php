<?php
use Royal\Engine;

$royal   = Engine::getInstance();
$post    = get_post();
$affitto = false;
?>
<div id="content">
	<div id="content-inner">
		<?php
        recapAnnuncio($post->ID);
		if ( $royal->hasGallery( 'photos' ) ) {
			the_slideshow_gallery( 'photos' );
		}
		?>
		<div class="annuncio-info grid">
            <div class="col lg-9 grid">
                <div class="col lg-12">
                    <h3>Descrizione</h3>
                    <?php the_content(); ?>
                </div>
                <div class="annuncio-tab-table col lg-12">
                    <div class="annuncio-tab active annuncio-caratteristiche">Caratteristiche</div>
                    <?php if ( $royal->hasGallery( 'planimetries' ) ) { ?> <div class="annuncio-tab annuncio-planimetrie">Planimetrie</div> <?php } ?>
                    <?php if ( $royal->hasMap() ) { ?> <div class="annuncio-tab annuncio-mappa">Mappa</div> <?php } ?>

                    <div class="annuncio-tab-content caratteristiche active">
                        <p>
                            <?php
                            the_terms( $post->ID, 'comune', 'Comune:&nbsp;', ', ', '<br>' );
                            the_terms( $post->ID, 'tipologia', 'Tipologia:&nbsp;', ', ', '<br>' );
                            the_terms( $post->ID, 'contratto', 'Contratto:&nbsp;', ', ', '<br>' );
                            $royal->theInformations();
                            ?>
                        </p>
                    </div>
                    <?php
                    if ( $royal->hasGallery( 'planimetries' ) ) {
                        ?>
                            <div class="annuncio-tab-content planimetrie">
                                <?php the_slideshow_gallery( 'planimetries' ); ?>
                            </div>
                        <?php
                    }
                    ?>
                    <?php
                    if ( $royal->hasMap() ) {
                        ?>
                        <div class="annuncio-tab-content mappa">
                            <?php $royal->theMap(); ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="col lg-3 ask-info">
                <div>
                    <h3>Richiedi Informazioni</h3>
                    <form class="royalFormInfo">
                        <input type="hidden" class="royalFormAnnuncio" value="<?php echo esc_attr( get_the_title() ); ?>">
                        <input type="text" placeholder="Nome" class="royalFormNome"/>
                        <input type="text" placeholder="Email" class="royalFormEmail"/>
                        <textarea placeholder="Il tuo messaggio" class="royalFormTesto"></textarea>
                        <input type="submit" value="Invia Messaggio"/>
                        <ul class="royalFormErrori"></ul>
                        <div class="royalFormOk" style="display:none;">Grazie per l'interesse, verrai ricontattato il prima possibile.</div>
                    </form>
                </div>
                <div>
                    <?php
                    if ( $royal->hasMap() ) {
                        ?>
                        <div class="col lg-4 md-4 sm-6 xs-12">
                            <h3>Mappa</h3>
                            <?php $royal->theMap(); ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
		</div>
	</div><!-- end content-inner-->
</div><!--end content-->
