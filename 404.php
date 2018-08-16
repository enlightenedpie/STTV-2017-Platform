<?php get_header(); 
sttv_get_template('stage'); ?>
<div class="content" style="min-height:10em;text-align:center;">
	<span style="display:block;margin-top:6em;">Try using the site menu or any of the links below to get where you're trying to go.</span>
</div>
<div id="latest_posts_404">
<?php
	$args = array( 'numberposts' => '4','post_status' => 'publish' );
	$recent_posts = wp_get_recent_posts( $args );
	foreach( $recent_posts as $recent ){ ?>
		<article id="post-<?php echo $recent['ID']; ?>" class="post related-post">
    	<a href="<?php the_permalink(); ?>">
            <figure id="post-<?php echo $recent['ID']; ?>-thumb" class="post-thumb related-post-thumb z-depth-1">
                <?php print get_the_post_thumbnail( $recent['ID'], 'full' ); ?>
            </figure>
            <h4><?php _e($recent['post_title'],'wordpress-seo'); ?></h4>
        </a>
        <summary>
        	<p><?php _e($recent['post_excerpt'],'wordpress-seo'); ?></p>
        </summary>
    </article>
	<?php }
?>
	
</div>
<?php get_footer(); ?>