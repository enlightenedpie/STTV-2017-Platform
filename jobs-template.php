<?php 

/* Template Name: Jobs Template */

get_header();
global $wp_query;
?>
<?php get_template_part('templates/title'); ?>
<section id="content-wrapper-full">
<?php print_r($wp_query->query); print_r($stjob); ?>
</section>
<?php get_footer(); ?>