<?php
if (!is_user_logged_in()) {
	wp_redirect('/');
	die;
}

$user = wp_get_current_user();
$meta = get_user_meta($user->ID,'stripe_meta',true);
$visited = get_user_meta($user->ID,'acct_visited',true) ?: 0;
$welcome = ($visited > 0) ? "Welcome back,":"Welcome,";
$legacy = array('_legacy_value_bundle','_legacy_official_guide','_legacy_red_book');

update_user_meta($user->ID,'acct_visited',$visited++);

if (current_user_can('manage_options')){
	$query = new WP_Query(array(
		'post_type' => 'courses',
		'post_status' => 'publish',
		'posts_per_page' => -1
	));

	echo '<pre>';
	while ($query->have_posts()) {
		$query->the_post();
		echo '<a href="'.get_permalink().'">'.get_the_title().'</a>';
	}
	echo '</pre>';

	wp_reset_query();
	
} elseif (current_user_can('multi-user_master')) {
	$keys = get_user_meta( $user->ID, 'mu_keys' );
	$html = '<div class="row"><h3>Multi-user Keys</h3><div class="col s12">';
	foreach ( $keys as $key ) {
		$html .= "{$key}<br/>";
	}
	$html .= '</div></div>';
	print $html;

} else {
	foreach ($legacy as $l){
		if (!current_user_can($l)){
			continue;
		}
		print '<pre><a href="/courses/the-best-act-prep-course-ever">The Best ACT Prep Course Ever - Click here to access your course</a></pre>';
		return true;
	}
	
?><div class="row" id="my-account_courses">
	<div class="col s12"><h2>My Courses</h2></div>
	<div class="col s12">
		<table class="bordered highlight responsive-table">
			<thead><tr><th>Status</th><th>Course Name</th><th>Expires</th></tr></thead>
			<tbody>
<?php
$invoices = [];
foreach ($meta['subscriptions'] as $sub):
	$invoices[] = $sub['inv_ID'];
	$thepost = get_post($sub['plan_ID']);
	$active = $link = '';
	$expiry = date('F d, Y',$sub['sub_expires']);
		if ($sub['sub_expires']>time()):
			$active = 'Active';
			$link = get_permalink($thepost->ID);
			$color = 'green';
		else:
			$active = 'Expired';
			$link = 'javascript:void(0)';
			$color = 'red';
		endif;
	echo '<tr>';
		echo "<td style='color:{$color}'>{$active}</td>";
		echo "<td><h4 style='text-transform:uppercase'><a href='{$link}'>{$thepost->post_title}</a></h4></td>";
		echo "<td>{$expiry}</td>";
	echo '</tr>';
endforeach;
			?></tbody>
		</table>
	</div>
</div>
<div class="row" id="my-account_invoices">
	<div class="col s12"><h2>My Invoices</h2></div>
	<div class="col s12">
		<table class="bordered highlight responsive-table">
			<thead><tr><th>Status</th><th>Amt</th><th>Name</th><th>Date of Purchase</th></tr></thead>
			<tbody><?php if (!empty($invoices)) {
		foreach ($invoices as $invoice){
			$inv = \Stripe\Invoice::retrieve($invoice);
			$status = ($inv['paid'] && $inv['closed']) ?'Paid':'Processing';
			$c = ($inv['paid'] && $inv['closed']) ?'green':'';
			$amt = $inv['total']/100;
			$ps =  date('F d, Y',$inv['date']);
			$invitems = '';
			print '<tr>';
			print "<td style='color:{$c}'>{$status}</td>";
			print "<td>\${$amt}</td>";
			print "<td><h4 style='text-transform:uppercase'><a href='{$link}'>{$thepost->post_title}</a></h4>";
			$disc = $inv['discount']['coupon']['amount_off']/100;
			print "<div><span>-{$disc}&nbsp;</span><span>coupon: {$item['discount']['coupon']['id']}</span></div>";
				foreach ($inv['lines']['data'] as $item){
					$itmamt = $item['amount']/100;
					print "<div><span>+{$itmamt}&nbsp;</span><span>{$item['description']}</span></div>";
				}
			print "</td>";
			print "<td>{$ps}</td>";
			print '</tr>';
		}
			}
	?></tbody>
		</table>
	</div>
</div>
<div class="row" id="my-account_info">
	<div class="col s12"><h2>My Info</h2></div>
	<div class="col s12"><?php
		$cus = ($meta['cus_ID']) ? \Stripe\Customer::retrieve($meta['cus_ID']):array('description'=>'','email'=>'',);
		//print_r(json_encode($cus));
		print "<div><span>Name: {$cus['description']}</span></div><br/>";
		print "<div><span>Email: {$cus['email']}</span></div><br/>";
		print "<span>Shipping Address:</span>";
		print "<address><p><span>{$cus['shipping']['address']['line1']}</span>&nbsp;<span>{$cus['shipping']['address']['line2']}</span></p>";
		print "<p><span>{$cus['shipping']['address']['city']},&nbsp;</span>&nbsp;<span>{$cus['shipping']['address']['state']}&nbsp;</span><span>{$cus['shipping']['address']['postal_code']}</span></p></address>";
	?></div>
</div>
<?php } ?>
<div id="logout-btn" class="logger-outer" style="width:100%;padding:1em;text-align:center">
	<a style="background-color:#109fda;color:white" href="<?php echo site_url('/logout');?>" class="btn">Logout</a>
</div>
<script>
$('.logger-outer').on('click',function(e){
	e.preventDefault()
	$.post(stajax.rest.url+'/auth?action=logout',function(d){
		window.location.href = d
	});
})
</script>