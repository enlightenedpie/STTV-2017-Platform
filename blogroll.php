<?php

/* Template Name: Blog Roll */

get_header();

$roll = new WP_Query(array ( 'orderby' => 'date', 'order' => 'DESC'));

if ( $roll->have_posts() ) : while ( $roll->have_posts() ) : $roll->the_post() ?>
		<article id="" class="post post-"><?php the_post_thumbnail(); ?></article>



<?php endwhile; endif;

get_footer();