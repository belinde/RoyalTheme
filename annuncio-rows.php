<?php
/** @var WP_Term[] $comuni */
use Royal\Engine;

$comuni = get_the_terms(get_the_ID(), "comune");
$comune = isset($comuni[0]) ? $comuni[0]->name : "&nbsp;";

/** @var WP_Term[] $comuni */
$tipologie = get_the_terms(get_the_ID(), "tipologia");
$tipo = isset($tipologie[0]) ? $tipologie[0]->slug : "undefined";

/** @var WP_Term[] $contratti */
$contratti = get_the_terms(get_the_ID(), "contratto");
$affitto = (isset($contratti[0]) and $contratti[0]->slug == "affitto");

?>
<div class="item-row col lg-12 md-12 sm-12 xs-12">
    <a href="<?php echo esc_url(get_the_permalink()) ?>">
        <div class="item-row-cover-image" style="background-image: url('<?php the_post_thumbnail_url('royaltile') ?>')">
        </div>
    </a>
    <div>
        <div class="item-row-info">
            <span
            class="item-row-price"><?php the_single_info("prezzo"); ?><?php echo($affitto ? '/mese' : ''); ?></span>
            <div>
                <span class="item-row-location"><?php echo $comune; ?></span>
            </div>
            <div>
                <table>
                    <tbody>
                        <tr>
                            <?php
                            $royal = Engine::getInstance();
                            $fields = $royal->getFields();
                            if ($fields['vani']->hasValue($post)) {
                                echo '<td><strong>';
                                echo $fields['vani']->getLabel();
                                echo ':</strong>&nbsp;';
                                $royal->theSingleInfo('vani');
                                echo '</td>';
                            }
                            if ($fields['superficie']->hasValue($post)) {
                                echo '<td style="padding-left: 5px;"><strong>';
                                echo $fields['superficie']->getLabel();
                                echo ':</strong>&nbsp;';
                                $royal->theSingleInfo('superficie');
                                echo '</td>';
                            }
                            ?>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="item-row-excerpt"><?php echo the_excerpt();?></div>
            <div style="text-align: right">
                <a class="btn" href="<?php echo esc_url(get_the_permalink()) ?>">Vedi foto e dettagli</a>
            </div>
        </div>
        <div class="item-row-desc">
        </div>
    </div>
</div>
