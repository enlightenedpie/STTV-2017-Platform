<?php 

/* Template Name: Front Page */

get_header(); ?>
<section id="st-stage-front" class="row">
	<div class="parallax-container col s12">
		<div class="st-parallax-inner row">
			<div class="st-parallax-content col s12 l6">
				<h1><?php echo get_theme_mod('parallax_block1_title'); ?></h1>
				<span class="st-parallax-text-block"><?php echo get_theme_mod('parallax_block1_text'); ?></span>
			</div>
		</div>
		<div class="parallax"><img class="tinted" src="<?php echo wp_get_attachment_url(get_theme_mod('parallax_block1_img')); ?>"/></div>
    </div>
</section>
<section id="st-banners-front" class="row">
	<div id="st-banners-inner" class="row z-depth-5">
		<div id="act-test-block" class="st-test-block col s6 l4">The next ACT is </div>
		<div id="sat-test-block" class="st-test-block col s6 l4">The next SAT is </div>
		<div id="mailinglist-block" class="st-test-block col s12 l4">SUBSCRIBE to our mailing list</div>
	</div>
</section>
<div id="content" class="row">
	Stuff and things
<?php get_footer(); ?>