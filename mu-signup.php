<?php 

/* Template Name: MU Student Signup */

add_filter( 'wpseo_robots', function() { return 'noindex, nofollow'; } );
add_action( 'wp_head', function(){
    print '<script>var muWelcome = "Welcome students of '.get_the_title().'!"</script>';
}, 999);
get_header('stripped'); ?>
<section id="stFWStrippedInner" class="row">
<?php the_content(); ?>
</section>
<?php get_footer('stripped');