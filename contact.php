<?php

/* Template Name: Contact Page */ 

get_header(); ?>
<section id="st-contact-stage" class="st-stage row">
    <h1><?php the_title(); ?></h1>
    <span><?php the_excerpt(); ?></span>
</section>
<section id="content-wrapper" class="row"><?php the_content(); ?></section>
<?php get_footer(); ?>