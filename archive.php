<?php 
get_header();
sttv_get_template('stage');
?><section id="stContentWrapper" class="archive-page archive row">
    <div class="col s12 l8">
    <?php if ( have_posts() ) :
        while ( have_posts() ) :
            the_post();
            include STTV_TEMPLATE_DIR.'archive-loop.php';
        endwhile;
    endif; ?>
    </div>
    <div class="col s12 l4"></div>
</section>
<?php get_footer(); ?>