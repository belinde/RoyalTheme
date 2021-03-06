<?php

get_header();
?>
<div id="content">
    <div id="content-inner">
        <h2 class="title text-center bft"><span>Annunci in evidenza</span></h2>
        <div class="grid evidenziata">
            <?php
            $resQuery = new \WP_Query([
                'post_type'      => 'annuncio',
                'post_status'    => 'publish',
                'posts_per_page' => 12,
                'meta_query'     => [
                    ['key' => 'royal_meta_status', 'value' => 'disponibile'],
                    ['key' => 'royal_meta_evidenza', 'value' => '1']
                ]
            ]);

            while ($resQuery->have_posts()) {
                $resQuery->the_post();
                get_template_part('annuncio', 'tile');
            }
            ?>
        </div>
        <div class="text-center">
            <a class="btn" href='/ricerca/risultati/?rs_con=3'>Visualizza tutti gli annunci</a>
        </div>
    </div>
</div>
<?php get_footer(); ?>
