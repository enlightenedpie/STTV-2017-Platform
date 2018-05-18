<?php if ( $rcount === 1 ) { ?>
    <div class="row">
<?php } ?>
<article id="post-<?php the_ID(); ?>" class="post archive-post col s12 xl3">
    <div class="archive-inner row">
        <a href="<?php the_permalink(); ?>">
            <figure id="post-<?php the_ID(); ?>-thumb" class="col s12 m6 xl12 post-thumb archive-post-thumb">
                <?php print get_the_post_thumbnail( get_the_ID(), 'full' ); ?>
            </figure>
            <summary class="col s12 m6 xl12">
                <h4><?php the_title(); ?></h4>
                <p><?php the_excerpt(); ?></p>
            </summary>
        </a>
    </div>
</article>
<?php if ( $rcount === 4 ) { ?>
    </div>
<?php $rcount = 0; } ?>