<?php get_template_part('templates/head'); ?>
<header class="row" id="stHeaderStripped">
    <div id="stHeaderStrippedInner">
        <section id="stHeaderBranding" class="col s12">
            <a href="<?php echo site_url(); ?>">
                <img src="<?php header_image(); ?>" alt="<?php echo get_bloginfo('name');?>" />
            </a>
        </section>
        <section id="stHeaderStrippedHeadline" class="col s12">
            <h1><?php the_title(); ?></h1>
            <span><?php the_excerpt(); ?></span>
        </section>
    </div>
</header>
<div id="not-header-wrapper" class="row">