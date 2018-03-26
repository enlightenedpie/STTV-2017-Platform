<?php 

/* Template Name: MU License Purchase */

?>
<?php get_header(); ?>
<?php get_template_part('templates/title'); ?>

<div id="content-wrapper-signup" class="col s12">
    <?php the_content(); ?>
</div>
<?php do_action( 'mu_signup_after_content' ); ?>
<?php get_footer(); ?>