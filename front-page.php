<?php
use Royal\Engine;

get_header();
?>
    <div id="content">
        <div id="content-inner">
            <h2 class="title text-center">Annunci in evidenza</h2>
            <div class="grid">
                <?php
                $resQuery = Engine::getInstance()->queryRicerca(['posts_per_page' => 12]);
                while ($resQuery->have_posts()) {
                    $resQuery->the_post();
                    get_template_part('annuncio', 'tile');
                }
                ?>
                <!-- TILES ANNUNCI -->
            </div>
        </div><!-- end content-inner-->
    </div><!--end content-->
<?php get_footer(); ?>