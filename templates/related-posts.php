<?php

$the_id = get_the_ID();
$the_cat = get_the_category($the_id);
$related = new WP_Query( array( 'category__in' => $the_cat[0]->term_id, 'posts_per_page' => 4, 'post__not_in' => array($the_id) ) ); ?>
<section id="relatedposts-<?php print esc_html( $the_cat[0]->name ); ?>" class="related-posts col s12">
<?php
printf('<span class="related-posts-title"><h4>%1$s</h4></span>',sprintf(__( 'Check out these related <strong>"%s"</strong> posts', 'wordpress-seo' ),$the_cat[0]->name));
if( $related->have_posts() ) :
  while( $related->have_posts() ) :
  	$related->the_post(); ?>
		<article id="post-<?php the_ID(); ?>" class="post related-post">
    	<a href="<?php the_permalink(); ?>">
            <figure id="post-<?php the_ID(); ?>-thumb" class="post-thumb related-post-thumb z-depth-1">
                <?php print get_the_post_thumbnail( get_the_ID(), 'full' ); ?>
            </figure>
            <h4><?php _e(get_the_title(),'wordpress-seo'); ?></h4>
        </a>
        <summary>
        	<p><?php _e(get_the_excerpt(),'wordpress-seo'); ?></p>
        </summary>
    </article>
	<?php endwhile;
endif; ?>
</section>
<?php wp_reset_postdata();