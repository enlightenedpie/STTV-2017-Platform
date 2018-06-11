/*
 *
 * MAIN SITE OBJECT
 *
 */
var _st = {
	analytics : function( obj ) {
		if ( typeof obj === 'undefined' ) {
			return false
		}

		var page = obj.page || false,
			pageview = obj.pageview || false,
			event = obj.event || false,
			action = obj.action || obj.data
			data = obj.data || false

		if (typeof action === 'string' && typeof data === 'object') {
			//console.log( "ga( "+obj.type+", "+action+", "+data+" )" )
			ga( obj.type, action, data )
		} else if (typeof obj.type !== 'undefined') {
			//console.log( "ga( "+obj.type+", "+action+" )" )
			ga( obj.type, action )
		}

		if ( event ) {
			//console.log( "ga( 'send', 'event', "+event+" )" )
			ga( 'send', 'event', event.name )
		}

		return (pageview) ? (page ? ga( 'send', 'pageview', page ) : ga( 'send', 'pageview' ) ) : pageview
	},
	parseParams : function(str,regex) {
		return (str || document.location.search).replace(/(^\?)/,'').replace(regex,'').split("&").map(function(n){return n = n.split("="),this[n[0]] = n[1],this}.bind({}))[0];
	},
	request : function(obj) {
		var ajaxp = {
			url: obj.route || '',
			method: obj.method || 'GET',
			headers: obj.headers || {},
			processData : false,
			dataType : obj.dataType || 'json',
			success: function(data){
				typeof obj.success !== 'undefined' && obj.success(data);
			},
			error: function(x,s,r){
				typeof obj.error !== 'undefined' && obj.error([x,s,r]);
			}
		}
		if (ajaxp.method !== 'GET') {
			ajaxp['data'] = JSON.stringify(obj.cdata || {})
		}
		if (typeof obj.accepts !== 'undefined'){
			ajaxp['accepts'] = obj.accepts
		}
		$.ajax(ajaxp)
	},
	menu : function(cb) {
		$('body').toggleClass('nav-sidebar-open')
		typeof cb === 'function' && cb();
	},
	closer : function(cb) {
		jQuery('body').removeClass('nav-sidebar-open modal-open');
		typeof cb === 'function' && cb();
	},
	form : {
		valid : false,
		disableForm : function(c) {
			$('.signup-submit',c).prop('disabled',!this.valid)
		},
		validate : function(con) {
			var inputs = $('input,select',con)
			inputs.each(function(k,v){
				if ( $(this).is(':required') && ( ( $(this).val() && !$(this).hasClass('invalid') ) || $(this).hasClass('valid') ) ) {
					_st.form.valid = true
				} else {
					_st.form.valid = false
					_st.form.disableForm(con)
					return false
				}
			})
			this.disableForm(con)
		},
	},
	heartBeat : function() {
		_st.request({
			route : stajax.rootURL+"/ping.php",
			success : function(d){
				try {
					if (!d) {
						throw new Exception('Invalid response from _st.heartBeat.');
					} else {
						do {
							$(document).dequeue('heartbeat')
						} while ($(document).queue('heartbeat').length)
					}
				} catch (e) {
					console.log(e);
				}
			},
			error : function(x,s,r){
				Materialize.toast('Offline', 6000);
				console.log(x,s,r);
			}
		});
	},
	login : function(el) {
		_st.request({
			route : stajax.rest.url+'/auth',
			headers : {
				//'X-WP-Nonce' : stajax.rest.nonce,
			},
			success : function(d) {
				el.append(d)
				_st.modal.loader()
			},
			error : function(x) {
				console.log(x)
			}
		})
	},
	modal : (function() {
		$('.loading-spinner').each(function(i){
			$(this).attr('src',stajax.contentURL+'/i/sttv-spinner.gif')
		})
		return {
			action : '',
			element : $('#sttvmodal'),
			inner : $('.sttvmodal_inner'),
			init : function( act ){
				if (typeof act === 'undefined'){
					return false
				}
				if (this.action === act) {
					return this.toggle()
				}

				var cb;
				if (act !== 'close') {
					this.action = act
					_st.modal.loader(function() {
						_st.modal.inner.empty()
					})
				}

				switch (act) {
					case 'close':
						break
					case 'login':
						cb = function(el) {
							_st.login(el)
						}
						break
					case 'account':
						cb = function(el) {

						}
						break
					case 'mu-checkout':
						cb = function(el) {
							_st.mu.submit(el,'#mu_form_wrapper')
						}
						break
					case 'mu-signup':
						cb = function(el) {
							_st.mu.register(el,'#mu_form_wrapper')
						}
						break
					case 'sttv-cart':
					case 'checkout':
						cb = function(el) {
							_st.cart.submit(true,el)
						}
						break
				}
				this.toggle(cb)
			},
			toggle : function(cb) {
				$('body').toggleClass('modal-open')
				typeof cb === 'function' && cb(_st.modal.inner)
			},
			loader : function(cb) {
				_st.modal.element.toggleClass('loader-active')
				typeof cb === 'function' && cb(_st.modal.inner)
			}
		}
	})(),
	cart : (function(){
		if ( stajax.type === 'courses' ) {
			return false
		}
		var cartObj = JSON.parse(localStorage.getItem('_stcart_'))
		var initDate = Date.now()
		if ( cartObj === null || (cartObj.ID / 1000 | 0) + (86400) < initDate / 1000 | 0 ) {
			cartObj = {
				ID : initDate,
				signature : btoa(navigator.userAgent+'|'+navigator.platform+'|'+navigator.product).replace(/=/g,''),
				items : {}
			}
		}

		for ( var key in cartObj.items ) {
			var obj = cartObj.items[key]
			if ( obj.type === 'multi-user' ) {
				delete cartObj.items[key]
			}
		}

		var currentCount = Object.keys(cartObj.items).length

		var fabWrap = $('<div/>',{id:'cart-FAB'}),
			fab = $('<a/>',{"class":'cart-fab btn-floating btn-large z-depth-5'}),
			fabCon = $('<i/>',{"class":'material-icons',text:'shopping_cart'}),
			fabAlert = $('<div/>',{"class":'cart-alert circle z-depth-2'})

		$('body').addClass('sttv-jscart')

		fabWrap.append(
			fabAlert.text(currentCount)
		).append(
			fab.append(fabCon)
		).appendTo(document.body)

		if ( currentCount > 0 ) {
			fabAlert.addClass('show').siblings('.cart-fab').addClass('pulse')
		}

		localStorage.setItem('_stcart_',JSON.stringify(cartObj))

		return {
			cartObj : cartObj,
			changed : [],
			add : function add(item,skipUpdate) {
				skipUpdate = skipUpdate || false
				if ( typeof item !== 'object' ) {
					throw 'Item must be an object'
				}
				var cart = this.cartObj.items,
					msg = ''

				if ( typeof cart[item.id] === 'undefined' ) {
					cart[item.id] = item
					msg = 'Item added'
				} else {
					if ( item.type !== 'subscription') {
						cart[item.id].qty += item.qty
						msg = 'Quantity updated'
					}
				}

				this.changed.push(item.id)
				this.save(skipUpdate)

				_st.analytics({
					type : 'ec:addProduct',
					data : {
						'id' : item.id,
						'name' : item.name,
						'brand' : 'SupertutorTV',
						'category' : item.type,
						'quantity' : item.qty,
						'price' : (item.price/100).toFixed(2)
					}
				})
				_st.analytics({
					type : 'ec:setAction',
					action : 'add'
				})
				return msg
			},
			remove : function(item,skipUpdate) {
				skipUpdate = skipUpdate || false
				if (typeof item !== 'string' ){
					return false
				}
				delete this.cartObj.items[item]
				return this.save(skipUpdate)
			},
			empty : function(cb) {
				this.cartObj.items = {}
				this.save()
				return typeof cb === 'function' && cb(this)
			},
			unset : function(cb) {
				localStorage.removeItem('_stcart_')
				return typeof cb === 'function' && cb(this)
			},
			save : function(skip) {
				localStorage.setItem('_stcart_',JSON.stringify(this.cartObj))
				return !skip && this.notifications.update()
			},
			get : function() {
				return this.cartObj.items
			},
			notifications : {
				count : currentCount,
				element : fabAlert,
				update : function() {
					this.count = Object.keys(_st.cart.cartObj.items).length
					if ( this.count <= 0 ) {
						$('.cart-alert').removeClass('show').siblings('.cart-fab').removeClass('pulse')
					} else {
						$('.cart-alert').addClass('show').siblings('.cart-fab').addClass('pulse')
					}
					$('.cart-alert').text(this.count)
					return this.count
				}
			},
			submit : function(init,el) {
				var data = {
					init : init || false,
					cart : this.get()
				}

				_st.analytics({
					type : 'ec:setAction',
					action : 'click',
					pageview : true
				})

				_st.request({
					route : stajax.rest.url+'/checkout',
					method : 'POST',
					cdata : data,
					headers : {
						//'X-WP-Nonce' : stajax.rest.nonce,
					},
					success : function(d) {
						_st.checkout = 'subscription'
						el.append(d.html)
						_st.modal.loader()

						for (var itemID in data.cart) {
							var item = data.cart[itemID]
							_st.analytics({
								type : 'ec:addProduct',
								data : {
									'id' : item.id,
									'name' : item.name,
									'brand' : 'SupertutorTV',
									'category' : item.type,
									'quantity' : item.qty,
									'price' : (item.price/100).toFixed(2)
								}
							})
						}
						_st.analytics({
							type : 'ec:setAction',
							action : 'checkout',
							data : {
								'step' : 1
							},
							pageview : true,
							page : '/checkout'
						})
					},
					error : function(x) {
						console.log(x)
						var d = x[0].responseJSON

						//$('.message',el).text(d.message)
						_st.modal.toggle(function() {
							_st.modal.loader()
						})
					}
				})
			}
		}
	})(),
	checkout : '',
	mu : {
		submit : function(el,con) {
			var data = {
				mukey : $('input[name=mukey]',con).val(),
				email : $('input[name=email]',con).val(),
				license : {
					id : $('select[name=sttv_course_id]',con).val(),
					title : $('select[name=sttv_course_id] option:selected',con).text(),
					qty : $('select[name=qty]',con).val()
				}
			},
			type = 'multi-user'

			_st.request({
				route : stajax.rest.url+'/multi-user',
				method : 'POST',
				cdata : data,
				headers : {
					'X-WP-Nonce' : stajax.rest.nonce,
				},
				success : function(d) {
					_st.checkout = type
					_st.cart.empty(function(t) {
						t.add(d.data,true)
					})
					el.append(d.html)
					_st.modal.loader()
					console.log(d)
				},
				error : function(x) {
					var d = x[0].responseJSON

					$('.message',con).text(d.message)
					_st.modal.toggle(function() {
						_st.modal.loader()
					})
					console.log(d)
				}
			})
		},
		register : function(el,con) {

			var data = {
				muid : $('input[name=mukey]',con).val(),
				email : $('input[name=sttv_email]',con).val(),
				password : $('input[name=sttv_password]',con).val(),
				firstName : $('input[name=sttv_firstname]',con).val(),
				lastName : $('input[name=sttv_lastname]',con).val()
			}

			_st.request({
				route : stajax.rest.url+'/checkout',
				method : 'POST',
				cdata : data,
				headers : {
					'X-WP-Nonce' : stajax.rest.nonce,
				},
				success : function(d) {
					el.append(d.html)
					_st.modal.loader()
					setTimeout(function(){
						window.location.href = d.data.redirect
					},2000)
					console.log(d)
				},
				error : function(x) {
					console.log(x)
					var d = x[0].responseJSON

					$('.message',con).text(d.message)
					_st.modal.toggle(function() {
						_st.modal.loader()
					})
				}
			})
		}
	},
	scroll : function(a) {
		$('html, body').stop().animate({
			scrollTop: $(a).offset().top-100
		},1250,"swing")
	},
	fn : function() {}
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
