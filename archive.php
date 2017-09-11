<?php get_header(); ?>
<?php get_template_part('templates/title'); ?>
	<section id="content-wrapper" class="col s12 m9">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" class="post category-post">
            <a href="<?php the_permalink(); ?>">
                <figure id="post-<?php the_ID(); ?>-thumb" class="z-depth-2 post-thumb category-post-thumb">
                    <?php print get_the_post_thumbnail( get_the_ID(), 'full' ); ?>
                </figure>
                <h4><?php _e(get_the_title(),'wordpress-seo'); ?></h4>
            </a>
            <summary>
                <p><?php _e(get_the_excerpt(),'wordpress-seo'); ?></p>
            </summary>
        </article>
        <?php endwhile; endif; ?>
    <?php /* ?><div class="sttv-pag" id="pagination_post"><?php sttv_pagination(); ?></div><?php */ ?>
    </section>
    	<?php get_sidebar(); ?>
<?php get_footer(); ?>