<?php
namespace STTV;

if ( ! defined( 'ABSPATH' ) ) exit;

class Order {

    public static function create( $obj = null ) {

		if ( is_null( $obj ) ) {
            return [
                'error' => true,
                'code' => 'null_body',
                'errMsg' => 'The request body cannot be null'
            ];
		}

		/**
		 * Create new subscription (Stripe)
		 * IF payments fails, delete customer and return to form
		 * (RETURN) sub_ID, cus_ID, inv_ID
		**/
		
		try {

			// init customer object
			$customer = $sub = $inv = NULL;
			$customerID = (is_user_logged_in()) ? get_user_meta(get_current_user_id(),'stripe_cus_ID',true) : false;

			//return $customerID;

			/**
			 * Create new customer (Stripe)
			 * IF user doesn't have stripe_cus_ID, they do not exist in Stripe; create the user.
			 * ELSE update user info and payment on Stripe
			**/
			if ( !$customerID ) {
				$customer = \Stripe\Customer::create([
				  "description" => $obj['firstname'].' '.$obj['lastname'],
				  "source" => $obj['token']['id'],
				  "email" => $obj['email'],
				  "shipping" => [
					"name" => "shipping",
					"address" => [
						"line1" => $obj['shipping_address1'],
						"line2" => $obj['shipping_address2'],
						"city" => $obj['shipping_city'],
						"state" => $obj['shipping_state'],
						"postal_code" => $obj['shipping_pcode'],
						"country" => $obj['shipping_country']
					],
					"phone" => $obj['phone']
				  ]
				]);

				$customerID = $customer->id;
			} else {
				$customer = \Stripe\Customer::retrieve($customerID);
				$customer->description = $obj['firstname'].' '.$obj['lastname'];
				$customer->shipping = [
					"name" => "shipping",
					"address" => [
						"line1" => $obj['shipping_address1'],
						"line2" => $obj['shipping_address2'],
						"city" => $obj['shipping_city'],
						"state" => $obj['shipping_state'],
						"postal_code" => $obj['shipping_pcode'],
						"country" => $obj['shipping_country']
					],
					"phone" => $obj['phone']
				];
				$customer->source = $obj['token']['id'];
				$customer->save();
			}

			if ( $obj['coupon'] ) {
				$customer->coupon = $obj['coupon'];
			}

			$stripe_cus_ID = update_user_meta(get_current_user_id(),'stripe_cus_ID',$customerID);

			// Process cart items
			$items = [];
			foreach ( $obj['cart']['items'] as $id => $item ) {
				switch ($item['type']) {
					case 'subscription':
						$items[$item['type']][] = [
							'plan' => $item['id']
						];
						break;
					case 'product':
						break;
				}
			}
			
			/**
			 * Create invoice items for the shipping charge and book tax
			 *
			**/
			if ( isset($obj['shipping_options']) && $obj['shipping_options'] ) {
				\Stripe\InvoiceItem::create([
					"customer" => $customerID,
					"amount" => 705,
					"currency" => "usd",
					"description" => "Priority Shipping",
					"discountable" => false
				]);
			}
			
			if ( $obj['taxrate'] ) {
				\Stripe\InvoiceItem::create([
					"customer" => $customerID,
					"amount" => round(2500*($obj['taxrate']/100)),
					"currency" => "usd",
					"description" => "Sales tax",
					"discountable" => false
				]);
			}

			$sub = \Stripe\Subscription::create([
				"customer" => $customerID,
				"items" => $items['subscription']
			]);

			$sub->cancel([ 'at_period_end' => true ]);

			/**
			 * Fetch the invoice just created
			**/
			$inv = \Stripe\Invoice::all([
					"limit" => 1,
					"subscription" => $sub->id
			]);
			$inv_id = $inv->data[0]->id;

		} catch (\Stripe\Error\Base $e) {

			if ( !is_null($customer) ) {
				$customer->delete();
			}

			return [ 'error' => $e->getJsonBody()['error'] ];

		} catch (Exception $e) {
			// Something else happened, completely unrelated to Stripe
			$customer->delete();

			self::notify([
				'subject' => 'Generic Error',
				'message' => json_encode($e)
			]);

			return [ 'error' => [
				'error' => 'generic',
				'message' => 'We apologize, something went wrong on our end. You have not been charged. Please try again later.',
				'object' => $e
			] ];
		}
		// END TRY/CATCH

		/**
		 * Add a new user (or update current user), give them access to the appropriate content
		 * after all the Stripe stuff has been created and there are no errors
		**/
		$userdata = [
			'ID' => ( get_current_user_id() ) ?: '',
			'user_login' => $obj['email'],
			'user_pass' => $obj['password'],
			'user_email' => $obj['email'],
			'first_name' => $obj['firstname'],
			'last_name' => $obj['lastname'],
			'display_name' => $obj['firstname'].' '.$obj['lastname'],
			'show_admin_bar_front' => 'false',
			'role' => $sub->plan->metadata->role
		];

		$user_id = wp_insert_user($userdata);

		if ( is_wp_error( $user_id ) ) {
			return [ 'error' => $user_id ];
		}
		$userroleadd = (new \WP_User($user_id))->add_role('test_4_patch');

		$customer->metadata = [
			'wp_id' => $user_id
		];
		$customer->save();

		wp_signon( [
			'user_login'    => $obj['email'],
			'user_password' => $obj['password'],
			'remember'      => true
		], is_ssl() );

		update_user_meta( $user_id, 'invoice_prefix', $customer->invoice_prefix );
		update_user_meta( $user_id, 'stripe_meta', [
			'cus_ID'				=> $customerID,
			'cus_prefix'			=> $customer->invoice_prefix,
			'cus_created'			=> $customer->created,
			'cus_default_source'	=> $customer->default_source,
			'pmt_token'				=> $obj['token']['id'],
			'subscriptions'			=> [
				[
					'plan_ID'		=> $sub->items->data[0]->plan->id,
					'sub_ID'		=> $sub->id,
					'sub_expires'	=> $sub->current_period_end,
					'inv_ID'		=> $inv_id
				]
			]
		] );
		update_user_meta( $user_id, 'show_admin_bar_front', 'false' );
		update_user_meta( $user_id, 'show_admin_bar_admin', 'false' );

		/* add_action( 'shutdown', function() use ($user_id) {
			wp_mail('dave@supertutortv.com', 'Shutdown test', get_userdata($user_id));
		}); */

		return [ 'subData' => $sub, 'invoice' => $inv, 'redirect' => site_url('/my-account') ];

	} // end create() method

	public static function notify( $params = [] ) {
		return wp_mail( get_bloginfo('admin_email'), $params['subject'], $params['message'],
			[
				'From: STTV Web Notifications <info@supertutortv.com>'
			]
		);
	}
}