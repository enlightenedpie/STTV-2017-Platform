<?php
/**

##### HOOKS #####

**/

add_action('sttv_pre_footer','sttv_subscribe_cta');
function sttv_subscribe_cta() {
	$html = '';
	if (!is_page('subscribe')) {
		$html = '<div id="footer-subscribe-cta" class="row z-depth-1"><div class="col s12"><h4>Join our mailing list for exclusive offers!&nbsp;</h4><a class="pmt-button btn waves-effect waves-dark" href="'.site_url().'/subscribe"><strong>Subscribe!</strong></a></div></div>';
	} else {
		$html = '<div id="footer-subscribe-cta" class="row z-depth-1"><div class="col s12"><h4>Is it really the "best" ACT prep course ever?</h4><a class="pmt-button btn waves-effect waves-dark" href="'.site_url().'/the-best-act-prep-course-ever"><strong>Find out!</strong></a></div></div>';
	}
	print $html;
}