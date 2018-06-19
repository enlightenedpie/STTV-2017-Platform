import {analytics} from './object/analytics.js'
import {request} from './object/request.js'
import {form} from './object/form.js'
import {modal} from './object/modal.js'
import {cart} from './object/cart.js'
import {mu} from './object/mu.js'
import {parseParams, menu, closer, login, scroll} from './object/functions.js'

/*
 *
 * MAIN SITE OBJECT
 *
 */
var _st = {
	analytics : analytics,
	parseParams : parseParams,
	request : request,
	menu : menu,
	closer : closer,
	form : form,
	heartBeat : heartBeat,
	login : login,
	modal : modal,
	cart : cart,
	mu : mu,
	scroll : scroll
};


/*
 *
 * EVENT HANDLERS
 *
 */
( function ( $ ) { //begin wrapper
	"use strict";

// Opener functions
$('input, select','#mu_form_wrapper').on('change',function(e){
	_st.form.validate('#mu_form_wrapper')
	_st.modal.action = ''
})

// modal handler
var selectors = '.slide-bar, .modal-toggle, .mu-signup, .read-more, .mu-submitter, .cart-fab, .payment-launcher'
$(document).on('click',selectors,function(e) {
	e.preventDefault();
	var t = $(this),
		c = t.attr('class').split(/\s+/),
		tda = t.attr('data-action')

	var f = {
		'mu-signup' : function() {
			_st.modal.init('mu-signup')
		},
		'payment-launcher' : function() {
			modalInit({'id':'10789','name':'The Best ACT Prep Course Ever','price':24900,'taxable':true,'taxableAmt':2500,'qty':1,'type':'subscription'})
			_st.modal.init( 'checkout' );
		},
		'modal-toggle' : function() {
			if ( 'account' == tda ) { // remove this for 2.0
				window.location.href = t.attr('href')
			} else {
				_st.modal.init( tda );
			}
		},
		'slide-bar' : function() {
			_st.menu()
		},
		'read-more' : function() {
			t.parent().css({'display':'none'});
			$('#content-wrapper').css({'max-height':'none'});
		},
		'mu-submitter' : function() {
			_st.modal.init('mu-checkout')
		},
		'cart-fab' : function() {
			_st.modal.init('sttv-cart')
		}
	}

	c.some(function(v){typeof f[v] !== 'undefined' && f[v]()});
});

function modalInit(){
 	var modal = "<section id='checkout-wrapper' class='row'> \
	 <div id='checkout-info' class='align-middle' style='display:block;'> \
				 <div id='wrapper_line-item' class='col s12'> \
						 <div id='customer_info' class='row'> \
								 <div id='account_info' class='col s12'> \
										 <div class='row'> \
												 <h4>Your Information</h4> \
												 <div class='input-field left-col col s6'> \
														 <input id='sttv_firstname' type='text' class='validate' name='sttv_firstname' value='' required/> \
														 <label for='sttv_firstname'>First Name</label> \
												 </div> \
												 <div class='input-field left-col col s6'> \
														 <input id='sttv_lastname' type='text' class='validate' name='sttv_lastname' value='' required/> \
														 <label for='sttv_lastname'>Last Name</label> \
												 </div> \
												 <div class='input-field col s12'> \
														 <input id='sttv_email' class='validate' name='sttv_email' type='email' value='' required/> \
														 <label for='sttv_email'>Email Address</label> \
												 </div> \
												 <div class='input-field col s12'> \
														 <input id='sttv_password' name='sttv_password' type='password' value='' required/> \
														 <label for='sttv_password'>Choose Password</label> \
												 </div> \
												 <div class='input-field col s12'> \
														 <input id='sttv_phone' class='validate' name='sttv_phone' type='tel' value='' required/> \
														 <label data-error='Invalid phone number' for='sttv_phone'>Phone Number</label> \
												 </div> \
										 </div> \
								 </div> \
							 </div> \
							 <input type='button' class='next_button' onClick=changePanel('checkout-info','checkout-billing') value='next' style='float: right;'/> \
						 </div> \
					 </div> \
			 <div id='checkout-billing' class='align-middle' style='display:none;'> \
						 <div id='wrapper_line-item' class='col s12'> \
							 <div id='billing_info' class='col s12'> \
									 <div id='billing_fields' class='row'> \
											 <h4>Billing Address</h4> \
											 <div class='input-field col s12'> \
													 <input id='sttv_billing_address1' name='sttv_billing_address1' type='text' class='validate' required/> \
													 <label class='active' for='sttv_billing_address1' data-error='Invalid format' >Address Line 1</label> \
											 </div> \
											 <div class='input-field col s12'> \
													 <input id='sttv_billing_address2' name='sttv_billing_address2' type='text' /> \
													 <label class='active' for='sttv_billing_address2'>Address Line 2</label> \
											 </div> \
											 <div class='input-field col s12'> \
													 <input id='sttv_billing_city' name='sttv_billing_city' class='validate' type='text' required/> \
													 <label class='active' for='sttv_billing_city'>City</label> \
											 </div> \
											 <div class='input-field left-col col s6'> \
													 <input id='sttv_billing_state' name='sttv_billing_state' class='validate' type='text' required/> \
													 <label class='active' for='sttv_billing_state'>State</label> \
											 </div> \
											 <div class='input-field col s6'> \
													 <input id='sttv_billing_pcode' name='sttv_billing_pcode' class='validate' type='tel' required/> \
													 <label class='active' for='sttv_billing_pcode'>Postal Code</label> \
											 </div> \
											 <div class='input-field col s12'> \
													 <select class='country-dd' name='sttv_billing_country' required> \
															 <option value disabled selected>Country...</option> \
													 </select> \
											 </div> \
											 <div class='input-field col s12'> \
													 <input class='filled-in' type='checkbox' id='same_as_billing'/><label for='same_as_billing'>Use same shipping address</label> \
											 </div> \
									 </div> \
									 <input type='button' class='next_button' onClick=changePanel(\'checkout-billing\',\'checkout-info\') value='prev'/> \
									 <input type='button' class='next_button' onClick=changePanel(\'checkout-billing\',\'checkout-shipping\') value='next' style='float: right;'/> \
							 </div> \
						 </div> \
				 </div> \
		 <div id='checkout-shipping' class='align-middle' style='display:none;'> \
				 <div id='shipping_info' class='col s12'> \
						 <div id='shipping_fields' class='row'> \
								 <h4>Shipping Address</h4> \
								 <div class='input-field col s12'> \
										 <input id='sttv_shipping_address1' name='sttv_shipping_address1' type='text' class='validate' required/> \
										 <label for='sttv_shipping_address1' data-error='Invalid format' >Address Line 1</label> \
								 </div> \
								 <div class='input-field col s12'> \
										 <input id='sttv_shipping_address2' name='sttv_shipping_address2' type='text' /> \
										 <label for='sttv_shipping_address2'>Address Line 2</label> \
								 </div> \
								 <div class='input-field col s12'> \
										 <input id='sttv_shipping_city' class='validate' name='sttv_shipping_city' type='text' required/> \
										 <label for='sttv_shipping_city'>City</label> \
								 </div> \
								 <div class='input-field left-col col s6'> \
										 <input id='sttv_shipping_state' class='validate' name='sttv_shipping_state' type='text' required/> \
										 <label for='sttv_shipping_state'>State</label> \
								 </div> \
								 <div class='input-field col s6'> \
										 <input id='sttv_shipping_pcode' class='validate' name='sttv_shipping_pcode' type='tel' required/> \
										 <label for='sttv_shipping_pcode'>Postal Code</label> \
								 </div> \
								 <div class='input-field col s12'> \
										 <select class='country-dd validate' name='sttv_shipping_country' required> \
												 <option value disabled selected>Country...</option> \
										 </select> \
								 </div> \
						 </div> \
						 <input type='button' class='next_button' onClick=changePanel(\'checkout-shipping\',\'checkout-billing\') value='prev'/> \
						 <input type='button' class='next_button' onClick=changePanel(\'checkout-shipping\',\'checkout-order\') value='next' style='float: right;'/> \
				 </div> \
		 </div> \
		 <div id='checkout-order' class='align-middle' style='display:none;'> \
				 <div class='row'> \
						 <h4>Your Order</h4> \
						 <div class='col s12' style='margin-top:10px'> \
								 <div class='row'> \
										 <div id='shipping_options' class='col s12' style='margin-top:1%'> \
												 <div class='shp-msg'> \
													 <span>Shipping is for U.S. orders only.</span> \
													 <div class='input-field col s12' style='margin-top:0px'> \
															 <p> \
																	 <input id='freeship' name='shipping_options' type='radio' value='0' checked /> \
																	 <label for='freeship'>Free Shipping (1-3 weeks)</label> \
															 </p> \
															 <p> \
																	 <input id='priship' name='shipping_options' type='radio' value='1285' /> \
																	 <label for='priship'>Priority Shipping (3-4 days)</label> \
															 </p> \
													 </div> \
												 </div> \
										 </div> \
								 </div> \
						 </div> \
						 <div id='cart-column' class='col s12'> \
								 <div class='row headings-row'> \
										 <div class='col s2'>Qty</div> \
										 <div class='col s8'>Item</div> \
										 <div class='col s2 right-align'>Price</div> \
								 </div> \
								 <div class='items-row'></div> \
								 <div class='row totals-row'> \
										 <div class='col s8'> \
												 <div class='input-field coupon col s12'> \
														 <input id='sttv_coupon' name='sttv_coupon' type='text'/> \
														 <label for='sttv_coupon'>Coupon Code</label> \
												 </div> \
										 </div> \
										 <div id='total' class='col s4 right-align'><span id='ttltxt'>Total: $<span>0</span></span></div> \
								 </div> \
						 </div> \
				 </div> \
				 <div class='card-content row'> \
						 <h4>Payment</h4> \
						 <div class='input-field col s12'> \
								 <input name='sttv_cardname' type='text' required/> \
								 <label class='active' for='sttv_cardname'>Name On Card</label> \
						 </div> \
						 <div class='input-field col s12'> \
								 <div id='sttv_card_element'></div> \
						 </div> \
						 <div class='col s12'> \
								 <div class='input-field col s12' style='margin-top:0px'> \
										 <input class='filled-in' type='checkbox' name='sttv_mailinglist' id='sttv_mailinglist' checked/><label for='sttv_mailinglist'>Sign me up for promos, coupons, and giveaways from SupertutorTV</label><br/> \
								 </div> \
								 <div class='tandc col s12'> \
										 <span>By submitting this payment, you agree to SupertutorTV's <a class='azure' href='' target='blank'>Terms and Conditions</a>.</span> \
								 </div> \
						 </div> \
						 <div class='col s12'> \
								 <button type='submit' class='signup-submit button-wide z-depth-1 waves-effect waves-light' disabled><span>Place Order</span></button> \
						 </div> \
						 <div class='col s12'> \
								 <p class='error token'>&nbsp;</p> \
						 </div> \
						 <input type='button' class='next_button' onClick=changePanel(\'checkout-order\',\'checkout-shipping\') value='prev'/> \
				 </div> \
		 </div> \
 </section> \
 <script type='text/javascript' src='/wp-content/themes/sttv_2017/s/checkout.js' id='sttv-checkout-js'></script>"
 $('.sttvmodal_inner').append(modal)
}

// scroller
$(document).on('click','.st-scroll',function(e) {
	e.preventDefault()
	_st.scroll(e.target.getAttribute('href'))
})

var thenav = $('body.nav-sidebar-open #main-nav');
thenav.on('click touchstart',function(e) {
	if (e.offsetX > thenav.offsetWidth) {
		alert('Clicked!');
		e.preventDefault();
		_st.closer();
	}
});

$('li.menu-item-has-children>a').click(function(e) {
	e.preventDefault();
	$(this).siblings('ul.sub-menu').toggleClass('active').promise().done(function(){
		$('ul.sub-menu').not(this).removeClass('active');
	});
});

$(document).on('submit','form#sttv_login_form',function(e) {
	e.preventDefault();
	if (0 === $('#sttv_user').val().length){
		$('.message').html('Username is required')
		return;
	}

	var loader = '<img src="'+stajax.contentURL+'/i/sttv-spinner.gif" alt="Loading..." />',
		ld = $('.loading_overlay');
	ld.append(loader).promise().done(function(){
		$(this).fadeIn(250);
	});

	_st.request({
		route : stajax.rest.url+'/auth?action=login',
		method : 'POST',
		headers : {
			'X-WP-Nonce' : stajax.rest.nonce,
			'X-STTV-Auth' : btoa(this.sttv_user.value+':'+this.sttv_pass.value)
		},
		success : function(data) {
			if ( data.code == 'login_success' ) {
				ld.empty().html('<p class="sblock"><strong><i class="material-icons">done</i></strong></p>').fadeIn(250)
				$('.sblock').hide().fadeIn(250)
				setTimeout(function(){
					window.location.href = data.redirect
				},250);
			}
		},
		error : function(x) {
			var data = x[0].responseJSON,
				msg = ( typeof data.errors.too_many_retries !== 'undefined') ? data.errors.too_many_retries[0] : data.message;

			$('.message').html(msg)
			ld.fadeOut(250)
			console.log(data)
		}
	})
});

$('form#sttv_contact').on('submit',function(e) {
	e.preventDefault();
	var loading = $('.loading_overlay',$(this).parent()).html('<img src="'+stajax.contentURL+'/i/sttv-spinner.gif" alt="Loading..." />')

	loading.fadeIn(250)

	_st.request({
		route : stajax.rest.url+'/contact',
		method : 'POST',
		headers : {
			'X-WP-Nonce' : stajax.rest.nonce
		},
		cdata : {
			g_recaptcha_response : grecaptcha.getResponse(),
			sttv_contact_name: this.sttv_contact_name.value,
			sttv_contact_email: this.sttv_contact_email.value,
			sttv_contact_subject: this.sttv_contact_subject.value,
			sttv_contact_message: this.sttv_contact_message.value
		},
		success : function(data) {
			console.log(data)
			if ( data.sent ) {
				loading.empty().html('<p class="sblock"><strong><i class="material-icons">done</i></strong></p>').fadeIn(250)
				var s = $('.sblock');
				var p = $('<p/>',{"class":"smessage"});
				p.appendTo(s).append(data.message);
				$('.sblock').hide().fadeIn(250)
			} else {
				$('.message').html(data.message)
				loading.fadeOut(250)
			}
		},
		error : function(x) {
			$('.message').html('Something went wrong. Please refresh the page and try again.')
			loading.fadeOut(250)
			console.log(x)
		}
	})

  });

	$('#subscribe_page_mc').on('submit',function(e){
		e.preventDefault();
		var form = $(this)

		var loading = $('.loading_overlay',$(this).parent()).html('<img src="'+stajax.contentURL+'/i/sttv-spinner.gif" alt="Loading..." />')

		loading.fadeIn(250)

		var fields = {
			fname : $('#sttv_mc_fname',form).val(),
			lname : $('#sttv_mc_lname',form).val(),
			email : $('#sttv_mc_email',form).val(),
			g_recaptcha_response : grecaptcha.getResponse()
		}

		_st.request({
			route : stajax.rest.url+'/subscribe',
			method : 'POST',
			cdata : fields,
			headers : {'X-WP-Nonce' : stajax.rest.nonce},
			success : function(d){
				$('input, button',form).prop('disabled',true)
				grecaptcha.reset()
				loading.empty().html('<p class="sblock"><strong><i class="material-icons">done</i></strong></p>').fadeIn(250)
				var s = $('.sblock');
				var p = $('<p/>',{"class":"smessage"});
				p.appendTo(s).append(d.message);
				$('.sblock').hide().fadeIn(250)
				console.log(d)
			},
			error : function(x){
				$('.message',form).html('Something went wrong. Please refresh the page and try again.')
				loading.fadeOut(250)
				console.log(x)
			}
		})
	});

} ( jQuery ) ); //end wrapper
