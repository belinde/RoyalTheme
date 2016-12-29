<?php
/** @var WP_Term[] $comuni */
$comuni = get_the_terms(get_the_ID(), "comune");
$comune = isset($comuni[0]) ? $comuni[0]->name : "&nbsp;";

/** @var WP_Term[] $comuni */
$tipologie = get_the_terms(get_the_ID(), "tipologia");
$tipo = isset($tipologie[0]) ? $tipologie[0]->slug : "undefined";
?>
<div class="item col lg-3 md-3 sm-4 xs-6">
    <a href="<?php echo esc_url(get_the_permalink()) ?>">
        <div class="item-cover-image" style="background-image: url('css/images/flat/05.jpg')">
            <span class="item-price"><?php the_single_info("prezzo"); ?></span>
        </div>
        <div class="item-info">
            <span class="item-type ico-<?php echo $tipo; ?>"></span>
            <span class="item-location"><?php echo $comune; ?></span>
        </div>
        <div class="item-desc"><?php the_excerpt(); ?></div>
    </a>
</div>