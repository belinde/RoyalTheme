<?php
use Royal\Engine;
use Royal\Widgets\MenuAnnunci;

get_header();
$royal = Engine::getInstance();
$ricerca = isset($_POST['royalsearch']) ? $_POST['royalsearch'] : [];
$resQuery = $royal->queryRicerca($ricerca);

?>
    <div id="content">
        <div id="content-inner">
            <h2 class="title text-center bft"><span>Tutti gli annunci</span></h2>
            <div class="grid">
                <div class="col lg-3">
                    <?php (new MenuAnnunci())->printer(); ?>
                </div>
                <div class="col lg-9">
                    <div class="grid">
                        <?php
                        if ($resQuery->have_posts()) {
                            while ($resQuery->have_posts()) {
                                $resQuery->the_post();
                                get_template_part('annuncio', 'rows');
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
