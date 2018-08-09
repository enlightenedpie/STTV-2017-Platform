<?php

/* Template Name: Full Width (Full Header) */

get_header(); 
get_template_part('templates/stage');
?><section id="stFWContent" class="row">
	<div id="stFWContentInner">
		<?php the_content(); ?>
	</div>
</section>
<?php get_footer(); ?>