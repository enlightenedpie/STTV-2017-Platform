<?php 

/* Template Name: Signup Page */
add_filter( 'body_class', function( $classes ) {
    return array_merge( $classes, [ 'checkout-modal' ] );
} );
get_header();
get_template_part('templates/title');
?><div id="content-wrapper-signup" class="col s12">
    <?php the_content(); ?>
</div><?php 
get_footer();