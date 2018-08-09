<?php
	global $post;
	$slug = $post->post_name;
	add_filter( 'body_class',function($classes) use ($slug) {
		return ['stBody','stPage-'.$slug];
	});
?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<?php wp_head(); ?>
    <?php if (is_singular('post')) : ?>
		<script type="text/javascript" src="//s3.amazonaws.com/downloads.mailchimp.com/js/signup-forms/popup/embed.js" data-dojo-config="usePlainJson: true, isDebug: false"></script>
        <script type="text/javascript">setTimeout(function() {require(["mojo/signup-forms/Loader"], function(L) { L.start({"baseUrl":"mc.us7.list-manage.com","uuid":"9c9636012d1e5c14f043acabf","lid":"df497b5cbd"}) })},10000);</script>
    <?php endif; ?>
    	<script>
			var recap = {
				field : $('#sttv_recap').closest('form'),
				siteKey : '<?php echo RECAPTCHA_SITEKEY; ?>',
				sttvRecap : null,
				response : function(response){
					if (typeof response === 'undefined') {
						$('button[type=submit]',this.field).prop('disabled',true)
					} else {
						$('button[type=submit]',this.field).prop('disabled',false)
					}
				},
				onload : function() {
					if ($('#sttv_recap').length){
						recap.sttvRecap = grecaptcha.render('sttv_recap', {
							'sitekey' : recap.siteKey,
							'callback' : recap.response,
							'expired-callback' : recap.response
						})
					}
				}
			}
			var recapOnload = function() {
				return recap.onload()
			}
		</script>
    	<script src="https://www.google.com/recaptcha/api.js?onload=recapOnload&render=explicit" async defer></script>
</head>
<body <?php body_class(); ?>>
<?php do_action('sttv_alert_banner'); ?>