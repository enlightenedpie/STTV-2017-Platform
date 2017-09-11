<main itemscope itemtype="http://schema.org/Article">
<?php 

//$colors = array('azure','seafoam','olive','sunrise','goldenrod','sienna','salmon');
	
	if (is_front_page()) : 
		
	else :
		
		$title = '';
		$meta = '';
		$desc = false;
		
		if (is_404()) :
	
			$title = 'Sorry, we couldn\'t find that page.';
			
		elseif (is_category()) :
			
			$title = 'Category - '.single_cat_title('',false);
			$desc = __(category_description(), 'wordpress-seo');
			
		elseif (is_tag()) :
			$title = 'Tag - '.single_tag_title('',false);
		
		else :
		
			$title = get_the_title();
			$metas = get_the_tags(get_the_ID());
		
		endif; 
			
			?><section id="title-meta">
				<div itemprop="name" id="page-title">
					<h1><?php _e($title, 'wordpress-seo') ?></h1>
				</div>
				<?php if (!is_singular('courses')) : ?>
				<?php yoast_breadcrumb('<div id="breadcrumbs">','</div>'); ?>
                
                	<?php if (is_single()) :
						echo '<hr/>'; ?>
                   <div id="post-meta"><?php get_template_part('templates/byline'); ?>
                	<?php
					
						if ($desc !== false) :
						
							?><span class="category-desc"><?php print $desc; ?></span><?php
							
						elseif (!empty($metas)) : ?>
                        	<div id="post-tags">
								<?php foreach ($metas as $meta) :
                                
                                    ?><a href="<?php print get_tag_link($meta->term_id); ?>"><div style="box-shadow:0px 1px 1px rgba(150, 150, 150, 0.55)" class="chip"><?php print $meta->name; ?></div></a><?php
                                
                                endforeach; ?>
							</div>
						<?php endif;
						
					?>
                </div><?php 
		endif;
	else : ?>
	<div id="course-sub-title"></div>
	<?php endif; ?>
			</section><?php
	endif; ?>
	<div id="content">