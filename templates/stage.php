<?php
$ytlink = get_post_meta(get_the_ID(),'yt_link',true);
?><section class="row" id="stage" <?php do_action( 'sttv_stage_extras' ); ?>><div class="row" id="stage-inner"><?php

	if ( is_front_page() ) :
	
		print do_shortcode( '[smartslider3 slider=1]' );

	elseif ( is_404() ) : ?>
	<span class="stage-404"><?php print wp_get_attachment_image( get_theme_mod( '404_stage_setting' ), 'full' ); ?></span>	
	<?php elseif (is_category() || is_tag() || is_search()) :
	
	elseif ( is_single() ) :
		
		if (!empty($ytlink)) : ?>
			<div class="sttv-embed-video col s12 xl6 push-xl6">
				<iframe class="youtube-player" width="1920" height="1080" type="text/html" src="https://www.youtube.com/embed/<?php print $ytlink; ?>?version=3&rel=1&fs=1&autohide=2&showsearch=0&showinfo=1&iv_load_policy=1&wmode=transparent&playsinline=1" allowfullscreen="true" style="border:0;"></iframe>
			</div><?php

		elseif (has_post_thumbnail()) : ?>
			<div id="cover-img" class="col s12 xl6 push-xl6" style="background-image: url(<?php print get_the_post_thumbnail_url( get_the_ID(), 'full' ); ?>); background-position: 49% 29% !important"></div>
		<?php

		endif; ?>
		<?php
	endif;

	get_template_part('templates/title');
	
	do_action('sttv_stage_section');

?></div></section><?php

do_action('sttv_after_stage');

?><div id="content" class="row"><?php

do_action( 'sttv_ad' );