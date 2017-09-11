<?php 

/* Template Name: Success - Signup */

?>
<?php

	$user_id = get_current_user_id();
	$stripe_meta = get_user_meta($user_id,'stripe_meta',true);
	$_legacy_cusID = get_user_meta($user_id,'stripe_cus_ID',true);
	
	$cu = 'cus_'.$_GET['conf'];
	$tk = hexdec($_GET['token']);
	$customerID = $stripe_meta['cus_ID'] ?: $_legacy_cusID;
		
	if( current_user_can('manage_options') || ((isset($_GET['conf']) && $cu == $customerID) && (isset($_GET['token']) && $tk == $stripe_meta['sub_expires']) ) ) :
	
	

?>
<?php get_header(); ?>

<?php get_template_part('templates/title'); ?>

<div id="content" class="row">
    <div id="content-wrapper-signup" class="col s12">
        <?php the_content(); ?>
    </div>
    <div id="order-success-content">
    <?php print_r($stripe_meta); print_r(get_user_meta($user_id,'stripe_cus_ID',true)); ?>
    <?php echo $stripe_meta['totals_table']; ?>
    </div>
</div>

<?php get_footer(); ?>

<?php 

	else: //throw 404 for non-admins accessing 'success' endpoint without both queries properly set
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		get_template_part( 404 ); 
		exit();
	endif;