<?php
use Royal\Engine;

get_header();
$royal = Engine::getInstance();
echo '<h1>Mappa</h1>';
$resQuery = $royal->queryRicerca([]);
$json = [];
if ($resQuery->have_posts()) {
    while ($resQuery->have_posts()) {
        $resQuery->the_post();

        $json[] = [
            'permalink' => get_permalink(),
            'thumbnail' => get_the_post_thumbnail_url(null, 'thumbnail'),
            'title'     => descrizioneAnnuncio(get_the_ID()),
            'address'   => get_post_meta(get_the_ID(), Engine::getInstance()->getFields()['indirizzo']->metaSlug(),
                true)
        ];
    }
}

echo '<div id="royalMapSearch" style="width: 900px;height: 600px;">' . json_encode($json) . '</div>';

get_footer();