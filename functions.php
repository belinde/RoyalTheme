<?php
use Royal\Engine;

/**
 * @param $obj
 */
function pr($obj)
{
    echo '<pre>';
    ob_start('htmlentities');
    print_r($obj);
    ob_end_flush();
    echo '</pre>';
}

/**
 * @param $obj
 */
function vd($obj)
{
    echo '<pre>';
    ob_start('htmlentities');
    var_dump($obj);
    ob_end_flush();
    echo '</pre>';
}

spl_autoload_register(function ($class) {
    $file = realpath(
        __DIR__ . DIRECTORY_SEPARATOR .
        str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php'
    );
    if ($file) {
        require_once $file;
    }
});
/**
 * @param $postId
 *
 * @return string
 */
function descrizioneAnnuncio($postId)
{
    $tipologie = get_the_terms($postId, 'tipologia');
    $comuni = get_the_terms($postId, 'comune');
    $contratti = get_the_terms($postId, 'contratto');

    return sprintf(
        "%s in %s a %s",
        isset($tipologie[0]->name) ? $tipologie[0]->name : 'Immobile',
        isset($contratti[0]->name) ? $contratti[0]->name : 'disponibilità',
        isset($comuni[0]->name) ? $comuni[0]->name : 'disposizione'
    );
}

/**
 * @param string $slug
 */
function the_single_info($slug)
{
    Engine::getInstance()->theSingleInfo($slug);
}

/**
 * @param string $type photos, planimetries
 */
function the_slideshow_gallery($type = 'photos')
{
    ?>
    <div class="annuncio-slideshow <?php echo $type; ?>">
        <span class="controls slide-prev"><span class="ico ico-keyboard_arrow_left"></span></span>
        <span class="controls slide-next"><span class="ico ico-keyboard_arrow_right"></span></span>
        <div class="annuncio-slideshow-inner">
            <?php Engine::getInstance()->theGallery($type); ?>
        </div>
    </div>
    <?php
}

/**
 * @param array $query
 * @param string $queryPar
 * @param string $taxonomy
 */
function royalQueryOverrider(&$query, $queryPar, $taxonomy)
{
    if (isset($_GET[ $queryPar ])) {
        $query['tax_query'][] = [
            'terms'    => [absint($_GET[ $queryPar ])],
            'field'    => 'term_id',
            'taxonomy' => $taxonomy
        ];
    }
}

Royal\Engine::getInstance();
