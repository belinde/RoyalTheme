<?php
use Royal\Engine;

$royal = Engine::getInstance();
$post = get_post();
$affitto = false;
?>
    <div id="content">
        <div id="content-inner">
            <div class="annuncio-border">
                <div class="annuncio-recap">
                    <?php
                    $comuni = get_the_terms(get_the_ID(), "comune");
                    $comune = isset($comuni[0]) ? $comuni[0]->name : "&nbsp;";

                    /** @var WP_Term[] $comuni */
                    $tipologie = get_the_terms(get_the_ID(), "tipologia");
                    $tipo = isset($tipologie[0]) ? $tipologie[0]->name : "undefined";

                    /** @var WP_Term[] $contratti */
                    $contratti = get_the_terms(get_the_ID(), "contratto");
                    $contratto = isset($contratti[0]) ? $contratti[0]->name : "undefined";
                    echo "<ul><li><strong>$tipo a $comune</strong></li></ul>";
                    ?>
                    <ul class="price">
                        <?php
                        $fields = $royal->getFields();
                        foreach (['prezzo'] as $slug) {
                            if (isset($fields[ $slug ]) and $fields[ $slug ]->hasValue($post)) {
                                echo '<li><strong>';
                                echo $fields[ $slug ]->getLabel();
                                echo ':&nbsp;';
                                $royal->theSingleInfo($slug);
                                echo '</strong></li>';
                            }
                        }
                        ?>
                    </ul>
                </div>

                <?php
                if ($royal->hasGallery('photos')) {
                    the_slideshow_gallery('photos');
                }
                ?>
            </div> <!-- end annuncio-border-->
            <div class="annuncio-info grid flex-start">
                <div class="col lg-9 grid paddless-left" style="padding-top:0;">
                    <div class="col lg-12" style="padding-left: 0; padding-top:0;">
                        <h3>Descrizione</h3>
                        <?php the_content(); ?>
                    </div>
                    <div class="annuncio-tab-table col lg-12" style="padding-left: 0;">
                        <div class="annuncio-tab active annuncio-caratteristiche" data-tab="caratteristiche">
                            Caratteristiche
                        </div>
                        <?php if ($royal->hasGallery('planimetries')) { ?>
                            <div class="annuncio-tab annuncio-planimetrie" data-tab="planimetrie">Planimetrie
                            </div> <?php } ?>
                        <?php if ($royal->hasMap()) { ?>
                            <div class="annuncio-tab annuncio-mappa" data-tab="mappa">Mappa</div> <?php } ?>

                        <div class="annuncio-tab-content caratteristiche active">
                            <table>
                                <tbody>
                                <?php
                                the_terms($post->ID, 'comune', '<tr><th>Comune:</th><td>', ', ', '</td></tr>');
                                the_terms($post->ID, 'tipologia', '<tr><th>Tipologia:</th><td>', ', ', '</td></tr>');
                                the_terms($post->ID, 'contratto', '<tr><th>Contratto:</th><td>', ', ', '</td></tr>');

                                foreach ($royal->getFields() as $field) {
                                    if ($field->isPublic() and $field->hasValue($post)) {
                                        if ($field->getSlug() == 'indirizzo' and !get_post_meta($post->ID,
                                                $royal->getFields()['mostraindirizzo']->metaSlug(), true)
                                        ) {
                                            continue;
                                        }
                                        echo '<tr><th>' . $field->getLabel() . '</th><td>';
                                        $field->printer($post);
                                        echo '</td></tr>';
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                        if ($royal->hasGallery('planimetries')) {
                            ?>
                            <div class="annuncio-tab-content planimetrie">
                                <?php the_slideshow_gallery('planimetries'); ?>
                            </div>
                            <?php
                        }
                        ?>
                        <?php
                        if ($royal->hasMap()) {
                            ?>
                            <div class="annuncio-tab-content mappa">
                                <?php $royal->theMap(); ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="col lg-3 ask-info">
                    <div>
                        <h3>Richiedi Informazioni</h3>
                        <form class="royalFormInfo">
                            <input type="hidden" class="royalFormAnnuncio"
                                   value="<?php echo esc_attr(get_the_title()); ?>">
                            <input type="text" placeholder="Nome" class="royalFormNome"/>
                            <input type="text" placeholder="Email" class="royalFormEmail"/>
                            <textarea placeholder="Il tuo messaggio" class="royalFormTesto"></textarea>
                            <small>
                                <label>
                                    <input type="checkbox" class="royalFormTerms">
                                    ho letto la <a href="<?php echo site_url('/privacy') ?>" target="_blank">policy
                                        sulla
                                        privacy</a>
                                </label>
                            </small>
                            <input type="submit" value="Invia Messaggio"/>
                            <ul class="royalFormErrori"></ul>
                            <div class="royalFormOk" style="display:none;">Grazie per l'interesse, verrai ricontattato
                                il
                                prima possibile.
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div><!-- end content-inner-->
    </div><!--end content-->
<?php
$json = [
    "@context"    => "http://schema.org",
    "@type"       => "Product",
    "image"       => get_the_post_thumbnail_url(null, 'royaltile'),
    "category"    => strip_tags(get_the_term_list(get_the_ID(), "tipologia", '', ', ')),
    "sku"         => get_the_title(),
    "description" => strip_tags(get_the_content()),
    "url"         => get_post_permalink(get_the_ID()),
    "name"        => "$tipo in $contratto a $comune"
];
echo '<script type="application/ld+json">' . json_encode($json, JSON_PRETTY_PRINT) . '</script>';
