<div id="content">
    <div id="content-inner">
        <div class="annuncio-info">
            <h3>Annuncio terminato</h3>
            <p>Ci dispiace ma questo immobile è in fase di trattativa.</p>
            <div class="grid">
                <?php
                $relateds = Royal\Engine::getInstance()->queryRelateds();
                if ($relateds and $relateds->have_posts()) {
                    echo '<p>Ti interessa qualcosa di simile?</p>';
                    while ($relateds->have_posts()) {
                        $relateds->the_post();
                        get_template_part('annuncio', 'tile');
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>