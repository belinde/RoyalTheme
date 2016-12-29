<?php
use Royal\Widgets\MenuAnnunci;

?>
<div class="grid">
    <div class="col lg-3">
        <?php (new MenuAnnunci())->printer(); ?>
    </div>
    <div class="col lg-9">
        <div class="grid">
            <?php
            if (have_posts()) {
                while (have_posts()) {
                    the_post();
                    get_template_part('annuncio', 'tile');
                }
            } else {
                echo 'nessun risultato';
            }
            ?>
        </div>
    </div>
</div>