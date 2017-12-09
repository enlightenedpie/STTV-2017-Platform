<?php
$ytlink = get_post_meta(get_the_ID(),'yt_link',true);
	if (is_front_page()) :
	
		print do_shortcode('[smartslider3 slider=1]');
	
	elseif (is_page()) :
		if (!empty($ytlink)) :
		?>
			<span class="sttv-embed-video" style="text-align:center; display: block;">
				<iframe class="youtube-player" width="1920" height="1080" type="text/html" src="https://www.youtube.com/embed/<?php print $ytlink; ?>?version=3&rel=1&fs=1&autohide=2&showsearch=0&showinfo=1&iv_load_policy=1&wmode=transparent&playsinline=1" allowfullscreen="true" style="border:0;"></iframe>
			</span><?php
		endif;
		do_action('sttv_stage_section');
	elseif (is_404()) : ?>
	<span class="stage-404"><?php
		$img_id = get_theme_mod('404_stage_setting');
		print wp_get_attachment_image($img_id,'full');
		?></span>	
	<?php elseif (is_category() || is_tag() || is_search()) :
	
		return false;
	
	elseif (is_singular()) :
		
		if (!empty($ytlink)) : ?>
			<span class="sttv-embed-video" style="text-align:center; display: block;">
				<iframe class="youtube-player" width="1920" height="1080" type="text/html" src="https://www.youtube.com/embed/<?php print $ytlink; ?>?version=3&rel=1&fs=1&autohide=2&showsearch=0&showinfo=1&iv_load_policy=1&wmode=transparent&playsinline=1" allowfullscreen="true" style="border:0;"></iframe>
			</span><?php

		elseif (has_post_thumbnail()) : ?>
			<span id="post-<?php the_ID(); ?>-thumb" class="post-thumbnail post-thumb-top">
				<?php print get_the_post_thumbnail( get_the_ID(), 'full' ); ?>
			</span><?php

		else :

			do_action('sttv_stage_section');

		endif;
		
	else :
	
		do_action('sttv_stage_section');
		
	endif;

do_action('sttv_after_stage');