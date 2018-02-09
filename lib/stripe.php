<?php
if ( ! defined( 'ABSPATH' ) ) {die;}

add_action('stripepress_events','kill_the_messenger',11,2);
function kill_the_messenger($event,$object) {
	
	require_once('Mandrill/Mandrill.php');
	
	$to = get_option('admin_email');
	$headers = array("Content-Type: text/html; charset=UTF-8","From: SupertutorTV <$to>","bcc: dave@supertutortv.com");
	
	$obj = json_decode($object);
	$cus = \Stripe\Customer::retrieve($obj->data->object->customer); //cus_AXIdPJHOPvtzDr
	
	switch ($event) {
		case 'invoice.payment_succeeded': 
			
			$html = '';
			foreach ($obj->data->object->lines->data as $line) {
				$amt = $line->amount/100;
				$html .= "<tr><td>{$line->description}</td><td>{$amt}</td></tr>";
			}
			
			$cusinfo = "<h4 style='color:#109fda;font-weight:bold'>Customer info:</h4><br/>{$cus->description}<br/>{$cus->email}<br/>Wordpress ID: {$cus->metadata->wp_id}<br/>";
			foreach ($cus->subscriptions->data as $sub) {
				$cusinfo .= "Subscribed to {$sub->plan->name} - expires ".date('F d, Y H:i:s',$sub->current_period_end);
			}
			
			$shipadd = "<h4 style='color:#109fda;font-weight:bold'>Shipping address:</h4><br/>{$cus->shipping->address->line1}<br/>{$cus->shipping->address->line2}<br/>{$cus->shipping->address->city}, {$cus->shipping->address->state} {$cus->shipping->address->postal_code} {$cus->shipping->address->country}";
			
			$billadd = "<h4 style='color:#109fda;font-weight:bold'>Billing address:</h4><br/>";
			foreach ($cus->sources->data as $card) {
				$billadd .= "{$card->address_line1}<br/>{$card->address_line2}<br/>{$card->address_city}, {$card->address_state} {$card->address_zip} {$card->address_country}";
			}
			
			$cus_html = "<div style='display:inline-block;width:100%;'>{$cusinfo}</div><div style='display:inline-block;width:50%;'>{$shipadd}</div><div style='display:inline-block;width:50%;>{$billadd}</div>";
			
			$json =	array(
				'type'=>'messages',
				'call'=>'send-template',
				'template_name'=>'new_order',
				'template_content'=>array(
					array(
						'name'=>'ordertitle',
						'content'=>'Invoice '.$obj->data->object->id.' - <span style="color:red">PAID</span> ('.date('F d, Y H:i:s',$obj->data->object->date).')'
					),
					array(
						'name'=>'ordertable-body',
						'content'=>$html
					),
					array(
						'name'=>'ordertotal',
						'content'=>$obj->data->object->total/100
					),
					array(
						'name'=>'customerinfo',
						'content'=>$cus_html
					)
				),
				'message'=>array(
					'from_email'=>'info@supertutortv.com',
					'from_name'=>'Supertutor Media Inc.',
					'subject' => 'New Order | '.$cus->description.' - '.$cus->email,
					'to'=>array(
						array(
							'type'=>'to',
							'email'=>$to,
							'name'=>'SupertutorTV'
						)
					),
					'headers'=>array(
						'Reply-To'=>$to,
					),
					'metadata'=>array(
						'website'=>'https://supertutortv.com'
					),
					'inline_css'=>true,
					'track_opens'=>true,
					'track_clicks'=>true,
					'bcc_address'=>'dave@supertutortv.com'
				)
			);
			
			try {
				Mandrill::setApiKey(MANDRILL_API_KEY);
				Mandrill::call((array) $json);
			} catch (Exception $e) {
				wp_mail($to,'Mandrill Fail',print_r($e));
			}
			break;
			
		case 'customer.subscription.created':
			
			$cus = \Stripe\Customer::retrieve($obj->data->object->customer);
			$name = explode(' ',$cus->description);
			$json =	array(
				'type'=>'messages',
				'call'=>'send-template',
				'template_name'=>'act_welcome',
				'template_content'=>array(
					array(
						'name'=>'fname',
						'content'=>$name[0]
					),
					array(
						'name'=>'test',
						'content'=>$obj->data->object->plan->metadata->test
					),
					array(
						'name'=>'coursename',
						'content'=>$obj->data->object->plan->name
					)
				),
				'message'=>array(
					'from_email'=>$to,
					'from_name'=>'SupertutorTV',
					'to'=>array(
						array(
							'type'=>'to',
							'email'=>$cus->email,
							'name'=>$cus->description
						)
					),
					'headers'=>array(
						'Reply-To'=>$to,
					),
					'metadata'=>array(
						'website'=>'https://supertutortv.com'
					),
					'inline_css'=>true,
					'track_opens'=>true,
					'track_clicks'=>true,
					'bcc_address'=>'dave@supertutortv.com'
				)
			);
			
			try {
				Mandrill::setApiKey(MANDRILL_API_KEY);
				Mandrill::call((array) $json);
			} catch (Exception $e) {
				wp_mail($to,'Mandrill Fail',print_r($e));
			}
			break;

		case 'customer.subscription.deleted':

			try {

				$user = new WP_User($cus->metadata->wp_id);

				ob_start();
				print_r($user);
				$body = ob_get_clean();
				
				wp_mail($to,'Test!!!',$body,$headers);

				$user->set_role('subscriber');

			} catch (Exception $e) {
				ob_start();
				print_r($e);
				$body = ob_get_clean();
				wp_mail('dave@supertutortv.com','Delete Fail',$body);
			}
			break;
		default:
			break;
	} //end switch block
}

function sp_get_all_subs() {
	try {
		//\Stripe\Stripe::setApiKey(Spress()->secret_key);
		$subs = \Stripe\Subscription::all();
		echo wp_send_json($subs);
	} catch (Exception $e) {
		echo wp_send_json($e);
	}
}

class STTV_Stripe_Emails {
	
	
} // end STTV_Stripe_Emails

//end of line, man.