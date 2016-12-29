<?php
use Royal\Engine;

get_header();
$royal = Engine::getInstance();

the_post();

get_template_part('single-annuncio', get_post_meta(get_the_ID(), 'royal_meta_status', true));

get_footer();