<?php
use Royal\Engine;

$json = wp_cache_get('ricerca-mappa', 'royal');
if (!$json) {
    $resQuery = Engine::getInstance()->queryRicerca(['posts_per_page' => -1]);
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
                'address'   => get_post_meta($pid, Engine::getInstance()->getFields()['indirizzo']->metaSlug(), true),
                'location'  => get_post_meta($pid, 'royal_maps_location', true),
            ];
        }
    }
    $json = json_encode($json);
    wp_cache_set('ricerca-mappa', $json, 'royal');
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
                            <div class="col lg-12">
                                <div id="royalMapSearchContratto" class="searchform-fields"></div>
                            </div>
                            <div class="col lg-12 searchform-row">
                                <h4 class="searchform-title">Comune</h4>
                                <div id="royalMapSearchComune" class="searchform-fields"></div>
                            </div>
                            <div class="col lg-12 searchform-row">
                                <h4 class="searchform-title">Tipologia</h4>
                                <div id="royalMapSearchTipologia" class="searchform-fields"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col lg-9">
                    <div id="royalMapSearchData" style="display: none;"><?php echo $json; ?></div>
                    <div id="royalMapSearch" style="width: 100%;height: 500px; margin-bottom: 30px;"></div>
                </div>
            </div>
            <script type="application/javascript">
                jQuery(function () {
                    function pinnatore(info, i) {
                        if (info.location) {
                            console.log(info.location);
                            var j;
                            markers[i] = {
                                marker: new google.maps.Marker({
                                    map: map,
                                    position: info.location,
                                    title: info.title
                                }),
                                comune: [],
                                tipologia: [],
                                contratto: []
                            };
                            var contentString = '<a href="' + info.permalink + '"><img style="float: left; margin:5px;" src="' + info.thumbnail + '"></a><h3>' + info.title + '</h3><p>' + info.address + '</p>';
                            for (j = info.comune.length - 1; j >= 0; j--) {
                                if (typeof eleComune[info.comune[j].slug] == 'undefined') {
                                    eleComune[info.comune[j].slug] = true;
                                    jQuery('<div class="fake-checkbox"><input id="' + info.comune[j].slug + '" type="checkbox" checked class="interruttore" data-tipo="comune" data-valore="' + info.comune[j].slug + '"><label for="' + info.comune[j].slug + '">' + info.comune[j].name + '</label><span class="immobili_quantity ' + info.comune[j].slug + '">0</span></div>').appendTo('#royalMapSearchComune');
                                }
                                markers[i].comune.push(info.comune[j].slug);
                            }
                            for (j = info.contratto.length - 1; j >= 0; j--) {
                                if (typeof eleContratto[info.contratto[j].slug] == 'undefined') {
                                    eleContratto[info.contratto[j].slug] = true;
                                    var vend = (info.contratto[j].slug == "vendite" || info.contratto[j].slug == "vendita");
                                    markers[i].marker.setVisible(vend);
                                    jQuery('<div class="fake-radio-menu"><input id="' + info.contratto[j].slug + '" type="checkbox"' + ( vend ? ' checked' : '' ) + ' class="interruttore" data-tipo="contratto" data-valore="' + info.contratto[j].slug + '"><label for="' + info.contratto[j].slug + '">' + info.contratto[j].name + '</label></div>').appendTo('#royalMapSearchContratto');
                                }
                                markers[i].contratto.push(info.contratto[j].slug);
                            }
                            for (j = info.tipologia.length - 1; j >= 0; j--) {
                                if (typeof eleTipologia[info.tipologia[j].slug] == 'undefined') {
                                    eleTipologia[info.tipologia[j].slug] = true;
                                    jQuery('<div class="fake-checkbox"><input id="' + info.tipologia[j].slug + '" type="checkbox" checked class="interruttore" data-tipo="tipologia" data-valore="' + info.tipologia[j].slug + '"><label for="' + info.tipologia[j].slug + '">' + info.tipologia[j].name + '</label><span class="immobili_quantity ' + info.tipologia[j].slug + '">0</span></div>').appendTo('#royalMapSearchTipologia');
                                }
                                markers[i].tipologia.push(info.tipologia[j].slug);
                            }
                            markers[i].marker.addListener('click', function () {
                                infowindow.setContent(contentString);
                                infowindow.open(map, markers[i].marker);
                            });
                            jQuery('#royalMapSearchForm').find('.interruttore').first().trigger('change');

                        }
                    }

                    var container = document.getElementById('royalMapSearch');
                    if (container) {
                        var data = jQuery.parseJSON(jQuery('#royalMapSearchData').text());
                        // console.log(data);

                        map = new google.maps.Map(container, {
                            center: {lat: 44.3594345, lng: 9.3540266},
                            zoom: 10
                        });
                        var eleComune = [], eleTipologia = [], eleContratto = [];
                        var infowindow = new google.maps.InfoWindow();
                        for (var i = data.length - 1; i >= 0; i--) {
                            pinnatore(data[i], i);
                        }
                        jQuery('#royalMapSearchForm').find('.interruttore').trigger('change');
                    }
                });
            </script>

        </div>
    </div>
<?php get_footer();
