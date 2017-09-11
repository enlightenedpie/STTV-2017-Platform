<?php
/**

##### HOOKS #####

**/

add_action('sttv_pre_footer','sttv_subscribe_cta');
function sttv_subscribe_cta() {
	print '<div id="footer-subscribe-cta" class="row z-depth-1"><div class="col s12"><h4>Join our mailing list for exclusive offers!&nbsp;</h4><a class="pmt-button btn" href="/subscribe"><strong>Subscribe!</strong></a></div></div>';
}