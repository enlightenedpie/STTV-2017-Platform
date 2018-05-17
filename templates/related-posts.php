<?php

$the_id = get_the_ID();
$the_cat = get_the_category($the_id);
$related = new WP_Query( array( 'category__in' => $the_cat[0]->term_id, 'posts_per_page' => 12, 'post__not_in' => array($the_id) ) ); 
$rcount = 0; ?>
<section id="relatedposts-<?php print esc_html( $the_cat[0]->name ); ?>" class="related-posts row">
<?php
printf('<div class="related-posts-title"><h5>%1$s</h5></div>',sprintf(__( 'Check out these related <strong>"%s"</strong> posts', 'wordpress-seo' ),$the_cat[0]->name));
if( $related->have_posts() ) :
    while( $related->have_posts() ) :
        ++$rcount;
  	    $related->the_post(); ?>
        <?php if ( $rcount === 1 ) { ?>
            <div class="row">
        <?php } ?>
		<article id="post-<?php the_ID(); ?>" class="post related-post col s12 m6 xl3">
            <a href="<?php the_permalink(); ?>">
                <figure id="post-<?php the_ID(); ?>-thumb" class="post-thumb related-post-thumb z-depth-1">
                    <?php print get_the_post_thumbnail( get_the_ID(), 'full' ); ?>
                </figure>
                <h4><?php the_title(); ?></h4>
            </a>
            <summary>
                <p><?php the_excerpt(); ?></p>
            </summary>
        </article>
        <?php if ( $rcount === 4 ) { ?>
            </div>
        <?php $rcount = 0; } ?>
	<?php endwhile;
endif; ?>
</section>
<?php wp_reset_postdata();