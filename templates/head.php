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
		
			var siteKey = '6LdjuA0UAAAAAMBQ0XAQoewK6248ezq5FZVm4T86';
			var loginRecap, contactRecap;
			
			var recapOnload = function() {
				<?php if (is_page('contact')) : ?>
					contactRecap = grecaptcha.render('contact_recap', {
						'sitekey' : '<?php echo RECAPTCHA_SITEKEY; ?>'
					});
				<?php endif; if (is_page('subscribe')) : ?>
					grecaptcha.render('subscribe_form', {
						'sitekey' : '<?php echo RECAPTCHA_SITEKEY; ?>'
					});
				<?php endif; ?>
			};
		
		</script>
    	<script src="https://www.google.com/recaptcha/api.js?onload=recapOnload&render=explicit" async defer></script>
</head>

<body <?php body_class(); ?>>
<?php do_action('sttv_after_body_tag'); ?>
<?php do_action('sttv_sale_banner'); ?>
	<div id="main-wrapper">
    	<div id="navcloser" class="close"></div>