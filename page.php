<?php get_header(); ?>
<?php get_template_part('templates/title'); ?>

<section id="content-wrapper" class="col s12 m9">
	<?php the_content(); ?>
</section>
	<?php get_sidebar(); ?>

<?php get_footer(); ?>