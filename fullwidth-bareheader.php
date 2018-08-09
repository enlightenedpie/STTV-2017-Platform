<?php

/* Template Name: Full Width (Bare Header) */

add_filter( 'body_class', function($classes){
	$classes[] = 'stNoCTA';
	return $classes;
});

get_header('bare'); ?>
<section id="stFWContent" class="row">
	<div id="stFWContentInner">
		<?php the_content(); ?>
	</div>
</section>
<?php get_footer('nocta'); ?>