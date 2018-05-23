<section id="sidebar" class="col s12 m6 l4 xl3">
	<?php wp_nav_menu([
		'theme_location'    => 'tutoring-info',
		'container'     	=> 'div',
		'container_class'   => 'collection row',
		'items_wrap'		=> '%3$s',
		'walker'			=> new Tutoring_Info_Walker()
	]); ?>
	<div class="row">
		<?php dynamic_sidebar('blog-sidebar'); ?>
	</div>
</section>