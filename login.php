<?php 
/* Template Name: Login Template */
add_filter( 'body_class', function( $classes ) {
    return array_merge( $classes, [ 'login-redirect-modal' ] );
} );
get_header('stripped');
get_footer('stripped');