<?php

###############################################
##### ENQUEUE ALL STYLES AND SCRIPTS HERE #####
###############################################

add_action('admin_enqueue_scripts', 'sttv_admin_scripts');
function sttv_admin_scripts($hook) {
	wp_dequeue_script('jquery');
	wp_deregister_script('jquery');
	
	//jquery scripts
	wp_enqueue_script('jquery','https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js',false,null);
	wp_enqueue_script('sttv-js', get_stylesheet_directory_uri().'/sttv-js.min.js','jquery',null,true);
	wp_enqueue_script('courses-admin',get_stylesheet_directory_uri().'/s/admin/courses.js','jquery',time(),true);
}

add_action('wp_enqueue_scripts','sttv_enqueue_all');
function sttv_enqueue_all() {
	//dequeue
	wp_dequeue_script('jquery');
	wp_deregister_script('jquery');
	
	
	//jquery scripts
	wp_enqueue_script('jquery','https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js',false,null);
	wp_enqueue_script('sttv-js-main', get_stylesheet_directory_uri().'/scripts/sttv-js.min.js','jquery',STTV_VERSION,true);
	wp_enqueue_script('materialize-js', 'https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js','jquery',null);
	wp_enqueue_script('sttv-stripe-js', 'https://js.stripe.com/v3/',null,null,false);
	wp_enqueue_script('jq-validate','https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js','jquery');
	
	//styles
	wp_enqueue_style('materialize','https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css',false,null);
	wp_enqueue_style('sttv-main', get_stylesheet_directory_uri().'/styles/main.min.css', 'materialize', STTV_VERSION);
	wp_enqueue_style('material-icons', 'https://fonts.googleapis.com/icon?family=Material+Icons', 'materialize', STTV_VERSION);
	wp_enqueue_style('font-awesome', 'https://use.fontawesome.com/releases/v5.0.13/css/all.css', 'materialize', STTV_VERSION);
	wp_enqueue_style('dashicons');

}

add_filter( 'script_loader_tag', 'add_atts_to_tags', 10, 3 );
function add_atts_to_tags( $tag, $handle, $src ) {
	if ( 'sttv-js-main' === $handle ) {
		return '<script type="text/javascript" src="' . esc_url( $src ) . '" id="'.$handle.'"></script>';
	}
	return $tag;
}

add_filter( 'style_loader_tag', 'add_atts_to_styles', 10, 4 );
function add_atts_to_styles( $html, $handle, $href, $media ) {
	if ( 'font-awesome' === $handle ) {
		return substr_replace( $html, ' integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous" ', -2, 0 );
	}
	return $html;
}

add_action( 'login_enqueue_scripts', 'sttv_login_brand' );
function sttv_login_brand() { 
?> 
	<style type="text/css"> 
		body.login div#login h1 a {
			background-image: url(<?php header_image(); ?>);
			background-size: contain;
			display: block;
			width: 100%; 
		}
	</style>
<?php 
}

#################################
##### FOOTER INLINE SCRIPTS #####
#################################

add_action( 'wp_footer', 'footer_ga_script', 999 );
function footer_ga_script() { 
	?><script>
		ga('send', 'pageview');
	</script><?php
}


#################################
##### HEADER INLINE SCRIPTS #####
#################################

add_action('wp_head','sttv_ga',99);
function sttv_ga() { 

	#################################
	##### GOOGLE ANALYTICS INIT #####
	#################################

?><script>
	window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
	ga('create', '<?php echo GA_PROPERTY_ID; ?>', 'auto');
	ga('require', 'ec')
</script>
<script async src='https://www.google-analytics.com/analytics.js'></script><?php

##########################
##### FACEBOOK PIXEL #####
##########################

?><script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '131594624139844');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=131594624139844&ev=PageView&noscript=1"
/></noscript>
<!-- Global site tag (gtag.js) - Google AdWords: 881289703 -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-881289703"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-881289703');
</script>
<?php if (get_page_template_slug() == 'signup.php') : ?>
<!-- Event snippet for Purchase Best ACT Prep Course Ever conversion page
In your html page, add the snippet and call gtag_report_conversion when someone clicks on the chosen link or button. -->
<script>
function gtag_report_conversion(url) {
  var callback = function () {
    if (typeof(url) != 'undefined') {
      window.location = url;
    }
  };
  gtag('event', 'conversion', {
      'send_to': 'AW-881289703/p1h6CIzTjnYQ59OdpAM',
      'transaction_id': '',
      'event_callback': callback
  });
  return false;
}
</script>
<?php endif; }