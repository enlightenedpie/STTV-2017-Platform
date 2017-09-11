<?php 

/* Template Name: Front Page */

?>

<?php get_header(); ?>
<?php //print_r(get_user_meta(get_current_user_id()));?>
<main itemscope itemtype="http://schema.org/WebSite">
    <div id="content">
		<div id="front-main">
        	<section id="triptych" class="row"><?php 
				$pos_d = array('left','center','right');
				
				foreach ($pos_d as $pos) :
					echo "<div id=\"{$pos}_panel\" class=\"col s12 m4\">",
							"<div class=\"center promo promo-example\">",
								"<div class=\"tripicon-wrap col s12\">",
									get_theme_mod("triptych_section_{$pos}_img"),
								"</div>",
								"<div class=\"triptext\">",
									"<h5>",
										get_theme_mod("triptych_section_{$pos}_text"),
									"</h5>",
								"</div>",
							"</div>",
						"</div>";
				endforeach; 
			?>
            </section>
            	<div class="divider"></div>
            <section id="billboard_pane_1" class="row billboard valign-wrapper">
            	<div class="col s12 m7 push-m5"><?php echo wp_get_attachment_image(get_theme_mod("billboard_one_img"),"full","",array("class"=>"col s8 offset-s2")); ?></div>
            	<div class="col s12 m5 pull-m7">
                	<span class="bb-inner"><?php echo get_theme_mod('billboard_one_mce'); ?></span>
                </div>
            </section>
            	<div class="divider"></div>
            <section id="billboard_pane_2" class="row billboard valign-wrapper">
            	<div class="col s12 m7"><?php echo wp_get_attachment_image(get_theme_mod("billboard_two_img"),"full","",array("class"=>"col s12")); ?></div>
            	<div class="col s12 m5">
                	<span class="bb-inner"><?php echo get_theme_mod('billboard_two_mce'); ?></span>
                </div>
            </section>
            	<div class="divider"></div>
		</div>
		<div id="front-latest-post" class="row"><h2>Our Latest Blog Post</h2><?php
			$args = array( 'numberposts' => '1','post_status' => 'publish' );
			$recent_posts = wp_get_recent_posts( $args );
			foreach( $recent_posts as $recent ){ ?>
				<article id="post-<?php echo $recent['ID']; ?>" class="post col s12">
				<a href="<?php echo get_the_permalink($recent['ID']); ?>">
					<figure id="post-<?php echo $recent['ID']; ?>-thumb" class="post-thumb col s12 m6 l3 xl4 z-depth-1">
						<?php print get_the_post_thumbnail( $recent['ID'], 'full' ); ?>
					</figure>
				</a>
				<summary class="col s12 m6 l9 xl8">
					<h4><a href="<?php echo get_the_permalink($recent['ID']); ?>"><?php _e($recent['post_title']); ?></a></h4>
					<p><?php _e(substr(wp_strip_all_tags($recent['post_content']),0,500)); ?>...</p>
				</summary>
			</article>
	<?php } ?></div>
<?php get_footer(); ?>