<?php get_header(); ?>
	<section id="content-wrapper" class="row">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    	<article id="post-<?php the_ID(); ?>" class="post single-post col l12 xl6 push-xl3">
        	<?php the_content(); ?>
        </article>
    <?php endwhile; endif; ?>
</section><div id="content-mobile-reveal" class="row"><button class="read-more">Read More</button></div>
        <?php get_template_part('templates/related-posts'); ?>
<?php get_footer(); ?>