<?php get_header(); 
sttv_get_template('stage');
?><section id="stContentWrapper" class="row">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    	<article id="post-<?php the_ID(); ?>" class="post single-post col l12 xl6 push-xl3">
        	<?php the_content(); ?>
        </article>
    <?php endwhile; endif; ?>
</section>
<div id="stContentMobileReveal" class="row"><button class="readMore">Read More</button></div>
        <?php //sttv_get_template('related-posts'); ?>
<?php get_footer(); ?>