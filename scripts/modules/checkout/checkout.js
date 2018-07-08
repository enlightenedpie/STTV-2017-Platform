/* Let's define the checkout object with methods and properties */
const Checkout = class {
	constructor(){
		Object.assign(this,{
			valid : false,
			index : 0,
			totals : {
				total : 0,
				tax : 0,
				taxable : 0,
				shipping : 705,
				disc : 0,
				discp : 0,
				msg : '',
				coupon : '',
				trial: 0
			},
			card : false,
			customer : {
	
			},
			html : ['']
		})
	}

	init(cb) {
		var t = this
		_st.request({
			route: '/checkout?pricing=C3500',
			success : (data) => {
			  var p = data.data.pricing
			  t.totals.total += p.price
			  t.totals.taxable += p.taxable_amt
			  t.totals.trial = p.trial_period
			  typeof cb === 'function' && cb(t)
			},
			error : (err) => {
			  console.log(err)
			}
		  })

		this.html.push(this.constructor.pane1())
		this.html.push(this.constructor.pane2())
		this.html.push(this.constructor.pane3())
		this.html.push(this.constructor.pane4())
	}

	static pane1() {
		return `<div id="pane-1" class="st-checkout-pane row">
			<div class="st-checkout-header col s12">
				<h2>Okay, let's get started!</h2>
				<span>You're very close to getting expert tutoring from Brooke Hanson. <strong>NOTE:</strong> Your card will not be charged until your trial period is over, and you're free to cancel at any time. If your course comes with free books, they will not ship until your trial has expired.</span>
			</div>
			<div id="st-checkout-account" class="st-checkout-form col s12 l6 push-l3">
				<div class="input-field col s12 l6 st-input-half-left">
					<input class="browser-default invalid" type="text" name="st-first-name" placeholder="First Name" required />
				</div>
				<div class="input-field col s12 l6 st-input-half-right">
					<input class="browser-default" type="text" name="st-last-name" placeholder="Last Name" />
				</div>
				<div class="input-field col s12">
					<input class="browser-default" type="email" name="st-email" placeholder="Email Address" />
				</div>
				<div class="input-field col s12">
					<input class="browser-default" type="password" name="st-password" placeholder="Password" />
				</div>
			</div>
			<div class="st-checkout-errors col s12"></div>
			<div class="st-checkout-buttons col s12">
				<a class="st-checkout-next st-checkout-btn pmt-button btn waves-effect waves-light" onclick="_st.checkout.next()">Next >></a>
			</div>
		</div>`
	}

	static pane2() {
		return `<div id="pane-2" class="st-checkout-pane">
			<div class="st-checkout-header col s12">
				<h2>What's your billing address?</h2>
				<span>This is the address associated with the card you are going to use for payment. We use this to verify your payment, so please check the accuracy of the information you provide.</span>
			</div>
			<div id="st-checkout-billing" class="st-checkout-form col s12 l6 push-l3">
				<div class="input-field col s12">
					<input class="browser-default" type="text" name="st-billing-address1" placeholder="Address 1" />
				</div>
				<div class="input-field col s12">
					<input class="browser-default" type="text" name="st-billing-address1" placeholder="Address 2" />
				</div>
				<div class="input-field col s12 l6 st-input-half-left">
					<input class="browser-default" type="text" name="st-billing-city" placeholder="City" />
				</div>
				<div class="input-field col s12 l6 st-input-half-right">
					<input class="browser-default" type="text" name="st-billing-state" placeholder="State" />
				</div>
				<div class="input-field col s12 l6 st-input-half-left">
					<input class="browser-default" type="text" name="st-billing-postal-code" placeholder="Postal Code" />
				</div>
				<div class="input-field col s12 l6 st-input-half-right">
					<input class="browser-default" type="text" name="st-billing-country" placeholder="Country" />
				</div>
			</div>
			<div class="st-checkout-buttons col s12">
				<a class="st-checkout-prev st-checkout-btn pmt-button btn waves-effect waves-light" onclick="_st.checkout.prev()"><< Back</a>
				<a class="st-checkout-next st-checkout-btn pmt-button btn waves-effect waves-light" onclick="_st.checkout.next()">Next >></a>
			</div>
		</div>`
	}

	static pane3() {
		return `<div id="pane-3" class="st-checkout-pane">
			<div class="st-checkout-header col s12">
				<h2>Where are we sending your books?</h2>
				<span>Even if you're signing up for a course that doesn't ship books, we still collect this information to keep on file in your account. We never share this information with anyone.</span>
			</div>
			<div id="st-checkout-shipping" class="st-checkout-form col s12 l6 push-l3">
				<div class="st-checkout-spaced col s12">
					<label>
						<input name="st-shipping-copy-billing" class="filled-in" type="checkbox" />
						<span>Same as billing address</span>
					</label>
				</div>
				<div class="st-checkout-spaced col s12">
					<label>
						<input name="st-shipping-priority" class="filled-in" type="checkbox" />
						<span>I want Priority Shipping (+$7.05, U.S. only)</span>
					</label>
				</div>
				<div class="input-field col s12">
					<input class="browser-default" type="text" name="st-billing-address1" placeholder="Address 1" />
				</div>
				<div class="input-field col s12">
					<input class="browser-default" type="text" name="st-billing-address1" placeholder="Address 2" />
				</div>
				<div class="input-field col s12 l6 st-input-half-left">
					<input class="browser-default" type="text" name="st-billing-city" placeholder="City" />
				</div>
				<div class="input-field col s12 l6 st-input-half-right">
					<input class="browser-default" type="text" name="st-billing-state" placeholder="State" />
				</div>
				<div class="input-field col s12 l6 st-input-half-left">
					<input class="browser-default" type="text" name="st-billing-postal-code" placeholder="Postal Code" />
				</div>
				<div class="input-field col s12 l6 st-input-half-right">
					<input class="browser-default" type="text" name="st-billing-country" placeholder="Country" />
				</div>
			</div>
			<div class="st-checkout-buttons col s12">
				<a class="st-checkout-prev st-checkout-btn pmt-button btn waves-effect waves-light" onclick="_st.checkout.prev()"><< Back</a>
				<a class="st-checkout-next st-checkout-btn pmt-button btn waves-effect waves-light" onclick="_st.checkout.next()">Next >></a>
			</div>
		</div>`
	}

	static pane4() {
		return `<div id="pane-4" class="st-checkout-pane">
			<div class="st-checkout-header col s12">
				<h2>Almost there!</h2>
				<span>Your total is below. Does everything look correct? If so, enter your credit card info and then hit submit! It's that easy! (Remember, you will not be charged until your {{trial}} day trial period is up.)</span>
			</div>
			<div id="st-checkout-shipping" class="st-checkout-form col s12 l6 push-l3">
				<div class="input-field col s12">
					<input class="browser-default" type="text" name="st-card-name" placeholder="Name on card" />
				</div>
				<div id="st-checkout-card-element" class="col s12"></div>
				<script>if (!_st.checkout.card) _st.checkout.setup()</script>
			</div>
			<div class="st-checkout-buttons col s12">
				<a class="st-checkout-prev st-checkout-btn pmt-button btn waves-effect waves-light" onclick="_st.checkout.prev()"><< Back</a>
				<a class="st-checkout-submit st-checkout-btn pmt-button btn waves-effect waves-light" onclick="_st.checkout.submit()" disabled>SUBMIT</a>
			</div>
		</div>`
	}

	next() {
		if (this.index < 4) {
			var pane = $('#pane-'+this.index)
			this.html[this.index] = pane
			this.index++
			this.render()
		}
	}

	prev() {
		if (this.index > 1) {
			var pane = $('#pane-'+this.index)
			this.html[this.index] = pane
			this.index--
			this.render()
		}
	}

	render(el) {
		var t = this
		if (t.index === 0) {
			t.html.forEach(function(l,i){
				if (typeof l !== 'string' || l === '') return
				
				t.html[i] = l.replace(/({{)(.*)(}})/g,(match,p1,p2,p3) => {
					return t.totals[p2]
				})

			})
			t.index++
		}
		
		if (typeof el !== 'undefined') {
			var wrapper = $('<div id="st-checkout-wrapper" class="col s12"></div>')
			wrapper.html(t.html[t.index])
			wrapper.appendTo(el)
		} else {
			var wrapper = $('#st-checkout-wrapper')
			wrapper.empty().append(t.html[t.index])
		}
		
		$('.st-checkout-pane').removeClass('active')
		setTimeout(function(){
			setTimeout(function(){
				$('#pane-'+t.index).addClass('active')
			},100)
		},100)
	}

	pricer(price) {
		return (Math.round(price)/100).toFixed(2)
	}

	update(obj) {
		if (obj !== null) {
			$.extend(this.totals,obj)
		}

		if ( this.state === this.totals ) {
			console.log('state unchanged')
			return false
		}
		var items = this.items,
			keys = Object.keys(items),
			tot = this.totals

			$('.items-row').fadeOut(100,function() {
			$(this).empty()

			for ( var i = 0; i < keys.length; i++ ) {
				if ( i === 0 ) {
					tot.total = tot.taxable = tot.tax.amt = 0
				}

				var item = items[keys[i]],
					price = item.price*item.qty

				if ( item.taxable !== false ) {
					tot.taxable += item.taxableAmt
				}

				tot.total += price

				$(this).append('<div class="row"><div class="col s2">'+item.qty+'</div><div class="col s8">'+item.name+'</div><div class="col s2 right-align">'+_st.checkout.pricer(price)+'</div></div>')
			}

			if ( 0 < tot.disc ) {
				var discprice = tot.disc;
			} else if ( 0 < tot.discp ) {
				var discprice = (tot.total*(tot.discp/100));
			}

			if (0 < tot.disc || 0 < tot.discp) {
				tot.total -= discprice
				$(this).append('<div class="row"><div class="col s2"></div><div class="col s8">Discount ('+tot.coupon+')</div><div class="col s2 right-align">-'+_st.checkout.pricer(discprice)+'</div></div>')
			}

			if ( tot.tax.rate > 0 ) {
				tot.tax.amt = (tot.taxable*tot.tax.rate)/100
				tot.total += tot.tax.amt
				$(this).append('<div class="row"><div class="col s2"></div><div class="col s8">'+tot.tax.msg+'</div><div class="col s2 right-align">+'+_st.checkout.pricer(tot.tax.amt)+'</div></div>')
			}

			if ( tot.shipping > 0 ) {
				tot.total += tot.shipping
				$(this).append('<div class="row"><div class="col s2"></div><div class="col s8">Priority Shipping</div><div class="col s2 right-align">+'+_st.checkout.pricer(tot.shipping)+'</div></div>')
			}

			$('#ttltxt>span').text(_st.checkout.pricer(tot.total))
		}).fadeIn(100);

		this.state = $.extend( true, {}, this.totals );
	}

	setOutcome( result, con ) {
		if ( typeof result.error !== 'undefined' ) {
			$( '.error', con ).text( result.error.message );
		} else {
			$( '.error', con ).text( '' );
		}

		var inputs = $( 'input, select', con )
		_st.checkout.validate( inputs, con, result.complete )
	}

	setup() {
		var stripe = Stripe(_st.stripe.publicKey)
		var elements = stripe.elements()
		var card = elements.create('card',{
			hidePostalCode: true
		});
		card.mount('#st-checkout-card-element');

		card.on( 'change', function( event ) {
			this.setOutcome( event, '#checkout-wrapper' )
		});
		this.card = true
		/* M.updateTextFields()
		$('select').formSelect()
		this.update() */
	}

	submit( data ) {
		_st.modal.loader(function(){
			var mo = $('#modal_loading_overlay')

			mo.find('*').not('img').remove()

			mo.append('<h2 style="margin-top:4em">Authorizing card...</h2>')
				.append('<span>(Patience, young padawan... This will take a moment.)</span>');
		})
		var det = {
			name: data.cardname,
			address_line1: data.billing_address1,
			address_line2: data.billing_address2,
			address_city: data.billing_city,
			address_state: data.billing_state,
			address_zip: data.billing_pcode,
			address_country: data.billing_country
		}

		stripe.createToken(card, det).then(function(result){
			if (result.error) {
				console.log(result.error)
				return _st.modal.loader(function(el){
					$('.error',el).text(result.error.message)
				})
			} else {
				$('#modal_loading_overlay h2').text('Processing order...')
				data.token = result.token
				data.cart = _st.cart.cartObj

				_st.request({
					route : '/checkout',
					method : 'POST',
					cdata : data,
					success : function(d) {
						console.log(d)

						if ( 'error' === d.code ) {
							var ecode = d.error.decline_code || d.error.code
							_st.modal.loader(function(el){
								$('p.error',el).html('<span class="col s12">We\'re sorry. '+d.error.message+'</span><span class="col s12">err code: '+ecode+'</span>')
							})
						} else if ( 'success' === d.code ) {
							var success = $('<div/>',{
								id: 'success',
								'class': 'col s12'
							}).append('<h2><i class="material-icons">done</i></h2><br/><span>'+d.message+'</span>')

							$('#modal_loading_overlay')
								.empty()
								.append(success)

							var lines = d.order.invoice.data[0].lines.data,
								tax = shipping = '0',
								coupon = d.order.invoice.data[0].discount || ''

							for (var i = 0, len = lines.length; i < len; i++) {
								var line = lines[i]

								if ( line.description === 'Sales tax' ) {
									tax = (line.amount/100).toFixed(2)
								} else if ( line.description === 'Priority Shipping' ) {
									shipping = (line.amount/100).toFixed(2)
								}
							}

							_st.analytics({
								type : 'ec:setAction',
								action : 'purchase',
								data : {
									'id' : d.cart.ID,
									'revenue' : (d.order.invoice.data[0].amount_paid/100).toFixed(2),
									'tax' : tax,
									'shipping' : shipping,
									'coupon' : coupon,
									'affiliation' : 'SupertutorTV Online Store'
								},
								pageview : true,
								page : '/checkout'
							})

							_st.cart.unset()

							setTimeout(function(){
								window.location.href = d.order.redirect
							},3000)
						}
					},
					error : function(x) {
						var d = x[0].responseJSON
						console.log(x,d)
					}
				})
			}
		})
	}

	validate( inputs, context, extra ) {
		if ( typeof extra === 'undefined' ) {
			extra = true
		}

		var cEr = true

		inputs.each( function( k, v ) {
			var t = $(this),
				msgTag = ''

			if ( t.is(':required') && ( !t.val() || t.hasClass('invalid') ) ) {
				var msgTag = ( !t.val() ) ? ' is required' : ' is invalid'
				cEr = false
				$( '.error', context ).html( $(v).siblings('label').text()+msgTag );
			} else if ( t.hasClass('invalid') ) {
				cEr = false
				$( '.error', context ).html( $(v).siblings('label').text()+' is invalid' );
			}

			if ( !cEr ) { return cEr }
		});

		$( '.signup-submit', context ).prop( 'disabled', !( cEr && extra ) );

		return ( cEr && extra )
	}
}

export default Checkout
