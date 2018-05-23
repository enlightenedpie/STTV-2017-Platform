<?php

printf( 
    '<span itemprop="author" itemscope="" itemtype="http://schema.org/Person" class="author byline">%1$s <a itemprop="name" href="%2$s" title="%3$s" rel="author">%4$s</a> | %5$s</span>', 
    esc_html__( 'By', 'wordpress-seo' ),
    esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
    esc_attr( sprintf( __( 'More about %s', 'wordpress-seo' ), get_the_author() ) ),
    get_the_author(),
    get_the_date()
);