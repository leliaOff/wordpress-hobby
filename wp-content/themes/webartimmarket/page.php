<?php get_header(); ?>
    


<?php while ( have_posts() ): the_post(); ?>

    <div class="content-block">
        <?php if(!is_front_page()): ?> 
            <h1><?php the_title(); ?></h1>
        <?php endif; ?>
        
        <div class="page-content">
            <?php the_content(); ?>
        </div>

    </div>
                
<?php endwhile; ?>

<?php get_footer(); ?>