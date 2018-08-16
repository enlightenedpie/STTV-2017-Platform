<?php
global $post;
add_filter( 'body_class', function( $classes ) {
    return array_merge( $classes, [ 'noStageGrad' ] );
}, 999 );

get_header();
sttv_get_template('stage');
?><section id="stContentWrapper" class="row">
    <div class="stBlockCentered" id="st<?php echo stCamelCase($post->post_name); ?>"><?php the_content(); ?></div>
</section>
<?php get_footer();