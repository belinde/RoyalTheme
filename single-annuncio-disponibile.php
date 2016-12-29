<?php
use Royal\Engine;

$royal = Engine::getInstance();
$post = get_post();
?>
<div id="content">
    <div id="content-inner">
        <?php the_slideshow_gallery(); ?>
        <div class="annuncio-info grid">
            <div class="col lg-8">
                <h3>Descrizione</h3>
                <?php the_content(); ?>
            </div>
            <div class="col lg-4 md-4 sm-6 xs-12">
                <h3>Prezzo</h3>
                <p class="annuncio-price"><?php $royal->getFields()['prezzo']->printer($post); ?></p>
            </div>
            <div class="col lg-8 md-4 sm-6 xs-12">
                <h3>Dettagli</h3>
                <p>
                    <?php

                    the_terms($post->ID, 'comune', 'Comune:&nbsp;', ', ', '<br>');
                    the_terms($post->ID, 'tipologia', 'Tipologia:&nbsp;', ', ', '<br>');
                    the_terms($post->ID, 'contratto', 'Contratto:&nbsp;', ', ', '<br>');
                    $royal->theInformations();

                    ?>
                </p>
            </div>
            <div class="col lg-4 md-4 sm-12">
                <h3>Richiedi Informazioni</h3>
                <form>
                    <input type="text" placeholder="Nome"/>
                    <input type="text" placeholder="Email"/>
                    <textarea placeholder="Il tuo messaggio"></textarea>
                    <input type="submit" value="Invia Messaggio"/>
                </form>
            </div>


            <div class="col lg-4 md-4 sm-12">
                <h3>Mappa</h3>
                <?php Engine::getInstance()->theMap(); ?>
            </div>
        </div>
    </div><!-- end content-inner-->
</div><!--end content-->