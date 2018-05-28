<?php
/**
 * Template Name: Главная
 *
 * @package webartimmarket
 */

get_header('homepage'); ?>

    <div class="screen products-items" id="homepage-content">
        <?php the_post(); ?>
        <?php the_content(); ?>
    </div>

<?php get_footer();