<?php
/**
 * The main template file.
 *
 * @package webartimmarket
 */

get_header(); ?>

<div class="content single">
    <?php the_post(); ?>
    <h1><?php the_title(); ?></h1>
    <?php the_content(); ?>
</div>

<?php get_footer();