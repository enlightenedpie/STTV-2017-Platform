<?php 

/* Template Name: Tutoring Info Template */

add_filter( 'body_class', function( $classes ) {
    return array_merge( $classes, [ 'page-tutoring-info' ] );
} );

add_action( 'sttv_stage_extras', function(){
    global $post;
    $thumb = get_the_post_thumbnail_url($post->ID,'full');
    if ( $thumb ) {
        print 'style="background-image:url('.$thumb.')"';
    }
});

get_header(); 
get_sidebar(); ?>
<section id="content-wrapper" class="col s12 m6 l8 xl9">
	<?php the_content(); ?>
</section>

<?php get_footer(); ?>