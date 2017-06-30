<?php
$cached = wp_cache_get('sitemap', 'royal');
if (!$cached) {
    global $royalSitemap;
    $royalSitemap = new SimpleXMLElement(
        '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>'
    );
    /**
     * @param array $params
     */
    function addURl($params)
    {
        global $royalSitemap;
        $urlElement = $royalSitemap->addChild('url');
        $urlElement->addChild(
            'loc',
            'http://www.immobiliareroyal.it/ricerca/risultati/?' . htmlentities(http_build_query($params))
        );
        $urlElement->addChild('changefreq', 'weekly');
        $urlElement->addChild('priority', round(.4 + (count($params) / 5), 1));
    }

    /** @var WP_Term[] $contratti */
    $contratti = get_terms(['taxonomy' => 'contratto', 'hide_empty' => true]);
    /** @var WP_Term[] $tipologie */
    $tipologie = get_terms(['taxonomy' => 'tipologia', 'hide_empty' => true]);
    /** @var WP_Term[] $comuni */
    $comuni = get_terms(['taxonomy' => 'comune', 'hide_empty' => true]);

    foreach ($contratti as $contratto) {
        $params = ['rs_con' => $contratto->term_id];
        addURl($params);
        foreach ($tipologie as $tipologia) {
            $params['rs_tip'] = $tipologia->term_id;
            addURl($params);
            foreach ($comuni as $comune) {
                $params['rs_com'] = $comune->term_id;
                addURl($params);
            }
        }
    }

    $cached = $royalSitemap->asXML();
    wp_cache_set('sitemap', $cached, 'royal');
}

header('Content-type: text/xml');
echo $cached;