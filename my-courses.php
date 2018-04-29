<?php
/**

Template Name: My Account

**/

if (!is_user_logged_in()) {
	wp_redirect('/');
	die;
}
?>

<?php get_header(); ?>

<?php get_template_part('templates/title'); ?>

<section id="content-wrapper-full">
<?php get_template_part('templates/myaccount/myaccount'); ?>
</section>

<?php get_footer(); ?>