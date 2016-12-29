<?php
use Royal\Engine;

$royal = Engine::getInstance();

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

get_header();
?>
    <div id="content">
        <div id="content-inner">
            <div id="royalMapSearch" style="width: 100%;height: 800px;"><?php echo json_encode($json); ?></div>
        </div>
    </div>
<?php get_footer();