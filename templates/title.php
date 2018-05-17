<?php 
if ( !is_front_page() ) {
	
	$title = $meta = $class = '';
	$desc = false;
	//$bc = !(is_singular('courses') || is_page('jobs')) ? yoast_breadcrumb('<div class="col s12" id="breadcrumbs">','</div>') : '';
	
	if (is_404()) {
		$title = 'Sorry, we couldn\'t find that page.';
	} elseif (is_category()) {
		$title = 'Category - '.single_cat_title('',false);
		$desc = category_description();
		$class = 'col s12';
	} elseif (is_tag()) {
		$title = 'Tag - '.single_tag_title('',false);
		$class = 'col s12';
	} else {
		$title = get_the_title();
		$metas = get_the_tags(get_the_ID());
		$class = 'col s12 xl6 pull-xl6';
	} 
?>
<section id="title-meta" class=""><div class="row" id="title-meta-inner">
	<?php !(is_singular('courses') || is_page('jobs')) ? yoast_breadcrumb('<div class="col s12" id="breadcrumbs">','</div>') : ''; ?>
	<div class="col s12" id="page-title">
		<h1><?php print $title; ?></h1>
	</div>
	<?php if ( is_single() ) { ?>
	<div class="col s12" id="post-meta"><?php 
	
		get_template_part('templates/byline');
	
		if ($desc !== false) {
		
			?><span class="category-desc"><?php print $desc; ?></span><?php
			
		} elseif ( !empty( $metas ) ) { 
	
	?>
		<div id="post-tags">
			<?php 
				foreach ($metas as $meta) {
				
					?><a href="<?php print get_tag_link($meta->term_id); ?>"><div class="post-tag"><?php print $meta->name; ?></div></a><?php
				
				} 
			?>
		</div>
	<?php } ?>
	</div><?php } ?>
</div></section><?php }