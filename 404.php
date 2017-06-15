<?php
if (false !== strpos($_SERVER['REQUEST_URI'], 'immobili_elenco.php')) {
    /**
     * @param $query
     * @param $params
     * @param $foresto
     * @param $corrente
     */
    function populator(&$query, $params, $foresto, $corrente)
    {
        $dict = [
            'categoria' => [
                'vendite' => 3,
                'affitti' => 2
            ],
            'tipologia' => [
                'appartamenti'              => 9,
                'negozi, uffici, capannoni' => 13,
                'ville'                     => 10,
//            'nuda proprietà'            => 2,
                'terreni e rustici'         => 12
            ],
            'zona'      => [
                'chiavari'                 => 4,
                'lavagna'                  => 6,
                'san salvatore di cogorno' => 5,
                'leivi'                    => 8,
                'rapallo'                  => 20,
                'cavi di lavagna'          => 19,
                'corso italia'             => 4,
//            'santa margherita ligure'    => 3,
                'leivi - rostio'           => 8,
//            'san colombano di certenoli' => 3,
                'sestri levante'           => 7
            ]
        ];

        if (isset($params[ $foresto ])) {
            $valore = trim(strtolower($params[ $foresto ]));
            if (isset($dict[ $foresto ][ $valore ])) {
                $query[ $corrente ] = $dict[ $foresto ][ $valore ];
            }
        }
    }


    $query = [];
    parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $params);
    populator($query, $params, 'categoria', 'rs_con');
    populator($query, $params, 'tipologia', 'rs_tip');
    populator($query, $params, 'zona', 'rs_com');

    wp_redirect(site_url('/ricerca/risultati/?' . http_build_query($query)), 301);
    die();
}

if (false !== strpos($_SERVER['REQUEST_URI'], 'immobili_scheda.php')) {
    parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $params);
    $annunci = [
        89  => 'a3',
        228 => 'c28', ////////
        213 => 'm3', ///////
        220 => 'i3', ////////
        232 => 'c7', ///////
        227 => 'a4', ///////
        223 => 'g2', //////
        162 => '???????',
        158 => 'b4',
        135 => 'c19',
        164 => 'm1', ///////
        205 => '???????',
        235 => 'f9', //////
        217 => 'g4',
        38  => 'g5',
        108 => '???????',
        233=>'c4', //////
        247=>'???????',
        146=>'14 ******',
        155 => '???????',
        245=>'s18', ///////
        248=>'a1'
    ];

}

get_header();

pr($_SERVER);
?>
<div id="content">
    <div id="content-inner">
        <h2 class="title text-center bft"><span>Pagina non trovata</span></h2>
        <article>
            <div class="entry-content">Siamo spiacenti, la pagina che stai cercando non esiste.</div>
        </article>
    </div>
</div>
<?php get_footer(); ?>
