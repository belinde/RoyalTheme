<?php get_header(); ?>
<div id="content">
    <div id="content-inner">
        <?php
        while (have_posts()) {
            the_post();
            ?>
            <?php the_title('<h2 class="title text-center bft"><span>', '</span></h2>'); ?>
            <article>
                <div class="entry-content"><?php the_content(); ?></div>
            </article>
            <?php
        }
        ?>
    </div>
</div>
<?php get_footer(); ?>
