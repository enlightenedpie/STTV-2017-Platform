<?php
/* Template Name: Contact Page */
?>

<?php get_header(); ?>
<?php get_template_part('templates/title'); ?>

<section id="content-wrapper-full">
	<div class="row contact-page">
    	<div class="col l6 m12">
			<?php the_content(); ?>
    	</div>
        <div class="col l6 m12">
        	<?php get_template_part('templates/partials/_contactform'); ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>