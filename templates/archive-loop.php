<article id="post-<?php the_ID(); ?>" class="post stArchivePost row">
    <div class="stArchivePostInner row">
        <figure id="post-<?php the_ID(); ?>-thumb" class="col s12 m6 post-thumb archive-post-thumb z-depth-1">
            <?php print get_the_post_thumbnail( get_the_ID(), 'full' ); ?>
        </figure>
        <summary class="col s12 m6">
            <h4><?php the_title(); ?></h4>
            <p><?php the_excerpt(); ?></p>
        </summary>
    </div>
    <div class="stArchivePostTail row">
        <span>Written by: <?php the_author(); ?></span>
        <a href="<?php the_permalink(); ?>">Read More &raquo;</a>
    </div>
</article>