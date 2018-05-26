<?php

$byline = '<span itemprop="author" itemscope="" itemtype="http://schema.org/Person" class="author byline">By <a itemprop="name" href="'.esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ).'" title="More about '.get_the_author().'" rel="author">'.get_the_author().'</a> | '.get_the_date().'</span>';

print apply_filters( 'sttv_byline', $byline );