<?php

/*function sttv_custom_endpoints() {
    add_rewrite_endpoint( 'courses', EP_ROOT | EP_PAGES );
}

add_action( 'init', 'sttv_custom_endpoints' );

function my_custom_query_vars( $vars ) {
    $vars[] = 'courses';

    return $vars;
}
add_filter( 'query_vars', 'my_custom_query_vars', 0 );*/


add_action( 'rest_api_init', 'sttv_api_user_meta' );
 
function sttv_api_user_meta() {
 
    // register_rest_field ( 'name-of-post-type', 'name-of-field-to-return', array-of-callbacks-and-schema() )
    register_rest_field( 'user', 'usermeta', array(
           'get_callback'    => 'get_user_meta_for_api',
           'schema'          => 'meta',
        )
    );
}
 
function get_user_meta_for_api( $object ) {
    //get the id of the user object array
    $user_id = $object['id'];
 
    //return the user meta
    return get_user_meta( $user_id );
}