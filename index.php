<?php get_header(); ?>
<div id="content">
    <div id="content-inner">
        <?php
        while (have_posts()) {
            the_post();
            ?>
            <article>
                <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                <div class="entry-content"><?php the_content(); ?></div>
            </article>
            <?php
        }
        ?>
    </div>
</div>
<?php get_footer(); ?>
