<?php
/* Template Name: Contact Page */ 

add_action( 'sttv_stage_extras', function(){
    global $post;
	$thumb = get_the_post_thumbnail_url($post->ID);
	if ( $thumb ) {
		print 'style="background-image:url('.$thumb.');background-position:center;box-shadow:inset 0 0 0 10000px rgba(0,0,0,0.5)"';
	}
});
get_header();
?>
<section id="content-wrapper" class="row">
	<div class="col l12 xl6 push-xl3 contact-page">
    	<div class="col s12">
			<?php the_content(); ?>
    	</div>
        <div class="col s12">
        	<?php get_template_part('templates/partials/_contactform'); ?>
        </div>
    </div>
</section>
<?php get_footer(); ?>