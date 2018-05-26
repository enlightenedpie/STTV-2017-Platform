<?php 
if ( !is_front_page() ) {
	
	$meta = $archive = '';
	$desc = false;
	$class = 'col s12 xl6 pull-xl6';
	$metas = get_the_tags(get_the_ID());
	$title = get_the_title();
	
	if (is_404()) {
		$title = 'Sorry, we couldn\'t find that page.';
	} elseif (is_category()) {
		$title = 'Category - '.single_cat_title('',false);
		$desc = category_description();
		$class = 'col s12';
		$archive = 'm9';
	} elseif (is_tag()) {
		$title = 'Tag - '.single_tag_title('',false);
		$desc = tag_description();
		$class = 'col s12';
		$archive = 'm9';
	} elseif ( is_page('jobs') ) {
		$archive = 'xl6';
	}
?>
<section id="title-meta" class="col s12 xl6 <?php if (is_single()){echo 'pull-xl6';} ?>"><div class="row" id="title-meta-inner">
	<?php !(is_singular('courses') || is_page('jobs')) ? yoast_breadcrumb('<div class="col s12" id="breadcrumbs">','</div>') : ''; ?>
	<div class="col s12" id="page-title">
		<h1><?php print $title; ?></h1>
	</div>
	<div class="col s12<?php echo ' '.$archive; ?>" id="post-meta"><?php 
		if ( is_singular() ) {
			get_template_part('templates/byline');
		}
	
		if ($desc !== false) {
		
			?><span class="category-desc"><?php print $desc ?: 'No archival description provided.'; ?></span><?php
			
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
	</div>
</div></section><?php }