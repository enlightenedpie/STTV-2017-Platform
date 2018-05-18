<?php

$the_id = get_the_ID();
$the_cat = get_the_category($the_id);
$related = new WP_Query( array( 'category__in' => $the_cat[0]->term_id, 'posts_per_page' => 12, 'post__not_in' => array($the_id) ) ); 
$rcount = 0; ?>
<section id="relatedposts-<?php print esc_html( $the_cat[0]->name ); ?>" class="related-posts archive row">
<?php
printf('<div class="related-posts-title"><h5>%1$s</h5></div>',sprintf(__( 'Check out these related <strong>"%s"</strong> posts', 'wordpress-seo' ),$the_cat[0]->name));
if( $related->have_posts() ) :
    while( $related->have_posts() ) :
        ++$rcount;
  	    $related->the_post();
        include STTV_TEMPLATE_DIR.'archive-loop.php';
	endwhile;
endif; ?>
</section>
<?php wp_reset_postdata();