<?php 
get_header();
$rcount = 0;
?><section id="content-wrapper" class="archive-page archive row">
        <?php if ( have_posts() ) :
            while ( have_posts() ) :
                ++$rcount;
                the_post();
                include STTV_TEMPLATE_DIR.'archive-loop.php';
            endwhile;
        endif; ?>
    </section>
<?php get_footer(); ?>