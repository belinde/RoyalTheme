<?php
use Royal\Engine;

$royal = Engine::getInstance();

$resQuery = $royal->queryRicerca([]);
$json = [];
if ($resQuery->have_posts()) {
    while ($resQuery->have_posts()) {
        $resQuery->the_post();
        $pid = get_the_ID();
        $json[] = [
            'permalink' => get_permalink(),
            'thumbnail' => get_the_post_thumbnail_url(null, 'thumbnail'),
            'title'     => descrizioneAnnuncio($pid),
            'comune'    => get_the_terms($pid, 'comune'),
            'tipologia' => get_the_terms($pid, 'tipologia'),
            'contratto' => get_the_terms($pid, 'contratto'),
            'address'   => get_post_meta($pid, Engine::getInstance()->getFields()['indirizzo']->metaSlug(),
                true)
        ];
    }
}

get_header();
?>
    <div id="content">
        <div id="content-inner">
            <h2 class="title text-center bft"><span>Mappa Immobili</span></h2>
            <div class="grid">
                <div class="col lg-3" style="padding-right: 20px;">
                    <div id="royalMapSearchForm" class="row">
                        <div class="grid searchform">
                            <div class="col lg-12 searchform-row">
                                <h4 class="searchform-title">Comune</h4>
                                <div id="royalMapSearchComune" class="searchform-fields"></div>
                            </div>
                            <div class="col lg-12 searchform-row">
                                <h4 class="searchform-title">Tipologia</h4>
                                <div id="royalMapSearchTipologia" class="searchform-fields"></div>
                            </div>
                            <div class="col lg-12 searchform-row">
                                <h4 class="searchform-title">Contratto</h4>
                                <div id="royalMapSearchContratto" class="searchform-fields"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col lg-9">
                    <div id="royalMapSearchData" style="display: none;"><?php echo json_encode($json); ?></div>
                    <div id="royalMapSearch" style="width: 100%;height: 500px; margin-bottom: 30px;"></div>
                </div>
            </div>


        </div>
    </div>
<?php get_footer();
