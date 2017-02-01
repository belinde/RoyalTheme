<?php
use Royal\Engine;

get_header();
?>
    <div id="content">
        <div id="content-inner">
            <h2 class="title text-center bft"><span>Annunci in evidenza</span></h2>
            <div class="grid">
                <?php
                $resQuery = Engine::getInstance()->queryRicerca(['posts_per_page' => 12]);
                while ($resQuery->have_posts()) {
                    $resQuery->the_post();
                    get_template_part('annuncio', 'tile');
                }
                ?>
            </div>
            <div class="text-center">
                <a class="btn" href='/ricerca/risultati'>Visualizza tutti gli annunci</a>
            </div>
        </div>
    </div>
<?php get_footer(); ?>
