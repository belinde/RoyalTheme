<?php
/**
 * Created by PhpStorm.
 * User: belinde
 * Date: 13/12/16
 * Time: 21.57
 */

namespace Royal\Widgets;

use Royal\Engine;
use Royal\Tools;

/**
 * Class MenuAnnunci
 * @package Royal\Widgets
 */
class MenuAnnunci extends \WP_Widget
{
    use Tools;
    /**
     * @var \WP_Term[]
     */
    private $contratti = [];
    /**
     * @var \WP_Term[]
     */
    private $comuni = [];
    /**
     * @var \WP_Term[]
     */
    private $tipologie = [];

    /**
     * Sets up the widgets name etc
     */
    public function __construct()
    {
        parent::__construct(
            'royal_menu_annunci',
            'Menù annunci',
            [
                'classname'   => 'royal_menu_annunci',
                'description' => 'Menù degli annunci, ordinati per contratto, categoria e comune',
            ]
        );
    }

    public function printer()
    {
        global $wpdb;
        foreach (get_terms('comune') as $term) {
            $this->comuni[ $term->term_id ] = $term;
        }
        foreach (get_terms('contratto') as $term) {
            $this->contratti[ $term->term_id ] = $term;
        }
        foreach (get_terms('tipologia') as $term) {
            $this->tipologie[ $term->term_id ] = $term;
        }
        $data = $wpdb->get_results("
		SELECT
		    p.ID,
		    tr.term_taxonomy_id,
		    tt.term_id,
		    tt.taxonomy,
		    t.name
		FROM
		    {$wpdb->posts} AS p
		    INNER JOIN {$wpdb->postmeta} AS pm
		        ON pm.post_id = p.ID
		        AND pm.meta_key = 'royal_meta_status'
		        AND pm.meta_value = 'disponibile'
		    LEFT JOIN {$wpdb->term_relationships} AS tr
		        ON tr.object_id = p.ID
		    LEFT JOIN {$wpdb->term_taxonomy} AS tt
		        ON tt.term_taxonomy_id = tr.term_taxonomy_id
		    LEFT JOIN {$wpdb->terms} AS t
		        ON t.term_id = tt.term_id
		WHERE p.post_status = 'publish'
		    AND p.post_type = 'annuncio'");

        $structured = [];
        $posts = [];
        foreach ($data as $row) {
            $posts[ $row->ID ][ $row->taxonomy ][ $row->term_id ] = $row->name;
            if ($row->taxonomy == 'contratto') {
                $structured[ $row->term_id ]['posts'][ $row->ID ] = $row->ID;
            }
        }
        foreach ($structured as $contratto => $rowContratto) {
            foreach ($rowContratto['posts'] as $postId) {
                foreach ($posts[ $postId ]['tipologia'] as $tipologia => $tiponame) {
                    $structured[ $contratto ][ $tipologia ]['posts'][ $postId ] = $postId;
                }
            }
            unset($structured[ $contratto ]['posts'], $tiponame);
            foreach ($structured[ $contratto ] as $tipologia => $rowTipologia) {
                foreach ($rowTipologia['posts'] as $postId) {
                    foreach ($posts[ $postId ]['comune'] as $comune => $comunename) {
                        $structured[ $contratto ][ $tipologia ][ $comune ][ $postId ] = $postId;
                    }
                }
                unset($structured[ $contratto ][ $tipologia ]['posts'], $comunename);
            }
        }

        ?>
        <div class="immobili-menu-trigger toggler-flats">
            <span class="ico-dehaze"></span>
            <span>Immobili</span>
        </div>
        <div class='immobili_menu_container toggler-flats'>
            <div class="immobili_menu_inner">
                <div class="immobili_menu_header">
                    <h2>
                        <?php
                        $quest = array_keys($structured);
                        $currentSel = isset($_GET['rs_con']) ? $_GET['rs_con'] : 0;
                        foreach (array_keys($structured) as $row => $contratto) {
                            $label = $this->contratti[ $contratto ]->name;
                            $slug = $this->contratti[ $contratto ]->slug;
                            $active = ($this->contratti[ $contratto ]->term_id  == $currentSel) ? ' active' : '';
                            echo '<a class="immobili_contratto immobili_' . $slug . $active . '" href="?rs_con=' . $this->contratti[ $contratto ]->term_id . '">' . $label . '</a>';
                            if (isset($quest[ $row + 1 ])) {
                                echo '<span class="immobili_separatore">/</span>';
                            }
                        }
                        ?>
                    </h2>
                </div>

                <div class='immobili_menu menu_vendite visible'>
                    <?php
                    $row = 0;
                    foreach ($structured as $contratto => $listaContratto) {
                        $active = ($this->contratti[ $contratto ]->term_id  == $currentSel) ? ' visible' : '';
                        printf('<div class="immobili_menu menu_%s">', $this->contratti[ $contratto ]->slug . $active);

                        foreach ($listaContratto as $tipologia => $listaTipologia) {
                            echo '<ul>';

                            echo '<li class="immobili_title">';
                            echo '<span class="ico-' . $this->tipologie[ $tipologia ]->slug . '"></span>';
                            echo '<span>';
                            $this->linker($contratto, $tipologia);
                            echo '</span>';
                            echo '</li>';

                            foreach ($listaTipologia as $comune => $listaPosts) {
                                echo '<li>';
                                $this->linker($contratto, $tipologia, $comune);
                                echo '<span class="immobili_quantity">' . count($listaPosts) . '</span>';
                                echo '</li>';
                            }

                            echo '</ul>';
                        }
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        echo $args['before_title'] . "Menù annunci" . $args['after_title'];
        $this->printer();
        echo $args['after_widget'];
    }

    /**
     * @param $contratto
     * @param null $tipologia
     * @param null $comune
     */
    private function linker($contratto, $tipologia = null, $comune = null)
    {
        $url = add_query_arg([
            'rs_con' => $contratto,
            'rs_tip' => $tipologia,
            'rs_com' => $comune
        ], site_url(Engine::URL_RISULTATI));
        $label = $this->contratti[ $contratto ]->name;
        if ($tipologia) {
            $label = $this->tipologie[ $tipologia ]->name;
        }
        if ($comune) {
            $label = $this->comuni[ $comune ]->name;
        }
        echo $this->htmlTag('a', ['href' => $url], $label);
    }
}